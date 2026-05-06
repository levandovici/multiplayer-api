# Realtime Multiplayer Server

A WebSocket-based realtime server for multiplayer games using TCP connections.

## Features

- **WebSocket Connections**: Real-time bidirectional communication
- **Token-Based Authentication**: Secure connection management
- **Room Management**: Players can join/leave rooms dynamically
- **Targeted Messaging**: Send messages to all, host, others, or specific players
- **Connection Management**: Automatic cleanup of inactive connections
- **Database Integration**: MySQL backend for persistence

## Installation

1. Install dependencies:
```bash
npm install
```

2. Copy environment configuration:
```bash
cp .env.example .env
```

3. Edit `.env` with your database and server configuration.

## Database Setup

Make sure your MySQL database includes the `realtime_players` table (included in schema.sql):

```sql
CREATE TABLE IF NOT EXISTS realtime_players (
    id INT AUTO_INCREMENT PRIMARY KEY,
    game_player_id INT NOT NULL,
    game_id INT NOT NULL,
    game_room_id VARCHAR(36) NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    connection_id VARCHAR(64) NULL,
    is_connected BOOLEAN NOT NULL DEFAULT FALSE,
    connected_at TIMESTAMP NULL,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_game_player (game_player_id),
    INDEX idx_game_id (game_id),
    INDEX idx_room_id (game_room_id),
    INDEX idx_token (token),
    INDEX idx_connection (connection_id),
    INDEX idx_connected (is_connected, last_activity),
    
    FOREIGN KEY (game_player_id) REFERENCES game_players(id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES api_keys(id) ON DELETE CASCADE,
    FOREIGN KEY (game_room_id) REFERENCES game_rooms(room_id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Usage

### Starting the Server

```bash
# Production
npm start

# Development (with auto-restart)
npm run dev
```

### Client Connection

1. **Get Token**: Call the API endpoint `/api/realtime.php/token` with your API token and player token
2. **Connect**: Connect to WebSocket server using the received token
   ```javascript
   const ws = new WebSocket(`wss://realtime.michitai.com?token=${token}`);
   ```

### Message Protocol

#### Send Message
```json
{
  "type": "send",
  "data": {
    "command": "move_player",
    "json": "{\"x\": 100, \"y\": 200}",
    "target": "all", // "all", "host", "others", "specific"
    "target_ids": [1, 2, 3] // Only for "specific" target
  }
}
```

#### Receive Message
```json
{
  "type": "receive",
  "data": {
    "command": "move_player",
    "json": "{\"x\": 100, \"y\": 200}",
    "sender": {
      "is_host": false,
      "game_player_id": 123,
      "player_name": "Player1"
    }
  }
}
```

#### Join/Leave Room
```json
// Join room
{
  "type": "join_room",
  "data": {
    "room_id": "room-uuid-here"
  }
}

// Leave room
{
  "type": "leave_room",
  "data": {}
}
```

### API Endpoints

#### Generate Token
```
POST /api/realtime.php/token
Headers: X-Player-Token: <player_token>
Query: api_token=<api_token>

Response:
{
  "success": true,
  "token": "64-char-hex-token",
  "player_info": {
    "player_id": 123,
    "player_name": "Player1",
    "room_id": "room-uuid",
    "is_host": false
  },
  "realtime_server": {
    "host": "localhost",
    "port": 8081,
    "protocol": "ws"
  }
}
```

#### Validate Token
```
GET /api/realtime.php/token/validate?token=<token>

Response:
{
  "success": true,
  "valid": true,
  "already_used": false,
  "player_info": {
    "player_id": 123,
    "player_name": "Player1",
    "game_id": 1,
    "room_id": "room-uuid"
  }
}
```

#### Revoke Token
```
POST /api/realtime.php/token/revoke
Headers: X-Player-Token: <player_token>
Query: api_token=<api_token>

Response:
{
  "success": true,
  "message": "Token revoked successfully"
}
```

## Integration with Unity/.NET

### Unity C# Example
```csharp
public class RealtimeConnection : MonoBehaviour
{
    private WebSocket websocket;
    
    async void Start()
    {
        // Get token from API
        string token = await GetRealtimeToken();
        
        // Connect to WebSocket
        websocket = new WebSocket($"wss://realtime.michitai.com?token={token}");
        
        websocket.OnOpen += () => {
            Debug.Log("Connected to realtime server");
        };
        
        websocket.OnMessage += (bytes) => {
            var message = Encoding.UTF8.GetString(bytes);
            HandleRealtimeMessage(message);
        };
        
        await websocket.Connect();
    }
    
    public void SendCommand(string command, string json = null, string target = "all", int[] targetIds = null)
    {
        var message = new {
            type = "send",
            data = new {
                command = command,
                json = json,
                target = target,
                target_ids = targetIds
            }
        };
        
        websocket.SendText(JsonConvert.SerializeObject(message));
    }
    
    void HandleRealtimeMessage(string message)
    {
        var data = JsonConvert.DeserializeObject<RealtimeMessage>(message);
        
        if (data.type == "receive")
        {
            // Handle incoming realtime message
            OnReceiveCommand(data.data.command, data.data.json, data.data.sender);
        }
    }
    
    void OnReceiveCommand(string command, string json, SenderInfo sender)
    {
        // Process received command
        Debug.Log($"Received {command} from {sender.player_name}");
        
        // Example: Move player
        if (command == "move_player")
        {
            var position = JsonConvert.DeserializeObject<Vector2>(json);
            // Update player position...
        }
    }
}
```

## Configuration

### Environment Variables

- `DB_HOST`: Database host (default: localhost)
- `DB_PORT`: Database port (default: 3306)
- `DB_NAME`: Database name
- `DB_USER`: Database username
- `DB_PASSWORD`: Database password
- `REALTIME_PORT`: WebSocket server port (default: 8081)
- `REALTIME_HOST`: WebSocket server host (default: 0.0.0.0)
- `JWT_SECRET`: JWT secret key for future authentication
- `LOG_LEVEL`: Logging level (info, debug, error)

## Security Features

- **Token Validation**: Only valid tokens can connect
- **One Connection Per Player**: Automatically drops previous connections
- **Room Isolation**: Players can only send messages within their room
- **Activity Monitoring**: Automatic cleanup of inactive connections

## Monitoring

The server logs all connection events, messages, and errors. Monitor these logs for:

- Connection patterns
- Message volume
- Error rates
- Security issues

## Deployment

### Hostinger Deployment

1. Upload the `realtime` folder to your hosting
2. Install Node.js dependencies via SSH
3. Configure environment variables
4. Run the server using PM2 or similar process manager
5. Configure firewall to allow WebSocket connections on the specified port

### PM2 Configuration

Create `ecosystem.config.js`:
```javascript
module.exports = {
  apps: [{
    name: 'realtime-server',
    script: 'server.js',
    instances: 1,
    autorestart: true,
    watch: false,
    max_memory_restart: '1G',
    env: {
      NODE_ENV: 'production'
    }
  }]
};
```

Start with:
```bash
pm2 start ecosystem.config.js
```
