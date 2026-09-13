<?php
// ====================== CORS & HEADERS ======================
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Enable error reporting & logging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Create logs directory if needed
if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

// Log the request
error_log("=== GAME ROOM REQUEST ===");
error_log("URI: " . $_SERVER['REQUEST_URI']);
error_log("Method: " . $_SERVER['REQUEST_METHOD']);

require_once __DIR__ . '/../php/config.php';

// ====================== FORMAT HANDLING ======================
$format = strtolower($_GET['format'] ?? 'json');
$isUnity = ($format === 'unity');
// ============================================================

// Helper function to send JSON response
function sendResponse($data, $statusCode = 200) {
    global $isUnity;
    if ($isUnity) {
        $data = formatForUnity($data);
    }
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ====================== HELPER FUNCTIONS ======================
function getAuthContext() {
    $apiToken = $_GET['api_token'];

    if (empty($apiToken)) {
        sendResponse(['success' => false, 'error' => 'API token is required'], 401);
    }

    global $pdo;
    $stmt = $pdo->prepare("SELECT id, user_id FROM api_keys WHERE api_key = ?");
    $stmt->execute([$apiToken]);
    $api = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$api) {
        sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
    }

    $player = null;
    $playerToken = $_GET['player_token'] ?? '';

    if ($playerToken !== '') {
        $stmt = $pdo->prepare("SELECT * FROM game_players WHERE private_key = ?");
        $stmt->execute([$playerToken]);
        $player = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$player) {
            sendResponse(['success' => false, 'error' => 'Invalid player token'], 401);
        }
    }

    return ['api' => $api, 'player' => $player];
}

function requirePlayer($context) {
    if (!$context['player']) {
        sendResponse(['success' => false, 'error' => 'Player token is required'], 401);
    }
    
    // Check if player is banned
    $banCheck = checkPlayerBan($context['player']['id'], $context['api']['id']);
    if ($banCheck['is_banned']) {
        sendResponse([
            'success' => false,
            'error' => $banCheck['message'],
            'ban_info' => [
                'ban_id' => $banCheck['ban_id'],
                'ban_duration' => $banCheck['ban_duration'],
                'ban_reason' => $banCheck['ban_reason'],
                'banned_at' => $banCheck['banned_at'],
                'banned_until' => $banCheck['banned_until']
            ]
        ], 403);
    }
    
    return $context['player'];
}

