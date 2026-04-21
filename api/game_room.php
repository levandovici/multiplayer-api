<?php
// ====================== CORS & HEADERS ======================
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Player-Token, X-Game-Player-Token');
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
    $headers = getallheaders();
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
    $playerToken = $headers['X-Player-Token'] ?? $_GET['player_token'] ?? '';

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

        $pdo->prepare("INSERT INTO game_rooms (room_id, game_id, room_name, password, max_players, host_switch, can_leave, rules) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")
            ->execute([$roomId, $context['api']['id'], $roomName, $password, $maxPlayers, $hostSwitch, true, $rulesJson]);

        addPlayerToRoom($roomId, $player['id'], $player['player_name'], $context['api']['id'], true, $playerDataJson);

        $pdo->prepare("UPDATE game_rooms SET host_player_id = ? WHERE room_id = ?")
            ->execute([$player['id'], $roomId]);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'room_id' => $roomId,
            'room_name' => $roomName,
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

    global $pdo;
    try {
        $stmt = $pdo->query("
            SELECT r.room_id, r.room_name, r.max_players, 
                   COUNT(rp.player_id) as current_players,
                   r.password IS NOT NULL as has_password,
                   r.host_switch, r.can_leave, r.rules
            FROM game_rooms r
            LEFT JOIN room_players rp ON r.room_id = rp.room_id
            WHERE r.is_active = TRUE
            GROUP BY r.room_id
            HAVING current_players < r.max_players
            ORDER BY current_players DESC, r.room_name ASC
        ");

        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($rooms as &$room) {
            $room['has_password'] = (bool)$room['has_password'];
            $room['host_switch'] = (bool)$room['host_switch'];
            $room['can_leave'] = (bool)$room['can_leave'];

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

    $actionId = bin2hex(random_bytes(16));

    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO action_queue (action_id, room_id, game_id, player_id, action_type, request_data, status)
        VALUES (?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([$actionId, $roomId, $context['api']['id'], $player['id'], $actionType, json_encode($requestData, JSON_UNESCAPED_UNICODE)]);

    sendResponse([
        'success' => true,
        'action_id' => $actionId,
        'status' => 'pending'
    ]);
}

function pollActions() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT action_id, action_type, response_data, status
        FROM action_queue
        WHERE player_id = ? AND status IN ('completed', 'failed')
        AND processed_at > NOW() - INTERVAL 1 HOUR
        ORDER BY processed_at DESC LIMIT 50
    ");
    $stmt->execute([$player['id']]);
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($actions as &$action) {
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

    if (!isHost($player['id'])) {
        sendResponse(['success' => false, 'error' => 'Only host can view pending actions'], 403);
    }

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) sendResponse(['success' => false, 'error' => 'You are not in any room'], 400);

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT a.action_id, a.player_id, a.action_type, a.request_data, a.created_at, rp.player_name
        FROM action_queue a
        JOIN room_players rp ON a.player_id = rp.player_id
        WHERE a.room_id = ? AND a.status = 'pending'
        ORDER BY a.created_at ASC
    ");
    $stmt->execute([$roomId]);
    $actions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($actions as &$action) {
        $action['player_id'] = (int)$action['player_id'];

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

    if (!isHost($player['id'])) {
        sendResponse(['success' => false, 'error' => 'Only host can complete actions'], 403);
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
        WHERE action_id = ? AND status = 'pending'
    ");
    $stmt->execute([$status, $responseData, $actionId]);

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

    if (!isHost($player['id'])) {
        sendResponse(['success' => false, 'error' => 'Only host can send updates'], 403);
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

    $lastUpdateId = $_GET['last_update'] ?? null;

    global $pdo;
    $whereClause = "WHERE target_player_id = ? AND room_id = ?";
    $params = [$player['id'], $roomId];

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
            gr.host_switch, gr.can_leave, gr.is_active, gr.rules, 
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
    } elseif ($method === 'GET' && preg_match('#/list/?$#', $path)) {
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
    } elseif ($method === 'GET' && preg_match('#/updates/poll/?$#', $path)) {
        pollUpdates();
    } elseif ($method === 'GET' && preg_match('#/current/?$#', $path)) {
        getCurrentGameRoomStatus();
    } else {
        sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
    }
} catch (Exception $e) {
    error_log("Critical error in game_room.php: " . $e->getMessage());
    sendResponse(['success' => false, 'error' => 'Internal server error'], 500);
}
?>