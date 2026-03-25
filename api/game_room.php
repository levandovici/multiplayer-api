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
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ====================== HELPER FUNCTIONS ======================
function getAuthContext() {
    $headers = getallheaders();
    $apiToken = '';

    if (isset($headers['Authorization']) && preg_match('/Bearer\s+(\S+)/', $headers['Authorization'], $m)) {
        $apiToken = $m[1];
    } elseif (isset($_GET['api_token'])) {
        $apiToken = $_GET['api_token'];
    }

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

function addPlayerToRoom($roomId, $playerId, $playerName, $gameId, $isHost = false) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO room_players 
            (player_id, room_id, game_id, player_name, is_host, last_heartbeat, joined_at, is_online) 
        VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, TRUE)
        ON DUPLICATE KEY UPDATE 
            room_id = VALUES(room_id),
            game_id = VALUES(game_id),
            player_name = VALUES(player_name),
            is_host = VALUES(is_host),
            last_heartbeat = CURRENT_TIMESTAMP,
            joined_at = CURRENT_TIMESTAMP,
            is_online = TRUE
    ");
    $stmt->execute([$playerId, $roomId, $gameId, $playerName, $isHost ? 1 : 0]);
}

function isHost($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT 1 FROM room_players WHERE player_id = ? AND is_host = TRUE LIMIT 1");
    $stmt->execute([$playerId]);
    return (bool)$stmt->fetchColumn();
}

