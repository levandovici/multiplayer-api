#!/usr/bin/php
<?php
/**
 * cleanup.php
 * Cron job - cleans up inactive players, abandoned rooms, and matchmaking lobbies
 *
 * Recommended execution frequency: every 1-2 minutes
 *
 * Rules (2026 version):
 * • Player → offline if no heartbeat for > 1 minute
 * • Matchmaking → removed if host offline and no host switch, or host switched to online player
 * • Room → handled with can_leave and is_active logic
 * • Room → hard deleted after 5 minutes if inactive and no reconnection
 */

require_once __DIR__ . '/config.php';

if (!isset($pdo) || !($pdo instanceof PDO)) {
    error_log("cleanup.php: Database connection not available");
    exit(1);
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ────────────────────────────────────────────────
// Configuration
// ────────────────────────────────────────────────
const HEARTBEAT_TIMEOUT_MIN       = 1;    // player considered offline after 1 minute
const RECONNECT_TIMEOUT_MIN       = 5;    // room reconnection timeout
const ROOM_HARD_DELETE_MIN         = 60;   // hard delete inactive rooms after 1 hour
// ────────────────────────────────────────────────

$now = new DateTimeImmutable();
$heartbeatThreshold = $now->modify('-' . HEARTBEAT_TIMEOUT_MIN . ' minutes');
$reconnectThreshold = $now->modify('-' . RECONNECT_TIMEOUT_MIN . ' minutes');
$hardDeleteThreshold = $now->modify('-' . ROOM_HARD_DELETE_MIN . ' minutes');

try {
    $pdo->beginTransaction();

    // ============================================
    // 1. Clean up game_players table
    // ============================================
    $stmt = $pdo->prepare("
        UPDATE game_players
        SET is_online = FALSE,
            last_logout = NOW()
        WHERE is_online = TRUE
          AND last_heartbeat < :threshold
    ");
    $stmt->execute([':threshold' => $heartbeatThreshold->format('Y-m-d H:i:s')]);
    $gamePlayersOffline = $stmt->rowCount();

    // ============================================
    // 2. Clean up matchmaking_players table
    // ============================================
    $stmt = $pdo->prepare("
        UPDATE matchmaking_players
        SET is_online = FALSE
        WHERE is_online = TRUE
          AND last_heartbeat < :threshold
    ");
    $stmt->execute([':threshold' => $heartbeatThreshold->format('Y-m-d H:i:s')]);
    $matchmakingPlayersOffline = $stmt->rowCount();

    // ============================================
    // 3. Clean up room_players table
    // ============================================
    $stmt = $pdo->prepare("
        UPDATE room_players
        SET is_online = FALSE
        WHERE is_online = TRUE
          AND last_heartbeat < :threshold
    ");
    $stmt->execute([':threshold' => $heartbeatThreshold->format('Y-m-d H:i:s')]);
    $roomPlayersOffline = $stmt->rowCount();

    // ============================================
    // 4. Clean up matchmakings - handle offline hosts
    // ============================================
    
    // 4a. Remove matchmakings where host is offline and host_switch is disabled
    $stmt = $pdo->prepare("
        DELETE m, mp, mr
        FROM matchmaking m
        LEFT JOIN matchmaking_players mp ON mp.matchmaking_id = m.matchmaking_id
        LEFT JOIN matchmaking_requests mr ON mr.matchmaking_id = m.matchmaking_id
        WHERE m.is_started = FALSE
          AND m.host_player_id IN (
              SELECT gp.id
              FROM game_players gp
              WHERE gp.id = m.host_player_id
                AND gp.is_online = FALSE
          )
          AND m.host_switch = FALSE
    ");
    $stmt->execute();
    $matchmakingsRemovedNoSwitch = $stmt->rowCount();

    // 4b. Switch hosts for matchmakings where host is offline and host_switch is enabled
    $stmt = $pdo->prepare("
        SELECT m.matchmaking_id, m.host_player_id
        FROM matchmaking m
        WHERE m.is_started = FALSE
          AND m.host_player_id IN (
              SELECT gp.id
              FROM game_players gp
              WHERE gp.id = m.host_player_id
                AND gp.is_online = FALSE
          )
          AND m.host_switch = TRUE
          AND EXISTS (
              SELECT 1
              FROM matchmaking_players mp
              JOIN game_players gp ON gp.id = mp.player_id
              WHERE mp.matchmaking_id = m.matchmaking_id
                AND gp.is_online = TRUE
                AND mp.player_id != m.host_player_id
              LIMIT 1
          )
    ");
    $stmt->execute();
    $matchmakingsNeedingHostSwitch = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($matchmakingsNeedingHostSwitch as $matchmaking) {
        // Find first online player to become new host
        $newHostStmt = $pdo->prepare("
            SELECT mp.player_id
            FROM matchmaking_players mp
            JOIN game_players gp ON gp.id = mp.player_id
            WHERE mp.matchmaking_id = :matchmaking_id
              AND gp.is_online = TRUE
              AND mp.player_id != :current_host
            ORDER BY mp.joined_at ASC
            LIMIT 1
        ");
        $newHostStmt->execute([
            ':matchmaking_id' => $matchmaking['matchmaking_id'],
            ':current_host' => $matchmaking['host_player_id']
        ]);
        $newHost = $newHostStmt->fetch(PDO::FETCH_ASSOC);

        if ($newHost) {
            $updateStmt = $pdo->prepare("
                UPDATE matchmaking
                SET host_player_id = :new_host
                WHERE matchmaking_id = :matchmaking_id
            ");
            $updateStmt->execute([
                ':new_host' => $newHost['player_id'],
                ':matchmaking_id' => $matchmaking['matchmaking_id']
            ]);
        }
    }

    // 4c. Remove matchmakings where host is offline, host_switch is enabled, but no online players available
    $stmt = $pdo->prepare("
        DELETE m, mp, mr
        FROM matchmaking m
        LEFT JOIN matchmaking_players mp ON mp.matchmaking_id = m.matchmaking_id
        LEFT JOIN matchmaking_requests mr ON mr.matchmaking_id = m.matchmaking_id
        WHERE m.is_started = FALSE
          AND m.host_player_id IN (
              SELECT gp.id
              FROM game_players gp
              WHERE gp.id = m.host_player_id
                AND gp.is_online = FALSE
          )
          AND m.host_switch = TRUE
          AND NOT EXISTS (
              SELECT 1
              FROM matchmaking_players mp
              JOIN game_players gp ON gp.id = mp.player_id
              WHERE mp.matchmaking_id = m.matchmaking_id
                AND gp.is_online = TRUE
                AND mp.player_id != m.host_player_id
          )
    ");
    $stmt->execute();
    $matchmakingsRemovedNoPlayers = $stmt->rowCount();

    // ============================================
    // 5. Clean up rooms - handle offline hosts and complex logic
    // ============================================
    
    // 5a. Get rooms that need processing (host offline)
    $stmt = $pdo->prepare("
        SELECT gr.room_id, gr.host_player_id, gr.can_leave, gr.is_active, gr.matchmaking_id,
               (SELECT COUNT(*) FROM room_players rp WHERE rp.room_id = gr.room_id AND rp.is_online = TRUE) as online_players_count
        FROM game_rooms gr
        WHERE gr.is_active = TRUE
          AND gr.host_player_id IS NOT NULL
          AND gr.host_player_id IN (
              SELECT rp.player_id
              FROM room_players rp
              WHERE rp.player_id = gr.host_player_id
                AND rp.is_online = FALSE
          )
    ");
    $stmt->execute();
    $roomsNeedingCleanup = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($roomsNeedingCleanup as $room) {
        if ($room['can_leave'] === FALSE) {
            // Don't remove players, just set them offline (already done above)
            // Check if all players are offline and room is inactive
            if ($room['online_players_count'] == 0 && $room['is_active'] === FALSE) {
                // Check if reconnection timeout has passed
                $stmt = $pdo->prepare("
                    SELECT updated_at FROM game_rooms 
                    WHERE room_id = :room_id AND is_active = FALSE
                ");
                $stmt->execute([':room_id' => $room['room_id']]);
                $roomData = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($roomData && strtotime($roomData['updated_at']) < $reconnectThreshold->getTimestamp()) {
                    // Remove room and associated matchmaking
                    removeRoomAndMatchmaking($pdo, $room['room_id'], $room['matchmaking_id']);
                }
            }
        } else {
            // can_leave = TRUE, handle like matchmakings
            if ($room['host_switch'] === FALSE) {
                // Remove room if no host switch
                removeRoomAndMatchmaking($pdo, $room['room_id'], $room['matchmaking_id']);
            } else {
                // Try to find new host
                $newHostStmt = $pdo->prepare("
                    SELECT player_id
                    FROM room_players
                    WHERE room_id = :room_id
                      AND is_online = TRUE
                      AND player_id != :current_host
                    ORDER BY joined_at ASC
                    LIMIT 1
                ");
                $newHostStmt->execute([
                    ':room_id' => $room['room_id'],
                    ':current_host' => $room['host_player_id']
                ]);
                $newHost = $newHostStmt->fetch(PDO::FETCH_ASSOC);

                if ($newHost) {
                    // Switch host
                    $updateStmt = $pdo->prepare("
                        UPDATE game_rooms
                        SET host_player_id = :new_host
                        WHERE room_id = :room_id
                    ");
                    $updateStmt->execute([
                        ':new_host' => $newHost['player_id'],
                        ':room_id' => $room['room_id']
                    ]);
                } else {
                    // No online players available, remove room
                    removeRoomAndMatchmaking($pdo, $room['room_id'], $room['matchmaking_id']);
                }
            }
        }
    }

    // ============================================
    // 6. Hard delete very old inactive rooms
    // ============================================
    $stmt = $pdo->prepare("
        DELETE gr, rp, aq, pu
        FROM game_rooms gr
        LEFT JOIN room_players rp ON rp.room_id = gr.room_id
        LEFT JOIN action_queue aq ON aq.room_id = gr.room_id
        LEFT JOIN player_updates pu ON pu.room_id = gr.room_id
        WHERE gr.is_active = FALSE
          AND gr.updated_at < :hard_delete_threshold
    ");
    $stmt->execute([':hard_delete_threshold' => $hardDeleteThreshold->format('Y-m-d H:i:s')]);
    $hardDeletedRooms = $stmt->rowCount();

    $pdo->commit();

    // Log results
    error_log("cleanup.php completed successfully:");
    error_log("  - Game players marked offline: $gamePlayersOffline");
    error_log("  - Matchmaking players marked offline: $matchmakingPlayersOffline");
    error_log("  - Room players marked offline: $roomPlayersOffline");
    error_log("  - Matchmakings removed (no host switch): $matchmakingsRemovedNoSwitch");
    error_log("  - Matchmakings removed (no online players): $matchmakingsRemovedNoPlayers");
    error_log("  - Host switches performed: " . count($matchmakingsNeedingHostSwitch));
    error_log("  - Rooms hard deleted: $hardDeletedRooms");

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("cleanup.php CRITICAL ERROR: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    exit(1);
}

/**
 * Helper function to remove a room and its associated matchmaking
 */
function removeRoomAndMatchmaking($pdo, $roomId, $matchmakingId) {
    // Clean up realtime players for this room
    cleanupRealtimePlayersForRoom($pdo, $roomId);
    
    // Remove room and all associated data
    $stmt = $pdo->prepare("
        DELETE gr, rp, aq, pu
        FROM game_rooms gr
        LEFT JOIN room_players rp ON rp.room_id = gr.room_id
        LEFT JOIN action_queue aq ON aq.room_id = gr.room_id
        LEFT JOIN player_updates pu ON pu.room_id = gr.room_id
        WHERE gr.room_id = :room_id
    ");
    $stmt->execute([':room_id' => $roomId]);

    // Remove associated matchmaking if it exists
    if ($matchmakingId) {
        $stmt = $pdo->prepare("
            DELETE m, mp, mr
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON mp.matchmaking_id = m.matchmaking_id
            LEFT JOIN matchmaking_requests mr ON mr.matchmaking_id = m.matchmaking_id
            WHERE m.matchmaking_id = :matchmaking_id
        ");
        $stmt->execute([':matchmaking_id' => $matchmakingId]);
    }
}

/**
 * Helper function to clean up realtime players for a specific room
 */
function cleanupRealtimePlayersForRoom($pdo, $roomId) {
    // Get all realtime players in this room
    $stmt = $pdo->prepare("
        SELECT rp.connection_id, rp.token, gp.player_name
        FROM realtime_players rp
        JOIN game_players gp ON rp.game_player_id = gp.id
        WHERE rp.game_room_id = :room_id AND rp.is_connected = TRUE
    ");
    $stmt->execute([':room_id' => $roomId]);
    $connectedPlayers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Notify Node.js server to disconnect these players
    if (!empty($connectedPlayers)) {
        notifyRealtimeServerDisconnections($connectedPlayers, $roomId);
    }
    
    // Remove realtime players for this room
    $stmt = $pdo->prepare("
        DELETE FROM realtime_players 
        WHERE game_room_id = :room_id
    ");
    $stmt->execute([':room_id' => $roomId]);
    
    error_log("cleanup.php: Removed " . count($connectedPlayers) . " realtime players from room $roomId");
}

/**
 * Notify Node.js server about player disconnections using room-based disconnect
 */
function notifyRealtimeServerDisconnections($players, $roomId = null) {
    $serverUrl = 'https://realtime.michitai.com/disconnect'; // Updated endpoint with port
    
    if ($roomId) {
        // Use room-based disconnect for better efficiency
        $url = $serverUrl . '?room_id=' . urlencode($roomId);
        
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'GET',
                'timeout' => 5 // 5 second timeout
            ]
        ];
        
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);
        
        if ($result === false) {
            error_log("cleanup.php: Failed to notify realtime server about room disconnection for room $roomId");
        } else {
            $response = json_decode($result, true);
            $disconnectedCount = $response['disconnected_count'] ?? 0;
            error_log("cleanup.php: Successfully notified realtime server about room disconnection for room $roomId - $disconnectedCount players disconnected");
        }
    } else {
        // Fallback to individual player disconnections
        foreach ($players as $player) {
            $url = $serverUrl . '?player_token=' . urlencode($player['token']);
            
            $options = [
                'http' => [
                    'header'  => "Content-Type: application/json\r\n",
                    'method'  => 'GET',
                    'timeout' => 5 // 5 second timeout
                ]
            ];
            
            $context = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);
            
            if ($result === false) {
                error_log("cleanup.php: Failed to notify realtime server about disconnection for {$player['player_name']}");
            } else {
                error_log("cleanup.php: Successfully notified realtime server about disconnection for {$player['player_name']}");
            }
        }
    }
}

exit(0);