function getPlayerRoom($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT room_id FROM room_players WHERE player_id = ? AND is_online = TRUE LIMIT 1");
    $stmt->execute([$playerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['room_id'] : null;
}

function addPlayerToRoom($roomId, $playerId, $playerName, $gameId, $isHost = false, $playerDataJson = '{}') {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO room_players 
            (player_id, room_id, game_id, player_name, is_host, last_heartbeat, joined_at, is_online, player_data) 
        VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, TRUE, ?)
        ON DUPLICATE KEY UPDATE 
            room_id = VALUES(room_id),
            game_id = VALUES(game_id),
            player_name = VALUES(player_name),
            is_host = VALUES(is_host),
            last_heartbeat = CURRENT_TIMESTAMP,
            joined_at = CURRENT_TIMESTAMP,
            is_online = TRUE,
            player_data = VALUES(player_data)
    ");
    $stmt->execute([$playerId, $roomId, $gameId, $playerName, $isHost ? 1 : 0, $playerDataJson]);
}

function isHost($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT 1 FROM room_players WHERE player_id = ? AND is_host = TRUE LIMIT 1");
    $stmt->execute([$playerId]);
    return (bool)$stmt->fetchColumn();
}

// ====================== UNITY HELPER (Recursive + Robust) ======================
function formatForUnity($data) {
    if (!is_array($data)) {
        return $data;
    }

    $result = [];
    foreach ($data as $key => $value) {
        if (in_array($key, ['request_data', 'response_data', 'player_data', 'rules', 'data'])) {
            $jsonKey = $key . '_json';
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                $result[$jsonKey] = (json_last_error() === JSON_ERROR_NONE)
                    ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    : $value;
            } else {
                $result[$jsonKey] = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        } else {
            // Recurse into nested structures
            $result[$key] = (is_array($value) || is_object($value)) ? formatForUnity($value) : $value;
        }
    }
    return $result;
}

// ====================== ENDPOINTS ======================

function createRoom() {
    global $isUnity;
    
    $context = getAuthContext();
    $player = requirePlayer($context);

    $existingRoom = getPlayerRoom($player['id']);
    if ($existingRoom) {
        sendResponse(['success' => false, 'error' => 'You are already in a game room. Leave current room first.'], 400);
    }

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT mp.matchmaking_id
        FROM matchmaking_players mp
        JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
        WHERE mp.player_id = ? AND mp.is_online = TRUE
        LIMIT 1
    ");
    $stmt->execute([$player['id']]);
    if ($stmt->fetchColumn()) {
        sendResponse(['success' => false, 'error' => 'You cannot create a game room while in a matchmaking lobby.'], 400);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $roomId = bin2hex(random_bytes(16));

    if(isset($data['room_name']) && !empty($data['room_name'])) {
        $roomName = $data['room_name'];
    } else {
        $roomName = 'Game Room ' . substr($roomId, 0, 6);
    }

    $roomName = mb_substr($roomName, 0, 120);

    $password = isset($data['password']) && !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;
    $maxPlayers = max(2, min(16, (int)($data['max_players'] ?? 6)));
    $hostSwitch = (bool) ($data['host_switch'] ?? false);
    $realtime = (bool) ($data['realtime'] ?? false);

    if($isUnity)
    {
        $rules = $data['rules_json'] ?? null;
    }
    else
    {
        $rules = $data['rules'] ?? null;
    }

    // Handle player_data
    if($isUnity)
    {
        $playerData = $data['player_data_json'] ?? null;
    }
    else
    {
        $playerData = $data['player_data'] ?? null;
    }

    // Normalize player_data to JSON string
    if (is_string($playerData)) {
        $playerDataJson = $playerData !== '' ? $playerData : '{}';
    } else {
        $playerDataJson = isset($playerData) ? json_encode($playerData, JSON_UNESCAPED_UNICODE) : '{}';
    }

    // Normalize rules to JSON string
    if (is_string($rules)) {
        $rulesJson = $rules !== '' ? $rules : '{}';
    } else {
        $rulesJson = isset($rules) ? json_encode($rules, JSON_UNESCAPED_UNICODE) : '{}';
    }

    try {
        $pdo->beginTransaction();

        $pdo->prepare("INSERT INTO game_rooms (room_id, game_id, room_name, password, max_players, host_switch, can_leave, realtime, rules) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([$roomId, $context['api']['id'], $roomName, $password, $maxPlayers, $hostSwitch, true, $realtime, $rulesJson]);

        addPlayerToRoom($roomId, $player['id'], $player['player_name'], $context['api']['id'], true, $playerDataJson);

        $pdo->prepare("UPDATE game_rooms SET host_player_id = ? WHERE room_id = ?")
            ->execute([$player['id'], $roomId]);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'room_id' => $roomId,
            'room_name' => $roomName,
            'realtime' => $realtime,
            'is_host' => true
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Create room failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to create room'], 500);
    }
}

function listRooms() {
    global $isUnity;

    getAuthContext();

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    global $pdo;
    try {
        $search = $data['search'] ?? '';
        $limit = isset($data['limit']) ? max(1, min(50, (int)$data['limit'])) : null;

        $sql = "
            SELECT r.room_id, r.room_name, r.max_players, 
                   COUNT(rp.player_id) as current_players,
                   r.password IS NOT NULL as has_password,
                   r.host_switch, r.can_leave, r.realtime, r.rules
            FROM game_rooms r
            LEFT JOIN room_players rp ON r.room_id = rp.room_id
            WHERE r.is_active = TRUE
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND r.room_name LIKE ?";
            $params[] = "%$search%";
        }

        $sql .= "
            GROUP BY r.room_id
            HAVING current_players < r.max_players
            ORDER BY current_players DESC, r.room_name ASC
        ";

        if ($limit !== null) {
            $sql .= " LIMIT ?";
            $params[] = $limit;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rooms as &$room) {
            $room['has_password'] = (bool)$room['has_password'];
            $room['host_switch'] = (bool)$room['host_switch'];
            $room['can_leave'] = (bool)$room['can_leave'];
            $room['realtime'] = (bool)$room['realtime'];

            if($isUnity)
            {
                $decoded = json_decode($room['rules']);

                $room['rules'] = (json_last_error() === JSON_ERROR_NONE && $decoded !== null)
                    ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                    : '{}';
            }
            else
            {
                $room['rules'] = (json_last_error() === JSON_ERROR_NONE)
                    ? json_decode($room['rules'])
                    : null;
            }
        }

        sendResponse(['success' => true, 'rooms' => $rooms]);
    } catch (Exception $e) {
        error_log("List rooms failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to list rooms'], 500);
    }
}

function joinRoom($roomId) {
    global $isUnity;
    
    $context = getAuthContext();
    $player = requirePlayer($context);

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    // Handle player_data
    if($isUnity)
    {
        $playerData = $data['player_data_json'] ?? null;
    }
    else
    {
        $playerData = $data['player_data'] ?? null;
    }

    // Normalize player_data to JSON string
    if (is_string($playerData)) {
        $playerDataJson = $playerData !== '' ? $playerData : '{}';
    } else {
        $playerDataJson = isset($playerData) ? json_encode($playerData, JSON_UNESCAPED_UNICODE) : '{}';
    }

    global $pdo;
    try {
        $pdo->beginTransaction();

        $currentRoom = getPlayerRoom($player['id']);
        if ($currentRoom && $currentRoom !== $roomId) {
            throw new Exception('You are already in another room');
        }

        $stmt = $pdo->prepare("
            SELECT game_id, password, max_players, is_active,
                   (SELECT COUNT(*) FROM room_players WHERE room_id = ?) as current_players
            FROM game_rooms WHERE room_id = ?
        ");
        $stmt->execute([$roomId, $roomId]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) throw new Exception('Room not found');
        if (!$room['is_active'] && $room['current_players'] > 0) throw new Exception('Room inactive');
        if ($room['current_players'] >= $room['max_players']) throw new Exception('Room is full');

        if ($room['password'] !== null) {
            if (!isset($data['password']) || !password_verify($data['password'], $room['password'])) {
                throw new Exception('Incorrect password');
            }
        }

        addPlayerToRoom($roomId, $player['id'], $player['player_name'], $room['game_id'], false, $playerDataJson);
        checkAndReassignHost($roomId);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'room_id' => $roomId,
            'message' => 'Successfully joined the room'
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Join room failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }
}

function listRoomPlayers() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'You are not in any room'], 400);
    }

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT rp.player_id, rp.player_name, rp.is_host, rp.is_online, rp.last_heartbeat, rp.player_data
        FROM room_players rp
        WHERE rp.room_id = ?
        ORDER BY rp.is_host DESC, rp.joined_at ASC, rp.player_name ASC
    ");
    $stmt->execute([$roomId]);
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $context = getAuthContext();
    $player = requirePlayer($context);

    foreach ($players as &$playerData) {
        $playerData['player_id'] = (int)$playerData['player_id'];
        $playerData['is_host']   = (bool)$playerData['is_host'];
        $playerData['is_online'] = (bool)$playerData['is_online'];
        $playerData['is_local']  = ($playerData['player_id'] === $player['id']);

        $playerData['last_heartbeat'] = isoUtc($playerData['last_heartbeat']);
        
        // Handle player_data formatting for Unity
        if($isUnity)
        {
            $decoded = json_decode($playerData['player_data']);
            $playerData['player_data_json'] = (json_last_error() === JSON_ERROR_NONE && $decoded !== null)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : '{}';
            unset($playerData['player_data']);
        }
        else
        {
            $decoded = json_decode($playerData['player_data']);
            $playerData['player_data'] = (json_last_error() === JSON_ERROR_NONE)
                ? $decoded
                : null;
        }
    }

    sendResponse(['success' => true, 'players' => $players, 'last_updated' => isoUtc(date('c'))]);
}

