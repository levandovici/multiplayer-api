const WebSocket = require('ws');
const mysql = require('mysql2/promise');
const { v4: uuidv4 } = require('uuid');
const http = require('http');
const url = require('url');
require('dotenv').config();

class RealtimeServer {
    constructor() {
        this.wss = null;
        this.db = null;
        this.connections = new Map(); // Map of connection_id -> connection info
        this.playerConnections = new Map(); // Map of player_id -> connection_id
        this.roomConnections = new Map(); // Map of room_id -> Set of connection_ids
        this.invalidatedTokens = new Set(); // Set of invalidated player tokens
        this.cleanupInterval = null;
    }

    async init() {
        try {
            await this.connectDatabase();
            this.setupWebSocketServer();
            this.startCleanupInterval();
            console.log('Realtime server initialized');
        } catch (error) {
            console.error('Failed to initialize server:', error);
            process.exit(1);
        }
    }

    async connectDatabase() {
        if (!process.env.DB_USER || !process.env.DB_PASSWORD || !process.env.DB_NAME) {
            throw new Error('Missing database environment variables');
        }

        this.db = await mysql.createConnection({
            host: process.env.DB_HOST || 'localhost',
            port: process.env.DB_PORT || 3306,
            user: process.env.DB_USER,
            password: process.env.DB_PASSWORD,
            database: process.env.DB_NAME,
            charset: 'utf8mb4'
        });
    }

    setupWebSocketServer() {
        const port = process.env.PORT || 3000;
        
        // Create HTTP server first
        const httpServer = http.createServer((req, res) => {
            const parsedUrl = url.parse(req.url, true);
            
            if (parsedUrl.pathname === '/' && req.method === 'GET') {
                console.log("Wake request");
                res.writeHead(200, { 'Content-Type': 'text/plain' });
                res.end("OK");
            } else if (parsedUrl.pathname === '/disconnect' && req.method === 'GET') {
                this.handleDisconnectRequest(req, res);
            } else {
                res.writeHead(404);
                res.end('Not Found');
            }
        });
        
        // Create WebSocket server from HTTP server
        this.wss = new WebSocket.Server({ server: httpServer });

        httpServer.on('error', (error) => {
            console.error('HTTP server error:', error.message);
        });

        this.wss.on('error', (error) => {
            console.error('WebSocket server error:', error.message);
        });

        this.wss.on('connection', this.handleConnection.bind(this));
        
        httpServer.listen(port, () => {
            console.log(`Server listening on port ${port}`);
            console.log(`WebSocket endpoint: ws://localhost:${port}`);
            console.log(`HTTP disconnect endpoint: http://localhost:${port}/disconnect`);
        });
    }



    async handleConnection(ws, req) {
        console.log(`URL: ${req.url}`);

        const url = new URL(req.url, 'http://localhost');
        const token = url.searchParams.get('token');
        const clientType = url.searchParams.get('client') || 'json';
        const connectionId = uuidv4();

        console.log(`Token: ${token} Client: ${clientType}`);

        if (!token) {
            ws.close(1008, 'Token required');
            return;
        }

        // Check if token has been invalidated
        if (this.invalidatedTokens.has(token)) {
            console.log('Rejected connection with invalidated token');
            ws.close(1008, 'Token invalidated');
            return;
        }

        try {
            const [rows] = await this.db.execute(
                `SELECT rp.*, gp.player_name, gp.game_id 
                FROM realtime_players rp 
                JOIN game_players gp ON rp.game_player_id = gp.id 
                WHERE rp.token = ?`, 
                [token]
            );

            if (rows.length === 0) {
                console.log('No player found for token');
                ws.close(1008, 'Invalid token');
                return;
            }

            const playerInfo = rows[0];
            console.log(`Player found: ${playerInfo.player_name}`);

            await this.dropExistingConnection(playerInfo.game_player_id);

            await this.db.execute(
                'UPDATE realtime_players SET is_connected = TRUE, connection_id = ?, connected_at = NOW(), last_activity = NOW() WHERE id = ?',
                [connectionId, playerInfo.id]
            );

            const connectionInfo = {
                ws,
                connectionId,
                playerId: playerInfo.game_player_id,
                playerName: playerInfo.player_name,
                gameId: playerInfo.game_id,
                roomId: playerInfo.game_room_id,
                playerToken: token,
                clientType: clientType,
                connectedAt: new Date(),
                lastActivity: new Date()
            };

            this.connections.set(connectionId, connectionInfo);
            this.playerConnections.set(playerInfo.game_player_id, connectionId);

            if (playerInfo.game_room_id) {
                if (!this.roomConnections.has(playerInfo.game_room_id)) {
                    this.roomConnections.set(playerInfo.game_room_id, new Set());
                }
                this.roomConnections.get(playerInfo.game_room_id).add(connectionId);
            }

            console.log(`Player ${playerInfo.player_name} connected (${this.connections.size} total)`);

            ws.on('message', (data) => this.handleMessage(connectionId, data));
            ws.on('close', () => this.handleDisconnection(connectionId));
            ws.on('error', (error) => this.handleError(connectionId, error));

            this.sendToConnection(connectionId, {
                type: 'connected',
                data: {
                    connection_id: connectionId,
                    player_id: playerInfo.game_player_id,
                    player_name: playerInfo.player_name,
                    room_id: playerInfo.game_room_id,
                    client_type: clientType
                }
            });

        } catch (error) {
            console.error('Connection error:', error.message);
            
            try {
                if (ws.readyState === WebSocket.OPEN) {
                    ws.close(1011, 'Internal server error');
                } else {
                    ws.terminate();
                }
            } catch {}

            this.connections.delete(connectionId);
            this.playerConnections.delete(playerInfo.game_player_id);
        }
    }