// ====================== UNITY HELPER ======================
function formatForUnity($data) {
    global $isUnity;
    if (!$isUnity) return $data;

    // Convert complex fields to _json strings for Unity JsonUtility compatibility
    if (isset($data['response_data']) && is_string($data['response_data'])) {
        $decoded = json_decode($data['response_data'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['response_data_json'] = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            unset($data['response_data']);
        }
    }
    if (isset($data['request_data']) && is_string($data['request_data'])) {
        $decoded = json_decode($data['request_data'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['request_data_json'] = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            unset($data['request_data']);
        }
    }
    if (isset($data['data_json']) && is_string($data['data_json'])) {
        $decoded = json_decode($data['data_json'], true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $data['data_json'] = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }
    return $data;
}

// ====================== ENDPOINTS ======================

function createRoom() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $existingRoom = getPlayerRoom($player['id']);
    if ($existingRoom) {
        sendResponse(['success' => false, 'error' => 'You are already in a game room. Leave current room first.'], 400);
    }

    // Check matchmaking
    global $pdo;
    $stmt = $pdo->prepare("SELECT mp.matchmaking_id FROM matchmaking_players mp JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id WHERE mp.player_id = ? AND mp.status = 'active' AND m.is_started = FALSE LIMIT 1");
    $stmt->execute([$player['id']]);
    if ($stmt->fetchColumn()) {
        sendResponse(['success' => false, 'error' => 'You cannot create a game room while in a matchmaking lobby.'], 400);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $roomId = bin2hex(random_bytes(16));
    $roomName = trim($data['room_name'] ?? 'Game Room ' . substr($roomId, 0, 6));
    $roomName = mb_substr($roomName, 0, 120);

    $password = !empty($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;
    $maxPlayers = max(2, min(16, (int)($data['max_players'] ?? 6)));

    try {
        $pdo->beginTransaction();

        $pdo->prepare("INSERT INTO game_rooms (room_id, game_id, room_name, password, max_players) VALUES (?, ?, ?, ?, ?)")
            ->execute([$roomId, $context['api']['id'], $roomName, $password, $maxPlayers]);

        addPlayerToRoom($roomId, $player['id'], $player['player_name'], $context['api']['id'], true);

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
    getAuthContext(); // validate API key

    global $pdo;
    try {
        $stmt = $pdo->query("
            SELECT r.room_id, r.room_name, r.max_players, 
                   COUNT(rp.player_id) as current_players,
                   r.password IS NOT NULL as has_password
            FROM game_rooms r
            LEFT JOIN room_players rp ON r.room_id = rp.room_id
            WHERE r.is_active = TRUE
            GROUP BY r.room_id
            HAVING current_players < r.max_players
            ORDER BY current_players DESC, r.room_name ASC
        ");

        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendResponse(['success' => true, 'rooms' => $rooms]);
    } catch (Exception $e) {
        error_log("List rooms failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to list rooms'], 500);
    }
}

function joinRoom($roomId) {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

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

        addPlayerToRoom($roomId, $player['id'], $player['player_name'], $room['game_id']);
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
    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) {
        sendResponse(['success' => false, 'error' => 'You are not in any room'], 400);
    }

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT rp.player_id, rp.player_name, rp.is_host, rp.is_online, rp.last_heartbeat
        FROM room_players rp
        WHERE rp.room_id = ?
        ORDER BY rp.is_host DESC, rp.joined_at ASC, rp.player_name ASC
    ");
    $stmt->execute([$roomId]);
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse(['success' => true, 'players' => $players, 'last_updated' => date('c')]);
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

        $pdo->prepare("DELETE FROM room_players WHERE player_id = ? AND room_id = ?")
            ->execute([$player['id'], $roomId]);

        if ($isHost) {
            $stmt = $pdo->prepare("SELECT player_id FROM room_players WHERE room_id = ? AND is_online = TRUE ORDER BY joined_at ASC LIMIT 1");
            $stmt->execute([$roomId]);
            $newHost = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($newHost) {
                $pdo->prepare("UPDATE room_players SET is_host = FALSE WHERE room_id = ?")->execute([$roomId]);
                $pdo->prepare("UPDATE room_players SET is_host = TRUE WHERE player_id = ? AND room_id = ?")->execute([$newHost['player_id'], $roomId]);
                $pdo->prepare("UPDATE game_rooms SET host_player_id = ? WHERE room_id = ?")->execute([$newHost['player_id'], $roomId]);
            } else {
                // Cleanup empty room
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

    $stmt = $pdo->prepare("
        SELECT player_id, is_online 
        FROM room_players 
        WHERE room_id = ? AND is_host = TRUE LIMIT 1
    ");
    $stmt->execute([$roomId]);
    $currentHost = $stmt->fetch(PDO::FETCH_ASSOC);

    $hostOffline = !$currentHost || !$currentHost['is_online'];

    if ($hostOffline) {
        if ($currentHost) {
            $pdo->prepare("UPDATE room_players SET is_host = FALSE WHERE player_id = ? AND room_id = ?")
                ->execute([$currentHost['player_id'], $roomId]);
        }

        $stmt = $pdo->prepare("
            SELECT player_id FROM room_players 
            WHERE room_id = ? AND is_online = TRUE 
            ORDER BY joined_at ASC LIMIT 1
        ");
        $stmt->execute([$roomId]);
        $newHost = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($newHost) {
            $pdo->prepare("UPDATE room_players SET is_host = TRUE WHERE player_id = ? AND room_id = ?")
                ->execute([$newHost['player_id'], $roomId]);
            $pdo->prepare("UPDATE game_rooms SET host_player_id = ?, is_active = TRUE WHERE room_id = ?")
                ->execute([$newHost['player_id'], $roomId]);
        } else {
            $pdo->prepare("DELETE FROM action_queue WHERE room_id = ?")->execute([$roomId]);
            $pdo->prepare("UPDATE game_rooms SET is_active = FALSE WHERE room_id = ?")->execute([$roomId]);
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
        $pdo->prepare("UPDATE room_players SET last_heartbeat = CURRENT_TIMESTAMP, is_online = TRUE WHERE player_id = ? AND room_id = ?")
            ->execute([$player['id'], $roomId]);

        $pdo->prepare("UPDATE game_players SET last_heartbeat = CURRENT_TIMESTAMP WHERE id = ?")
            ->execute([$player['id']]);

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
    $context = getAuthContext();
    $player = requirePlayer($context);

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    // Support both standard and Unity-friendly input
    $actionType = $data['action_type'] ?? null;
    $requestData = $data['request_data'] ?? null;

    if (empty($actionType)) {
        sendResponse(['success' => false, 'error' => 'Missing action_type'], 400);
    }

    if (isset($data['request_data_json']) && is_string($data['request_data_json'])) {
        $requestData = json_decode($data['request_data_json'], true);
    }

    if ($requestData === null) {
        sendResponse(['success' => false, 'error' => 'Missing request_data or request_data_json'], 400);
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
    $stmt->execute([$actionId, $roomId, $context['api']['id'], $player['id'], $actionType, json_encode($requestData, JSON_UNESCAPED_UNICODE), 'pending']);

    sendResponse([
        'success' => true,
        'action_id' => $actionId,
        'status' => 'pending'
    ]);
}

function pollActions() {
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
        $action = formatForUnity($action);
    }

    // Mark as read
    if (!empty($actions)) {
        $actionIds = array_column($actions, 'action_id');
        $placeholders = implode(',', array_fill(0, count($actionIds), '?'));
        $pdo->prepare("UPDATE action_queue SET status = 'read' WHERE action_id IN ($placeholders)")
            ->execute($actionIds);
    }

    sendResponse(['success' => true, 'actions' => $actions]);
}

function getPendingActions() {
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
        $action = formatForUnity($action);
    }

    sendResponse(['success' => true, 'actions' => $actions]);
}

function completeAction($actionId) {
    $context = getAuthContext();
    $player = requirePlayer($context);

    if (!isHost($player['id'])) {
        sendResponse(['success' => false, 'error' => 'Only host can complete actions'], 403);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $status = in_array($data['status'] ?? 'completed', ['completed', 'failed']) ? $data['status'] : 'completed';
    $responseData = isset($data['response_data']) ? json_encode($data['response_data'], JSON_UNESCAPED_UNICODE) : null;

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

function sendUpdates() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);

    if (!isHost($player['id'])) {
        sendResponse(['success' => false, 'error' => 'Only host can send updates'], 403);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if (empty($data['type']) || !isset($data['dataJson'])) {
        sendResponse(['success' => false, 'error' => 'Missing required fields: type, dataJson'], 400);
    }

    $updateType = trim($data['type']);
    $dataJson = is_string($data['dataJson']) ? $data['dataJson'] : json_encode($data['dataJson'], JSON_UNESCAPED_UNICODE);

    json_decode($dataJson);
    if (json_last_error() !== JSON_ERROR_NONE) {
        sendResponse(['success' => false, 'error' => 'Invalid JSON in dataJson field'], 400);
    }

    $targetPlayerIds = $data['targetPlayerIds'] ?? 'all';
    $targets = [];

    global $pdo;
    if ($targetPlayerIds === 'all') {
        $stmt = $pdo->prepare("SELECT player_id FROM room_players WHERE room_id = ? AND player_id != ? AND is_online = TRUE");
        $stmt->execute([$roomId, $player['id']]);
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } elseif (is_array($targetPlayerIds)) {
        $placeholders = implode(',', array_fill(0, count($targetPlayerIds), '?'));
        $stmt = $pdo->prepare("SELECT player_id FROM room_players WHERE room_id = ? AND player_id IN ($placeholders) AND is_online = TRUE");
        $stmt->execute(array_merge([$roomId], $targetPlayerIds));
        $targets = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    if (empty($targets)) {
        sendResponse(['success' => false, 'error' => 'No valid target players found'], 400);
    }

    $updateIds = [];
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            INSERT INTO player_updates 
            (update_id, room_id, game_id, from_player_id, target_player_id, type, data_json)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($targets as $targetPlayerId) {
            $updateId = bin2hex(random_bytes(16));
            $stmt->execute([$updateId, $roomId, $context['api']['id'], $player['id'], $targetPlayerId, $updateType, $dataJson]);
            $updateIds[] = $updateId;
        }

        $pdo->commit();

        sendResponse([
            'success' => true,
            'updates_sent' => count($updateIds),
            'update_ids' => $updateIds,
            'target_players' => $targets
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Send updates failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to send updates'], 500);
    }
}

function getCurrentGameRoomStatus() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    global $pdo;

    // Get room information
    $stmt = $pdo->prepare("
        SELECT 
            rp.room_id,
            rp.player_id,
            rp.player_name,
            rp.is_host,
            rp.is_online,
            rp.last_heartbeat,
            rp.joined_at,
            gr.room_name,
            gr.max_players,
            gr.password IS NOT NULL as has_password,
            gr.is_active,
            gr.created_at as room_created_at,
            gr.updated_at,
            gr.last_activity as room_last_activity,
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

    // Get pending actions for this player
    $stmt = $pdo->prepare("
        SELECT action_id, action_type, status, created_at, processed_at
        FROM action_queue 
        WHERE player_id = ? AND status IN ('pending', 'processing')
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$player['id']]);
    $pendingActions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get pending updates for this player
    $stmt = $pdo->prepare("
        SELECT update_id, from_player_id, type, data_json, created_at, status
        FROM player_updates 
        WHERE target_player_id = ? AND status = 'pending'
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$player['id']]);
    $pendingUpdates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format pending updates for Unity (convert data_json to data_json string)
    if ($isUnity) {
        foreach ($pendingUpdates as &$update) {
            if (!empty($update['data_json'])) {
                $decoded = json_decode($update['data_json'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $update['data_json'] = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }
        }
    }

    // Build response
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
            'is_active'          => (bool)$room['is_active'],
            'player_name'        => $room['player_name'],
            'joined_at'          => $room['joined_at'],
            'last_heartbeat'     => $room['last_heartbeat'],
            'room_created_at'    => $room['room_created_at'],
            'room_last_activity' => $room['room_last_activity']
        ],
        'pending_actions' => $pendingActions,
        'pending_updates' => $pendingUpdates
    ];

    // If Unity format → convert any complex fields (extra safety)
    if ($isUnity) {
        foreach ($response['pending_actions'] as &$action) {
            if (isset($action['request_data']) && is_string($action['request_data'])) {
                $decoded = json_decode($action['request_data'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $action['request_data_json'] = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    unset($action['request_data']);
                }
            }
        }
    }

    sendResponse($response);
}

function pollUpdates() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $roomId = getPlayerRoom($player['id']);
    if (!$roomId) sendResponse(['success' => false, 'error' => 'Player is not in any room'], 400);

    $lastUpdateId = $_GET['lastUpdateId'] ?? null;

    global $pdo;
    $whereClause = "WHERE target_player_id = ? AND room_id = ?";
    $params = [$player['id'], $roomId];

    if ($lastUpdateId) {
        $whereClause .= " AND update_id > ?";
        $params[] = $lastUpdateId;
    }

    $stmt = $pdo->prepare("
        SELECT update_id, from_player_id, type, data_json, created_at
        FROM player_updates $whereClause
        ORDER BY created_at ASC LIMIT 50
    ");
    $stmt->execute($params);
    $updates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($updates as &$update) {
        $update = formatForUnity($update);
    }

    // Mark as delivered
    if (!empty($updates)) {
        $updateIds = array_column($updates, 'update_id');
        $placeholders = implode(',', array_fill(0, count($updateIds), '?'));
        $pdo->prepare("UPDATE player_updates SET status = 'delivered', delivered_at = CURRENT_TIMESTAMP 
                       WHERE update_id IN ($placeholders) AND status = 'pending'")
            ->execute($updateIds);
    }

    sendResponse([
        'success' => true,
        'updates' => $updates,
        'last_update_id' => !empty($updates) ? end($updates)['update_id'] : $lastUpdateId
    ]);
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
        sendResponse(['success' => false, 'error' => 'Not found'], 404);
    }
} catch (Exception $e) {
    error_log("Critical error in game_room.php: " . $e->getMessage());
    sendResponse(['success' => false, 'error' => 'Internal server error'], 500);
}
?>