function leaveRoom() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    global $pdo;
    try {
        $pdo->beginTransaction();

        $roomId = getPlayerRoom($player['id']);
        if (!$roomId) throw new Exception('You are not in any room');

        $stmt = $pdo->prepare("SELECT is_host FROM room_players WHERE player_id = ? AND room_id = ?");
        $stmt->execute([$player['id'], $roomId]);
        $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$playerData) throw new Exception('You are not in this room');

        $isHost = (bool)$playerData['is_host'];

        // Check if players can leave this room
        $stmt = $pdo->prepare("SELECT can_leave, matchmaking_id FROM game_rooms WHERE room_id = ?");
        $stmt->execute([$roomId]);
        $roomData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$roomData) throw new Exception('Room not found');

        $canLeave = (bool) ($roomData['can_leave'] ?? true);
        $matchmakingId = $roomData['matchmaking_id'];

        if (!$isHost && !$canLeave) {
            sendResponse(['success' => false, 'error' => 'Players are not allowed to leave this room'], 403);
        }

        $pdo->prepare("DELETE FROM room_players WHERE player_id = ? AND room_id = ?")
            ->execute([$player['id'], $roomId]);

        // Clean up realtime player for this individual player
        cleanupRealtimePlayer($pdo, $player['id']);

        // If this room was created from matchmaking, also leave the matchmaking
        if ($matchmakingId) {
            $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ? AND player_id = ?")
                ->execute([$matchmakingId, $player['id']]);
        }

        if ($isHost) {
            // Get host_switch setting for this room
            $stmt = $pdo->prepare("SELECT host_switch FROM game_rooms WHERE room_id = ?");
            $stmt->execute([$roomId]);
            $roomData = $stmt->fetch(PDO::FETCH_ASSOC);
            $hostSwitch = (bool) ($roomData['host_switch'] ?? false);

            if ($hostSwitch === false) {
                // If host_switch is false, delete the entire room
                $stmt = $pdo->prepare("SELECT matchmaking_id FROM game_rooms WHERE room_id = ?");
                $stmt->execute([$roomId]);
                $matchmakingId = $stmt->fetchColumn();

                if ($matchmakingId) {
                    $pdo->prepare("DELETE FROM matchmaking_requests WHERE matchmaking_id = ?")->execute([$matchmakingId]);
                    $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ?")->execute([$matchmakingId]);
                    $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);
                }

                // Clean up realtime players for this room
                cleanupRealtimePlayersForRoom($pdo, $roomId);
                
                $pdo->prepare("DELETE FROM action_queue WHERE room_id = ?")->execute([$roomId]);
                $pdo->prepare("DELETE FROM player_updates WHERE room_id = ?")->execute([$roomId]);
                $pdo->prepare("DELETE FROM game_rooms WHERE room_id = ?")->execute([$roomId]);
            } else {
                // If host_switch is true, use checkAndReassignHost to handle host transfer
                checkAndReassignHost($roomId);
            }
        } else {
            // If not host, still check if room needs cleanup
            checkAndReassignHost($roomId);
        }

        $pdo->commit();
        sendResponse(['success' => true, 'message' => 'Successfully left the room']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Leave room failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }
}