    async handleMessage(connectionId, data) {
        try {
            const connection = this.connections.get(connectionId);
            if (!connection || connection.ws.readyState !== WebSocket.OPEN) return;

            console.log(`Message: ${data.toString()}`);

            const message = JSON.parse(data.toString());
            connection.lastActivity = new Date();

            await this.db.execute(
                'UPDATE realtime_players SET last_activity = NOW() WHERE connection_id = ?',
                [connectionId]
            );

            if (message.type === 'send') {
                await this.handleSendCommand(connection, message);
            } else if (message.type === 'heartbeat') {
                await this.handleHeartbeat(connection);
            }
        } catch (error) {
            console.error('Message handling error:', error);
            const connection = this.connections.get(connectionId);
            if (connection && connection.ws.readyState === WebSocket.OPEN) {
                this.sendToConnection(connectionId, {
                    type: 'error',
                    data: { message: 'Failed to process message' }
                });
            }
        }
    }

    async handleHeartbeat(connection) {
        // Update last activity timestamp for heartbeat
        connection.lastActivity = new Date();
        
        await this.db.execute(
            'UPDATE realtime_players SET last_activity = NOW() WHERE connection_id = ?',
            [connection.connectionId]
        );

        // Send heartbeat acknowledgment back to client
        this.sendToConnection(connection.connectionId, {
            type: 'heartbeat_ack',
            timestamp: new Date().toISOString()
        });
    }

    async handleSendCommand(connection, message) {
        const { command, data, target, target_ids } = message;
        
        console.log(`Command: ${command}, Data: ${data}, Target: ${target}, Target IDs: ${target_ids}`);

        let targetConnections = [];
        
        switch (target) {
            case 'all':
                if (connection.roomId) {
                    targetConnections = Array.from(this.roomConnections.get(connection.roomId) || []);
                }
                break;
            case 'host':
                targetConnections = this.getHostConnections(connection.roomId);
                break;
            case 'others':
                targetConnections = this.getOtherConnections(connection.connectionId, connection.roomId);
                break;
            case 'specific':
                // target_ids is now a separate parameter
                if (target_ids && Array.isArray(target_ids)) {
                    targetConnections = this.getSpecificConnections(target_ids);
                }
                break;
            default:
                throw new Error('Invalid target specified');
        }

        // Format message based on client type
        let messageData;
        if (connection.clientType === 'json') {
            // .NET format: data as object
            messageData = {
                type: 'receive',
                command,
                data: data || null,
                sender: {
                    is_host: await this.isPlayerHost(connection.playerId, connection.roomId),
                    game_player_id: connection.playerId,
                    player_name: connection.playerName
                }
            };
        } else if (connection.clientType === 'unity') {
            // Unity format: data_json as string
            messageData = {
                type: 'receive',
                command,
                data_json: typeof data === 'string' ? data : (data ? JSON.stringify(data) : null),
                sender: {
                    is_host: await this.isPlayerHost(connection.playerId, connection.roomId),
                    game_player_id: connection.playerId,
                    player_name: connection.playerName
                }
            };
        } else {
            throw new Error(`Invalid client type: ${connection.clientType}. Supported types: 'json', 'unity'`);
        }

        for (const targetConnectionId of targetConnections) {
            if (targetConnectionId !== connection.connectionId) {
                this.sendToConnection(targetConnectionId, messageData);
            }
        }

        // Send confirmation to sender
        this.sendToConnection(connection.connectionId, {
            type: 'sent',
            command,
            target_count: targetConnections.length - 1 // Exclude sender
        });
    }

    async handleDisconnection(connectionId, invalidateToken = false) {
        const connection = this.connections.get(connectionId);
        if (!connection) return;

        console.log(`Player ${connection.playerName} disconnected`);

        // Only invalidate token if specifically requested (forced disconnects, timeouts)
        if (invalidateToken && connection.playerToken) {
            this.invalidatedTokens.add(connection.playerToken);
            console.log(`Invalidated token: ${connection.playerToken.substring(0, 8)}...`);
        }

        // Remove from memory structures
        this.connections.delete(connectionId);
        this.playerConnections.delete(connection.playerId);

        if (connection.roomId) {
            const roomConnections = this.roomConnections.get(connection.roomId);
            if (roomConnections) {
                roomConnections.delete(connectionId);
                if (roomConnections.size === 0) {
                    this.roomConnections.delete(connection.roomId);
                }
            }
        }
    }

    handleError(connectionId, error) {
        console.error('Connection error:', error);
        // Don't invalidate token on connection errors (allow reconnection)
        this.handleDisconnection(connectionId, false);
    }


    async dropExistingConnection(playerId) {
        const existingConnectionId = this.playerConnections.get(playerId);
        if (!existingConnectionId) return;

        const conn = this.connections.get(existingConnectionId);
        if (conn) {
            try {
                conn.ws.terminate();
            } catch {}
            await this.handleDisconnection(existingConnectionId, false);
        }
    }

    getHostConnections(roomId) {
        if (!roomId) return [];
        
        const roomConnections = this.roomConnections.get(roomId) || new Set();
        const hostConnections = [];
        
        for (const connectionId of roomConnections) {
            const connection = this.connections.get(connectionId);
            if (connection && this.isPlayerHostSync(connection.playerId, roomId)) {
                hostConnections.push(connectionId);
            }
        }
        
        return hostConnections;
    }

    getOtherConnections(excludeConnectionId, roomId) {
        if (!roomId) return [];
        
        const roomConnections = this.roomConnections.get(roomId) || new Set();
        return Array.from(roomConnections).filter(id => id !== excludeConnectionId);
    }

    getSpecificConnections(playerIds) {
        const targetConnections = [];
        
        for (const playerId of playerIds) {
            const connectionId = this.playerConnections.get(playerId);
            if (connectionId && this.connections.has(connectionId)) {
                targetConnections.push(connectionId);
            }
        }
        
        return targetConnections;
    }

    isPlayerHost(playerId, roomId) {
        if (!roomId) return false;
        
        const roomConnections = this.roomConnections.get(roomId) || new Set();
        for (const connectionId of roomConnections) {
            const connection = this.connections.get(connectionId);
            if (connection && connection.playerId === playerId) {
                return connectionId === Array.from(roomConnections)[0];
            }
        }
        return false;
    }

    sendToConnection(connectionId, message) {
        const connection = this.connections.get(connectionId);
        if (connection && connection.ws.readyState === WebSocket.OPEN) {
            try {
                connection.ws.send(JSON.stringify(message));
            } catch (error) {
                console.error('Send error:', error.message);
            }
        }
    }

    startCleanupInterval() {
        // Run cleanup every 30 seconds to check for inactive connections
        this.cleanupInterval = setInterval(async () => {
            await this.cleanupInactiveConnections();
        }, 30000);
    }