function updateRoomPassword() {
    global $pdo;
    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'You are not in any room'], 400);
    }

    // Check if player is host
    $stmt = $pdo->prepare("SELECT is_host FROM room_players WHERE player_id = ? AND room_id = ?");
    $stmt->execute([$player['id'], $roomId]);
    $playerData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$playerData) {
        sendResponse(['success' => false, 'error' => 'You are not in this room'], 400);
    }

    if (!(bool)$playerData['is_host']) {
        sendResponse(['success' => false, 'error' => 'Only host can update room password'], 403);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $password = isset($data['password']) && !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;

    try {
        $stmt = $pdo->prepare("UPDATE game_rooms SET password = ? WHERE room_id = ?");
        $stmt->execute([$password, $roomId]);

        sendResponse([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    } catch (Exception $e) {
        error_log("Update room password failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to update password'], 500);
    }
}

function checkAndReassignHost($roomId) {
    global $pdo;

    // Get host_switch setting for this room
    $stmt = $pdo->prepare("SELECT host_switch FROM game_rooms WHERE room_id = ?");
    $stmt->execute([$roomId]);
    $roomData = $stmt->fetch(PDO::FETCH_ASSOC);
    $hostSwitch = (bool) ($roomData['host_switch'] ?? false);

    $stmt = $pdo->prepare("
        SELECT player_id, is_online, last_heartbeat,
               TIMESTAMPDIFF(SECOND, last_heartbeat, NOW()) as seconds_since_heartbeat
        FROM room_players
        WHERE room_id = ? AND is_host = TRUE
        LIMIT 1
    ");
    $stmt->execute([$roomId]);
    $currentHost = $stmt->fetch(PDO::FETCH_ASSOC);

    // Host is offline if missing or marked offline
    $hostOffline = !$currentHost || !$currentHost['is_online'];

    if ($hostOffline) {
        if ($currentHost && $hostSwitch === true) {
            // Only remove host status if host_switch is true (allows host transfer)
            $pdo->prepare("
                UPDATE room_players 
                SET is_host = FALSE 
                WHERE player_id = ? AND room_id = ?
            ")->execute([$currentHost['player_id'], $roomId]);
        }

        if ($hostSwitch === true) {
            // Find next available online player (oldest joined first)
            $stmt = $pdo->prepare("
                SELECT player_id 
                FROM room_players 
                WHERE room_id = ? 
                  AND is_online = TRUE
                ORDER BY joined_at ASC
                LIMIT 1
            ");
            $stmt->execute([$roomId]);
            $newHost = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($newHost) {
                // Assign new host and reactivate room if needed
                $pdo->prepare("
                    UPDATE room_players 
                    SET is_host = TRUE
                    WHERE player_id = ? AND room_id = ?
                ")->execute([$newHost['player_id'], $roomId]);

                $pdo->prepare("
                    UPDATE game_rooms 
                    SET host_player_id = ?,
                        is_active = TRUE
                    WHERE room_id = ?
                ")->execute([$newHost['player_id'], $roomId]);
            } else {
                // No players left -> clean up and deactivate
                $pdo->prepare("DELETE FROM action_queue WHERE room_id = ?")->execute([$roomId]);
                $pdo->prepare("UPDATE game_rooms SET is_active = FALSE WHERE room_id = ?")->execute([$roomId]);
            }
        } else {
            // If host_switch is false and host left, delete the entire room
            $stmt = $pdo->prepare("SELECT matchmaking_id FROM game_rooms WHERE room_id = ?");
            $stmt->execute([$roomId]);
            $matchmakingId = $stmt->fetchColumn();

            if ($matchmakingId) {
                $pdo->prepare("DELETE FROM matchmaking_requests WHERE matchmaking_id = ?")->execute([$matchmakingId]);
                $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ?")->execute([$matchmakingId]);
                $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);
            }

            // Clean up realtime players for this room
            cleanupRealtimePlayersForRoom($pdo, $roomId);
            
            $pdo->prepare("DELETE FROM action_queue WHERE room_id = ?")->execute([$roomId]);
            $pdo->prepare("DELETE FROM player_updates WHERE room_id = ?")->execute([$roomId]);
            $pdo->prepare("DELETE FROM game_rooms WHERE room_id = ?")->execute([$roomId]);
        }
    }
}

function updateHeartbeat() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);
    }

    global $pdo;
    $pdo->beginTransaction();
    try {
        // Update this player's heartbeat + online status
        $pdo->prepare("
            UPDATE room_players 
            SET last_heartbeat = CURRENT_TIMESTAMP, 
                is_online = TRUE 
            WHERE player_id = ? AND room_id = ?
        ")->execute([$player['id'], $roomId]);

        // Also update global game_players table
        $pdo->prepare("
            UPDATE game_players 
            SET last_heartbeat = CURRENT_TIMESTAMP 
            WHERE id = ?
        ")->execute([$player['id']]);

        checkAndReassignHost($roomId);

        $pdo->commit();
        sendResponse(['success' => true, 'status' => 'ok']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Heartbeat update failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to update heartbeat'], 500);
    }
}

function submitAction() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if (!isset($data['action_type']) || empty($data['action_type'])) {
        sendResponse(['success' => false, 'error' => 'Missing action_type'], 400);
    }

    $actionType = $data['action_type'];

    if (!isset($data['target_players']) || empty($data['target_players'])) {
        sendResponse(['success' => false, 'error' => 'Missing required field: target_players'], 400);
    }

    $targetPlayers = $data['target_players'];

    if ($targetPlayers === 'specific') {
        if(!isset($data['target_players_ids']) || empty($data['target_players_ids'])) {
            sendResponse(['success' => false, 'error' => 'Missing required field: target_players_ids'], 400);
        }
        $targetPlayersIds = $data['target_players_ids'];
    }

    $requestData = null;

    if($isUnity)
    {
        if (isset($data['request_data_json']) && !empty($data['request_data_json'])) {
            if(!is_string($data['request_data_json']))
            {
                sendResponse(['success' => false, 'error' => 'request_data_json must be a string'], 400);
            }

            $requestData = json_decode($data['request_data_json'], true);
        }
    }
    else
    {
        if (isset($data['request_data']) && !empty($data['request_data'])) {
            $requestData = $data['request_data'];
        }
    }

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);
    }

    // Normalize request_data to JSON string
    if (is_string($requestData)) {
        $requestDataJson = $requestData !== '' ? $requestData : '{}';
    } else {
        $requestDataJson = isset($requestData) ? json_encode($requestData, JSON_UNESCAPED_UNICODE) : '{}';
    }

    $targets = [];

    global $pdo;

    if ($targetPlayers === 'all') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND is_online = TRUE
        ");
        $stmt->execute([$roomId]);
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else if ($targetPlayers === 'host') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND is_host = TRUE AND is_online = TRUE
        ");
        $stmt->execute([$roomId]);
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else if ($targetPlayers === 'others') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND player_id != ? AND is_online = TRUE
        ");
        $stmt->execute([$roomId, $player['id']]);
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } elseif ($targetPlayers === 'specific') {
        if(is_array($targetPlayersIds) && count($targetPlayersIds) > 0) {
            $placeholders = implode(',', array_fill(0, count($targetPlayersIds), '?'));
            $stmt = $pdo->prepare("
                SELECT player_id 
                FROM room_players 
                WHERE room_id = ? AND player_id IN ($placeholders) AND is_online = TRUE
            ");
            $params = array_merge([$roomId], $targetPlayersIds);
            $stmt->execute($params);
            $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        else
        {
            sendResponse(['success' => false, 'error' => 'Invalid target players ids'], 400);
        }

        if (empty($targets)) {
            sendResponse(['success' => false, 'error' => 'No valid target players found'], 400);
        }
    }
    else
    {
        sendResponse(['success' => false, 'error' => 'Invalid target players'], 400);
    }

    $actionIds = [];
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            INSERT INTO action_queue 
            (action_id, room_id, game_id, player_id, target_id, action_type, request_data, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
        ");

        foreach ($targets as $targetPlayerId) {
            $actionId = bin2hex(random_bytes(16));
            $stmt->execute([$actionId, $roomId, $context['api']['id'], $player['id'], $targetPlayerId, $actionType, $requestDataJson]);
            $actionIds[] = $actionId;
        }

        $pdo->commit();

        $targets = array_map('intval', $targets);

        sendResponse([
            'success' => true,
            'actions_sent' => count($actionIds),
            'action_ids' => $actionIds,
            'target_players_ids' => $targets
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Submit actions failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to submit actions'], 500);
    }
}

function pollActions() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT action_id, action_type, response_data, status, target_id, processed_at
        FROM action_queue
        WHERE player_id = ? AND status IN ('completed', 'failed')
        AND processed_at > NOW() - INTERVAL 1 HOUR
        ORDER BY processed_at DESC LIMIT 50
    ");
    $stmt->execute([$player['id']]);
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($actions as &$action) {
        // Determine if this action was sent to a host player
        $action['is_host'] = false;
        if ($action['target_id']) {
            $stmt = $pdo->prepare("SELECT is_host FROM room_players WHERE player_id = ? AND room_id = ?");
            $stmt->execute([$action['target_id'], $roomId]);
            $action['is_host'] = (bool)$stmt->fetchColumn();
        }

        if($isUnity)
        {
            $decoded = json_decode($action['response_data']);

            $action['response_data_json'] = (json_last_error() === JSON_ERROR_NONE && $decoded !== null)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : '{}';

            unset($action['response_data']);
        }
        else
        {
            $action['response_data'] = (json_last_error() === JSON_ERROR_NONE)
                ? json_decode($action['response_data'])
                : null;
        }

        $action['processed_at'] = isoUtc($action['processed_at']);
    }

    sendResponse(['success' => true, 'actions' => $actions]);
}

function getPendingActions() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) sendResponse(['success' => false, 'error' => 'You are not in any room'], 400);

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT a.action_id, a.player_id, a.target_id, a.action_type, a.request_data, a.created_at, rp.player_name
        FROM action_queue a
        JOIN room_players rp ON a.player_id = rp.player_id
        WHERE a.room_id = ? AND a.status = 'pending' AND a.target_id = ?
        ORDER BY a.created_at ASC
    ");
    $stmt->execute([$roomId, $player['id']]);
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($actions as &$action) {
        $action['player_id'] = (int)$action['player_id'];
        $action['target_id'] = (int)$action['target_id'];
        
        // Determine if this action was sent from a host player
        $stmt = $pdo->prepare("SELECT is_host FROM room_players WHERE player_id = ? AND room_id = ?");
        $stmt->execute([$action['player_id'], $roomId]);
        $action['is_host'] = (bool)$stmt->fetchColumn();

        if($isUnity)
        {
            $decoded = json_decode($action['request_data']);

            $action['request_data_json'] = (json_last_error() === JSON_ERROR_NONE && $decoded !== null)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : '{}';

            unset($action['request_data']);
        }
        else
        {
            $action['request_data'] = (json_last_error() === JSON_ERROR_NONE)
                ? json_decode($action['request_data'])
                : null;
        }

        $action['created_at'] = isoUtc($action['created_at']);
    }

    sendResponse(['success' => true, 'actions' => $actions]);
}

function completeAction($actionId) {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    if (!$player) {
        sendResponse(['success' => false, 'error' => 'Player not found'], 404);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if(!isset($data['status']) || empty($data['status']))
    {
        sendResponse(['success' => false, 'error' => 'Status is required'], 400);
    }

    $status = in_array($data['status'] ?? 'completed', ['[processing', 'completed', 'failed']) ? $data['status'] : 'processing';

    $responseData = null;

    if($isUnity)
    {
       if(isset($data['response_data_json']) && !empty($data['response_data_json']))
       {
           if(!is_string($data['response_data_json']))
           {
               sendResponse(['success' => false, 'error' => 'response_data_json must be a string'], 400);
           }

            $responseData = $data['response_data_json'];
       }
    }
    else
    {
        if(isset($data['response_data']) && !empty($data['response_data']))
        {
            $responseData = json_encode($data['response_data'], JSON_UNESCAPED_UNICODE);

            if(json_last_error() !== JSON_ERROR_NONE)
            {
                sendResponse(['success' => false, 'error' => 'response_data is not valid JSON'], 400);
            }
        }
    }

    

    global $pdo;
    $stmt = $pdo->prepare("
        UPDATE action_queue 
        SET status = ?, response_data = ?, processed_at = CURRENT_TIMESTAMP
        WHERE action_id = ? AND status = 'pending' AND target_id = ?
    ");
    $stmt->execute([$status, $responseData, $actionId, $player['id']]);

    if ($stmt->rowCount() === 0) {
        sendResponse(['success' => false, 'error' => 'Action not found or already processed'], 404);
    }

    sendResponse(['success' => true, 'message' => 'Action completed']);
}

// ====================== FIXED: sendUpdates() ======================
function sendUpdates() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if (!isset($data['target_players']) || empty($data['target_players'])) {
        sendResponse(['success' => false, 'error' => 'Missing required field: target_players'], 400);
    }

    $targetPlayers = $data['target_players'];

    if (!isset($data['type']) || empty($data['type'])) {
        sendResponse(['success' => false, 'error' => 'Missing required field: type'], 400);
    }

    $updateType = trim($data['type']);

    if ($targetPlayers === 'specific') {
        if(!isset($data['target_players_ids']) || empty($data['target_players_ids'])) {
            sendResponse(['success' => false, 'error' => 'Missing required field: target_players_ids'], 400);
        }

        $targetPlayersIds = $data['target_players_ids'];
    } 

    if($isUnity)
    {
        $data = $data['data_json'] ?? null;
    }
    else
    {
        $data = $data['data'] ?? null;
    }

    // Normalize rules to JSON string
    if (is_string($data)) {
        $dataJson = $data !== '' ? $data : '{}';
    } else {
        $dataJson = isset($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : '{}';
    }

    $targets = [];

    global $pdo;

    if ($targetPlayers === 'all') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND is_online = TRUE
        ");
        $stmt->execute([$roomId]);
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else if ($targetPlayers === 'host') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND is_host = TRUE AND is_online = TRUE
        ");
        $stmt->execute([$roomId]);
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else if ($targetPlayers === 'others') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND player_id != ? AND is_online = TRUE
        ");
        $stmt->execute([$roomId, $player['id']]);
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } elseif ($targetPlayers === 'specific') {
        if(is_array($targetPlayersIds) && count($targetPlayersIds) > 0) {
            $placeholders = implode(',', array_fill(0, count($targetPlayersIds), '?'));
            $stmt = $pdo->prepare("
                SELECT player_id 
                FROM room_players 
                WHERE room_id = ? AND player_id IN ($placeholders) AND is_online = TRUE
            ");
            $params = array_merge([$roomId], $targetPlayersIds);
            $stmt->execute($params);
            $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        else
        {
            sendResponse(['success' => false, 'error' => 'Invalid target players ids'], 400);
        }

        if (empty($targets)) {
            sendResponse(['success' => false, 'error' => 'No valid target players found'], 400);
        }
    }
    else
    {
        sendResponse(['success' => false, 'error' => 'Invalid target players'], 400);
    }

    $updateIds = [];
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            INSERT INTO player_updates 
            (update_id, room_id, game_id, from_player_id, target_player_id, type, data)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($targets as $targetPlayerId) {
            $updateId = bin2hex(random_bytes(16));
            $stmt->execute([$updateId, $roomId, $context['api']['id'], $player['id'], $targetPlayerId, $updateType, $dataJson]);
            $updateIds[] = $updateId;
        }

        $pdo->commit();

        $targets = array_map('intval', $targets);

        sendResponse([
            'success' => true,
            'updates_sent' => count($updateIds),
            'update_ids' => $updateIds,
            'target_players_ids' => $targets
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Send updates failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to send updates'], 500);
    }
}

function pollUpdates() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);

    // Read from_players and last_update from request body (for .NET compatibility)
    $requestData = json_decode(file_get_contents('php://input'), true) ?: [];
    $fromPlayers = $requestData['from_players'] ?? 'host'; // Default to host to match .NET implementation
    $fromPlayersIds = $requestData['from_players_ids'] ?? null;
    $lastUpdateId = $requestData['last_update'] ?? null;

    global $pdo;
    
    // Build source player list based on from_players parameter (filter by who sent updates)
    $sourcePlayers = [];
    
    if ($fromPlayers === 'all') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND is_online = TRUE
        ");
        $stmt->execute([$roomId]);
        $sourcePlayers = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else if ($fromPlayers === 'host') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND is_host = TRUE AND is_online = TRUE
        ");
        $stmt->execute([$roomId]);
        $sourcePlayers = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else if ($fromPlayers === 'others') {
        $stmt = $pdo->prepare("
            SELECT player_id 
            FROM room_players 
            WHERE room_id = ? AND is_host = FALSE AND is_online = TRUE
        ");
        $stmt->execute([$roomId]);
        $sourcePlayers = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } elseif ($fromPlayers === 'specific') {
        if(is_array($fromPlayersIds) && count($fromPlayersIds) > 0) {
            $placeholders = implode(',', array_fill(0, count($fromPlayersIds), '?'));
            $stmt = $pdo->prepare("
                SELECT player_id 
                FROM room_players 
                WHERE room_id = ? AND player_id IN ($placeholders) AND is_online = TRUE
            ");
            $params = array_merge([$roomId], $fromPlayersIds);
            $stmt->execute($params);
            $sourcePlayers = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        if (empty($sourcePlayers)) {
            sendResponse(['success' => false, 'error' => 'No valid source players found'], 400);
        }
    } else {
        sendResponse(['success' => false, 'error' => 'Invalid from players'], 400);
    }

    // Build WHERE clause for source players (who sent the updates) and current player as target
    if (count($sourcePlayers) === 1) {
        $whereClause = "WHERE from_player_id = ? AND target_player_id = ? AND room_id = ?";
        $params = [$sourcePlayers[0], $player['id'], $roomId];
    } else {
        $placeholders = implode(',', array_fill(0, count($sourcePlayers), '?'));
        $whereClause = "WHERE from_player_id IN ($placeholders) AND target_player_id = ? AND room_id = ?";
        $params = array_merge($sourcePlayers, [$player['id'], $roomId]);
    }

    if ($lastUpdateId) {
        $whereClause .= " AND update_id > ?";
        $params[] = $lastUpdateId;
    }

    $stmt = $pdo->prepare("
        SELECT update_id, from_player_id, type, data, created_at
        FROM player_updates $whereClause
        ORDER BY created_at ASC LIMIT 50
    ");
    $stmt->execute($params);
    $updates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($updates as &$update) {
        $update['from_player_id'] = (int)$update['from_player_id'];

        if($isUnity)
        {
            $decoded = json_decode($update['data']);

            $update['data_json'] = (json_last_error() === JSON_ERROR_NONE && $decoded !== null)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : '{}';

            unset($update['data']);
        }
        else
        {
            $update['data'] = (json_last_error() === JSON_ERROR_NONE)
                ? json_decode($update['data'])
                : null;
        }

        $update['created_at'] = isoUtc($update['created_at']);
    }

    sendResponse([
        'success' => true,
        'updates' => $updates,
        'last_update' => !empty($updates) ? end($updates)['update_id'] : $lastUpdateId
    ]);
}

function stopGameRoom() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'You are not in any game room'], 400);
    }

    // Check if player is the host
    if (!isHost($player['id'])) {
        sendResponse(['success' => false, 'error' => 'Only the host can stop the game room'], 403);
    }

    global $pdo;
    $pdo->beginTransaction();
    try {
        // Get matchmaking_id if this room was created from matchmaking
        $stmt = $pdo->prepare("SELECT matchmaking_id FROM game_rooms WHERE room_id = ?");
        $stmt->execute([$roomId]);
        $matchmakingId = $stmt->fetchColumn();

        if ($matchmakingId) {
            // Clean up matchmaking data
            $pdo->prepare("DELETE FROM matchmaking_requests WHERE matchmaking_id = ?")->execute([$matchmakingId]);
            $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ?")->execute([$matchmakingId]);
            $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);
        }

        // Clean up realtime players for this room
        cleanupRealtimePlayersForRoom($pdo, $roomId);
        
        // Delete all room-related data
        $pdo->prepare("DELETE FROM action_queue WHERE room_id = ?")->execute([$roomId]);
        $pdo->prepare("DELETE FROM player_updates WHERE room_id = ?")->execute([$roomId]);
        $pdo->prepare("DELETE FROM room_players WHERE room_id = ?")->execute([$roomId]);
        $pdo->prepare("DELETE FROM game_rooms WHERE room_id = ?")->execute([$roomId]);

        $pdo->commit();
        sendResponse(['success' => true, 'message' => 'Game room stopped successfully']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Stop game room failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to stop game room'], 500);
    }
}

function kickPlayer() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    
    if (!isset($data['player_id']) || empty($data['player_id'])) {
        sendResponse(['success' => false, 'error' => 'Missing required field: player_id'], 400);
    }

    $targetPlayerId = (int)$data['player_id'];

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'You are not in any game room'], 400);
    }

    // Check if player is the host
    if (!isHost($player['id'])) {
        sendResponse(['success' => false, 'error' => 'Only host can kick players'], 403);
    }

    if ($targetPlayerId === $player['id']) {
        sendResponse(['success' => false, 'error' => 'You cannot kick yourself'], 400);
    }

    global $pdo;
    $pdo->beginTransaction();
    try {
        // Check if target player is in the same room
        $stmt = $pdo->prepare("
            SELECT rp.player_id
            FROM room_players rp
            WHERE rp.room_id = ? AND rp.player_id = ? AND rp.is_online = TRUE
            LIMIT 1
        ");
        $stmt->execute([$roomId, $targetPlayerId]);
        $targetPlayer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$targetPlayer) {
            sendResponse(['success' => false, 'error' => 'Player not found in this room'], 404);
        }

        // Remove the player from the room
        $pdo->prepare("DELETE FROM room_players WHERE room_id = ? AND player_id = ?")
             ->execute([$roomId, $targetPlayerId]);

        // Clean up realtime player for the kicked player
        cleanupRealtimePlayer($pdo, $targetPlayerId);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'message' => 'Player kicked successfully',
            'kicked_player_id' => $targetPlayerId
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Kick player failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to kick player'], 500);
    }
}

function getCurrentGameRoomStatus() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    global $pdo;

    $stmt = $pdo->prepare("
        SELECT 
            rp.room_id, rp.player_id, rp.player_name, rp.is_host, rp.is_online, 
            rp.last_heartbeat, rp.joined_at,
            gr.room_name, gr.max_players, gr.password IS NOT NULL as has_password, 
            gr.host_switch, gr.can_leave, gr.realtime, gr.is_active, gr.rules, 
            gr.created_at as room_created_at,
            gr.updated_at, gr.last_activity as room_last_activity,
            COUNT(rp2.player_id) as current_players
        FROM room_players rp
        JOIN game_rooms gr ON rp.room_id = gr.room_id
        LEFT JOIN room_players rp2 ON gr.room_id = rp2.room_id
        WHERE rp.player_id = ? AND gr.is_active = TRUE
        GROUP BY gr.room_id 
        LIMIT 1
    ");
    $stmt->execute([$player['id']]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        sendResponse([
            'success' => true,
            'in_room' => false,
            'message' => 'Player is not in any game room'
        ]);
    }

    if($isUnity)
    {
        $decoded = json_decode($room['rules']);

        $rules = (json_last_error() === JSON_ERROR_NONE && $decoded !== null)
            ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : '{}';
    }
    else
    {
        $rules = (json_last_error() === JSON_ERROR_NONE)
            ? json_decode($room['rules'])
            : null;
    }

    $stmt = $pdo->prepare("
        SELECT action_id, action_type, status, created_at, processed_at
        FROM action_queue 
        WHERE player_id = ? AND status IN ('pending', 'processing')
        ORDER BY created_at DESC LIMIT 5
    ");
    $stmt->execute([$player['id']]);
    $pendingActions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("
        SELECT update_id, from_player_id, type, data, created_at, status
        FROM player_updates 
        WHERE target_player_id = ? AND status = 'pending'
        ORDER BY created_at DESC LIMIT 5
    ");
    $stmt->execute([$player['id']]);
    $pendingUpdates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        'success' => true,
        'in_room' => true,
        'room' => [
            'room_id'            => $room['room_id'],
            'room_name'          => $room['room_name'],
            'is_host'            => (bool)$room['is_host'],
            'is_online'          => (bool)$room['is_online'],
            'max_players'        => (int)$room['max_players'],
            'current_players'    => (int)$room['current_players'],
            'has_password'       => (bool)$room['has_password'],
            'host_switch'        => (bool)$room['host_switch'],
            'can_leave'          => (bool)$room['can_leave'],
            'realtime'           => (bool)$room['realtime'],
            'is_active'          => (bool)$room['is_active'],
            'rules'              => $rules,
            'player_name'        => $room['player_name'],
            'joined_at'          => isoUtc($room['joined_at']),
            'last_heartbeat'     => isoUtc($room['last_heartbeat']),
            'room_created_at'    => isoUtc($room['room_created_at']),
            'room_last_activity' => isoUtc($room['room_last_activity'])
        ],
        'pending_actions' => $pendingActions,
        'pending_updates' => $pendingUpdates
    ];

    sendResponse($response);
}

// ====================== ROUTING ======================
try {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST' && preg_match('#/create/?$#', $path)) {
        createRoom();
    } elseif ($method === 'POST' && preg_match('#/list/?$#', $path)) {
        listRooms();
    } elseif ($method === 'POST' && preg_match('#/([a-f0-9-]{32,36})/join/?$#', $path, $m)) {
        joinRoom($m[1]);
    } elseif ($method === 'GET' && preg_match('#/players/?$#', $path)) {
        listRoomPlayers();
    } elseif ($method === 'POST' && preg_match('#/leave/?$#', $path)) {
        leaveRoom();
    } elseif ($method === 'POST' && preg_match('#/heartbeat/?$#', $path)) {
        updateHeartbeat();
    } elseif ($method === 'POST' && preg_match('#/actions/?$#', $path)) {
        submitAction();
    } elseif ($method === 'GET' && preg_match('#/actions/poll/?$#', $path)) {
        pollActions();
    } elseif ($method === 'GET' && preg_match('#/actions/pending/?$#', $path)) {
        getPendingActions();
    } elseif ($method === 'POST' && preg_match('#/actions/([a-f0-9-]{32,36})/complete/?$#', $path, $m)) {
        completeAction($m[1]);
    } elseif ($method === 'POST' && preg_match('#/updates/?$#', $path)) {
        sendUpdates();
    } elseif ($method === 'POST' && preg_match('#/updates/poll/?$#', $path)) {
        pollUpdates();
    } elseif ($method === 'GET' && preg_match('#/current/?$#', $path)) {
        getCurrentGameRoomStatus();
    } elseif ($method === 'POST' && preg_match('#/stop/?$#', $path)) {
        stopGameRoom();
    } elseif ($method === 'POST' && preg_match('#/kick/?$#', $path)) {
        kickPlayer();
    } elseif ($method === 'POST' && preg_match('#/password/?$#', $path)) {
        updateRoomPassword();
    } else {
        sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
    }
} catch (Exception $e) {
    error_log("Critical error in game_room.php: " . $e->getMessage());
    sendResponse(['success' => false, 'error' => 'Internal server error'], 500);
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
    
    error_log("game_room.php: Removed " . count($connectedPlayers) . " realtime players from room $roomId");
}

/**
 * Helper function to clean up a single realtime player
 */
function cleanupRealtimePlayer($pdo, $playerId) {
    // Get realtime player info
    $stmt = $pdo->prepare("
        SELECT rp.connection_id, rp.token, gp.player_name
        FROM realtime_players rp
        JOIN game_players gp ON rp.game_player_id = gp.id
        WHERE rp.game_player_id = :player_id AND rp.is_connected = TRUE
    ");
    $stmt->execute([':player_id' => $playerId]);
    $player = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($player) {
        // Notify Node.js server to disconnect this player
        notifyRealtimeServerDisconnections([$player]);
        
        // Remove realtime player
        $stmt = $pdo->prepare("
            DELETE FROM realtime_players 
            WHERE game_player_id = :player_id
        ");
        $stmt->execute([':player_id' => $playerId]);
        
        error_log("game_room.php: Removed realtime player {$player['player_name']} (ID: $playerId)");
    }
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
            error_log("game_room.php: Failed to notify realtime server about room disconnection for room $roomId");
        } else {
            $response = json_decode($result, true);
            $disconnectedCount = $response['disconnected_count'] ?? 0;
            error_log("game_room.php: Successfully notified realtime server about room disconnection for room $roomId - $disconnectedCount players disconnected");
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
                error_log("game_room.php: Failed to notify realtime server about disconnection for {$player['player_name']}");
            } else {
                error_log("game_room.php: Successfully notified realtime server about disconnection for {$player['player_name']}");
            }
        }
    }
}
?>