    async cleanupInactiveConnections() {
        const now = new Date();
        const inactiveThreshold = 60 * 1000; // 60 seconds in milliseconds
        const connectionsToDisconnect = [];

        // Check all active connections for inactivity
        for (const [connectionId, connection] of this.connections) {
            const timeSinceLastActivity = now - connection.lastActivity;
            
            if (timeSinceLastActivity > inactiveThreshold) {
                console.log(`Player ${connection.playerName} inactive for ${Math.round(timeSinceLastActivity / 1000)}s, disconnecting`);
                connectionsToDisconnect.push(connectionId);
            }
        }

        // Disconnect inactive connections without database access
        for (const connectionId of connectionsToDisconnect) {
            const connection = this.connections.get(connectionId);
            if (connection) {
                try {
                    // Close WebSocket connection
                    if (connection.ws.readyState === WebSocket.OPEN) {
                        connection.ws.close(1000, 'Inactive timeout');
                    }

                    // Remove from memory (don't invalidate token for timeout - allow reconnection)
                    this.handleDisconnection(connectionId, false);
                } catch (error) {
                    console.error('Error disconnecting inactive connection:', error.message);
                }
            }
        }

        // Also clean up database records for players that haven't been seen recently
        await this.cleanupInactiveDatabaseRecords();
    }

    async cleanupInactiveDatabaseRecords() {
        try {
            // Mark players as inactive if they haven't had activity in 60 seconds
            await this.db.execute(
                `UPDATE realtime_players 
                 SET is_connected = FALSE, connection_id = NULL 
                 WHERE is_connected = TRUE 
                 AND last_activity < DATE_SUB(NOW(), INTERVAL 60 SECOND)`
            );
        } catch (error) {
            console.error('Database cleanup error:', error.message);
        }
    }

    async handleDisconnectRequest(req, res) {
        try {
            const parsedUrl = url.parse(req.url, true);
            const { room_id, player_token } = parsedUrl.query;

            if (!room_id && !player_token) {
                res.writeHead(400, { 'Content-Type': 'application/json' });
                return res.end(JSON.stringify({ error: 'room_id or player_token required' }));
            }

            let disconnectedCount = 0;
            let disconnectType = '';

            if (player_token && !room_id) {
                // Disconnect specific player by token using in-memory data
                await this.forceDisconnectPlayerByToken(player_token);
                disconnectedCount = 1;
                disconnectType = 'player';
            } else if (room_id && !player_token) {
                // Disconnect all players in a specific room using in-memory data
                const roomConnections = this.roomConnections.get(room_id);
                if (roomConnections) {
                    disconnectedCount = roomConnections.size;
                    await this.forceDisconnectRoom(room_id);
                }
                disconnectType = 'room';
            } else {
                res.writeHead(400, { 'Content-Type': 'application/json' });
                return res.end(JSON.stringify({ error: 'Invalid parameter combination. Use either player_token or room_id' }));
            }

            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ 
                success: true, 
                disconnected_count: disconnectedCount,
                disconnect_type: disconnectType,
                message: `Disconnected ${disconnectedCount} players (${disconnectType})`
            }));
        } catch (error) {
            console.error('Disconnect request error:', error.message);
            if (!res.headersSent) {
                res.writeHead(500, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ error: 'Internal server error' }));
            }
        }
    }

    async forceDisconnectRoom(roomId) {
        const roomConnections = this.roomConnections.get(roomId);
        if (roomConnections) {
            for (const connectionId of roomConnections) {
                const connection = this.connections.get(connectionId);
                if (connection && connection.ws.readyState === WebSocket.OPEN) {
                    try {
                        connection.ws.close(1000, 'Room cleanup');
                        this.handleDisconnection(connectionId, true);
                    } catch (error) {
                        console.error('Error forcing disconnect:', error.message);
                    }
                }
            }
        }
    }

    
    async forceDisconnectPlayerByToken(playerToken) {
        // Find connection for this player token in memory
        let connectionToDisconnect = null;
        
        for (const [connectionId, connection] of this.connections) {
            if (connection.playerToken === playerToken) {
                connectionToDisconnect = connection;
                break;
            }
        }

        if (connectionToDisconnect && connectionToDisconnect.ws.readyState === WebSocket.OPEN) {
            try {
                connectionToDisconnect.ws.close(1000, 'Player disconnect');
                this.handleDisconnection(connectionToDisconnect.connectionId, true);
                console.log(`Force disconnected player with token: ${playerToken.substring(0, 8)}...`);
            } catch (error) {
                console.error('Error forcing player disconnect:', error.message);
            }
        }
    }

    
}

// Start the server
const server = new RealtimeServer();
server.init().catch(console.error);
