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
error_log("=== MATCHMAKING REQUEST ===");
error_log("URI: " . $_SERVER['REQUEST_URI']);
error_log("Method: " . $_SERVER['REQUEST_METHOD']);

require_once __DIR__ . '/../php/config.php';

// ====================== FORMAT HANDLING ======================
$format = strtolower($_GET['format'] ?? 'json');
$isUnity = ($format === 'unity');
// ============================================================

// ────────────────────────────────────────────────
//      Helper functions
// ────────────────────────────────────────────────
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

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

function getPlayerMatchmaking($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT mp.matchmaking_id
        FROM matchmaking_players mp
        JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
        WHERE mp.player_id = ? AND mp.status = 'active' AND m.is_started = FALSE
        LIMIT 1
    ");
    $stmt->execute([$playerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['matchmaking_id'] : null;
}

function getPlayerRoom($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT rp.room_id
        FROM room_players rp
        JOIN game_rooms gr ON rp.room_id = gr.room_id
        WHERE rp.player_id = ? AND gr.is_active = TRUE
        LIMIT 1
    ");
    $stmt->execute([$playerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['room_id'] : null;
}

function getPlayerMatchmakingDetails($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            mp.matchmaking_id, 
            m.host_player_id,
            (m.host_player_id = ?) as is_host,
            mp.status as player_status,
            mp.joined_at,
            mp.last_heartbeat,
            m.max_players,
            m.strict_full,
            m.join_by_requests,
            m.extra_json_string,
            m.is_started,
            m.started_at,
            m.created_at,
            m.last_heartbeat as lobby_heartbeat
        FROM matchmaking_players mp
        JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
        WHERE mp.player_id = ? AND mp.status = 'active' AND m.is_started = FALSE
        LIMIT 1
    ");
    $stmt->execute([$playerId, $playerId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ?: null;
}

function isMatchmakingHost($playerId, $matchmakingId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 1 
        FROM matchmaking 
        WHERE matchmaking_id = ? AND host_player_id = ?
        LIMIT 1
    ");
    $stmt->execute([$matchmakingId, $playerId]);
    return (bool) $stmt->fetchColumn();
}

// Unity formatter helper
function formatForUnity($data) {
    global $isUnity;
    if (!$isUnity) return $data;

    if (isset($data['extra_json_string'])) {
        if (is_string($data['extra_json_string']) && !empty($data['extra_json_string'])) {
            $decoded = json_decode($data['extra_json_string'], true);
            $data['extra_json_string'] = json_encode($decoded ?: new stdClass(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $data['extra_json_string'] = '{}';
        }
    }
    return $data;
}

// ────────────────────────────────────────────────
//      Endpoints
// ────────────────────────────────────────────────

function listMatchmaking() {
    getAuthContext(); // validate API key

    global $pdo;
    try {
        $stmt = $pdo->query("
            SELECT 
                m.matchmaking_id,
                m.host_player_id,
                m.max_players,
                m.strict_full,
                m.extra_json_string,
                m.created_at,
                m.last_heartbeat,
                COUNT(mp.player_id) as current_players,
                gp.player_name as host_name
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.status = 'active'
            LEFT JOIN game_players gp ON m.host_player_id = gp.id
            WHERE m.is_started = FALSE
            GROUP BY m.matchmaking_id
            HAVING current_players < m.max_players
            ORDER BY m.created_at ASC
        ");

        $lobbies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($lobbies as &$lobby) {
            $lobby = formatForUnity($lobby);
        }
        
        sendResponse(['success' => true, 'lobbies' => $lobbies]);
    } catch (Exception $e) {
        error_log("List matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to list matchmaking lobbies'], 500);
    }
}

function createMatchmaking() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $existingLobby = getPlayerMatchmaking($player['id']);
    if ($existingLobby) {
        sendResponse(['success' => false, 'error' => 'You are already in a matchmaking lobby'], 400);
    }

    $existingRoom = getPlayerRoom($player['id']);
    if ($existingRoom) {
        sendResponse(['success' => false, 'error' => 'You cannot create matchmaking while in a game room. Leave the room first.'], 400);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if (empty($data['maxPlayers'])) {
        sendResponse(['success' => false, 'error' => 'Missing required field: maxPlayers'], 400);
    }

    $maxPlayers = max(2, min(16, (int)$data['maxPlayers']));
    $strictFull = !empty($data['strictFull']);
    $joinByRequests = !empty($data['joinByRequests']);
    $extraJsonString = isset($data['extraJsonString']) ? json_encode($data['extraJsonString'], JSON_UNESCAPED_UNICODE) : null;

    $matchmakingId = bin2hex(random_bytes(16));

    global $pdo;
    $pdo->beginTransaction();
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO matchmaking 
            (matchmaking_id, game_id, host_player_id, max_players, strict_full, join_by_requests, extra_json_string)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$matchmakingId, $context['api']['id'], $player['id'], $maxPlayers, $strictFull, $joinByRequests, $extraJsonString]);

        $stmt = $pdo->prepare("
            INSERT INTO matchmaking_players (matchmaking_id, game_id, player_id)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$matchmakingId, $context['api']['id'], $player['id']]);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'matchmaking_id' => $matchmakingId,
            'max_players' => $maxPlayers,
            'strict_full' => $strictFull,
            'join_by_requests' => $joinByRequests,
            'is_host' => true
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Create matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to create matchmaking lobby'], 500);
    }
}

function requestJoin() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $matchmakingId = $_GET['matchmakingId'] ?? null;
    if (!$matchmakingId) {
        sendResponse(['success' => false, 'error' => 'Missing required parameter: matchmakingId'], 400);
    }

    $existingLobby = getPlayerMatchmaking($player['id']);
    if ($existingLobby) {
        sendResponse(['success' => false, 'error' => 'You are already in a matchmaking lobby'], 400);
    }

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 1 FROM matchmaking_requests 
        WHERE matchmaking_id = ? AND player_id = ? AND status = 'pending' LIMIT 1
    ");
    $stmt->execute([$matchmakingId, $player['id']]);
    if ($stmt->fetchColumn()) {
        sendResponse(['success' => false, 'error' => 'You already have a pending request'], 400);
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT m.*, COUNT(mp.player_id) as current_players
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.status = 'active'
            WHERE m.matchmaking_id = ? AND m.is_started = FALSE
            GROUP BY m.matchmaking_id
        ");
        $stmt->execute([$matchmakingId]);
        $matchmaking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$matchmaking) throw new Exception('Matchmaking lobby not found or already started');
        if ($matchmaking['current_players'] >= $matchmaking['max_players']) throw new Exception('Matchmaking lobby is full');

        $requestId = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("
            INSERT INTO matchmaking_requests (request_id, matchmaking_id, game_id, player_id)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$requestId, $matchmakingId, $matchmaking['game_id'], $player['id']]);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'request_id' => $requestId,
            'message' => 'Join request sent to host'
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Request join failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }
}

function joinMatchmaking() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $matchmakingId = $_GET['matchmakingId'] ?? null;
    if (!$matchmakingId) {
        sendResponse(['success' => false, 'error' => 'Missing required parameter: matchmakingId'], 400);
    }

    $existingLobby = getPlayerMatchmaking($player['id']);
    if ($existingLobby) {
        sendResponse(['success' => false, 'error' => 'You are already in a matchmaking lobby'], 400);
    }

    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT m.*, COUNT(mp.player_id) as current_players
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.status = 'active'
            WHERE m.matchmaking_id = ? AND m.is_started = FALSE
            GROUP BY m.matchmaking_id
        ");
        $stmt->execute([$matchmakingId]);
        $matchmaking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$matchmaking) throw new Exception('Matchmaking lobby not found or already started');
        if ($matchmaking['join_by_requests']) throw new Exception('This lobby requires host approval. Use /request instead.');
        if ($matchmaking['current_players'] >= $matchmaking['max_players']) throw new Exception('Matchmaking lobby is full');

        $stmt = $pdo->prepare("
            INSERT INTO matchmaking_players (matchmaking_id, game_id, player_id)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$matchmakingId, $matchmaking['game_id'], $player['id']]);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'matchmaking_id' => $matchmakingId,
            'message' => 'Successfully joined matchmaking lobby'
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Join matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }
}

function leaveMatchmaking() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT mp.matchmaking_id, m.host_player_id
            FROM matchmaking_players mp
            JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
            WHERE mp.player_id = ? AND mp.status = 'active' AND m.is_started = FALSE
            LIMIT 1
        ");
        $stmt->execute([$player['id']]);
        $playerLobby = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$playerLobby) throw new Exception('You are not in any matchmaking lobby');

        $matchmakingId = $playerLobby['matchmaking_id'];
        $isHost = ($playerLobby['host_player_id'] === $player['id']);

        $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ? AND player_id = ?")
            ->execute([$matchmakingId, $player['id']]);

        if ($isHost) {
            $stmt = $pdo->prepare("
                SELECT player_id FROM matchmaking_players 
                WHERE matchmaking_id = ? AND status = 'active'
                ORDER BY joined_at ASC LIMIT 1
            ");
            $stmt->execute([$matchmakingId]);
            $newHost = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($newHost) {
                $pdo->prepare("UPDATE matchmaking SET host_player_id = ? WHERE matchmaking_id = ?")
                    ->execute([$newHost['player_id'], $matchmakingId]);
            } else {
                $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);
            }
        }

        $pdo->commit();
        sendResponse(['success' => true, 'message' => 'Successfully left matchmaking lobby']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Leave matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }
}

function getMatchmakingPlayers() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $matchmakingId = getPlayerMatchmaking($player['id']);
    if (!$matchmakingId) {
        sendResponse(['success' => false, 'error' => 'You are not in any matchmaking lobby'], 400);
    }

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT 
            mp.player_id, mp.joined_at, mp.last_heartbeat, mp.status,
            gp.player_name,
            TIMESTAMPDIFF(SECOND, mp.last_heartbeat, NOW()) as seconds_since_heartbeat,
            (m.host_player_id = mp.player_id) as is_host
        FROM matchmaking_players mp
        JOIN game_players gp ON mp.player_id = gp.id
        JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
        WHERE mp.matchmaking_id = ?
        ORDER BY is_host DESC, mp.joined_at ASC
    ");
    $stmt->execute([$matchmakingId]);
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

    sendResponse([
        'success' => true,
        'players' => $players,
        'last_updated' => date('c')
    ]);
}

function updateMatchmakingHeartbeat() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $matchmakingId = getPlayerMatchmaking($player['id']);
    if (!$matchmakingId) {
        sendResponse(['success' => false, 'error' => 'You are not in any matchmaking lobby'], 400);
    }

    global $pdo;
    $pdo->beginTransaction();
    try {
        $pdo->prepare("UPDATE matchmaking_players SET last_heartbeat = CURRENT_TIMESTAMP, status = 'active' WHERE matchmaking_id = ? AND player_id = ?")
            ->execute([$matchmakingId, $player['id']]);

        $pdo->prepare("UPDATE matchmaking SET last_heartbeat = CURRENT_TIMESTAMP WHERE matchmaking_id = ?")
            ->execute([$matchmakingId]);

        $pdo->prepare("UPDATE game_players SET last_heartbeat = CURRENT_TIMESTAMP WHERE id = ?")
            ->execute([$player['id']]);

        $pdo->commit();
        sendResponse(['success' => true, 'status' => 'ok']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Matchmaking heartbeat failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to update heartbeat'], 500);
    }
}

function removeMatchmaking() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $currentMatchmaking = getPlayerMatchmakingDetails($player['id']);
    if (!$currentMatchmaking) {
        sendResponse(['success' => false, 'error' => 'You are not in a matchmaking lobby'], 400);
    }

    if (!$currentMatchmaking['is_host']) {
        sendResponse(['success' => false, 'error' => 'Only host can remove matchmaking lobby'], 403);
    }

    $matchmakingId = $currentMatchmaking['matchmaking_id'];

    global $pdo;
    $pdo->beginTransaction();
    try {
        $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ?")->execute([$matchmakingId]);
        $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);

        $pdo->commit();
        sendResponse(['success' => true, 'message' => 'Matchmaking lobby removed successfully']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Remove matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to remove matchmaking lobby'], 500);
    }
}

function getCurrentMatchmakingStatus() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT 
                mp.matchmaking_id, mp.player_id, mp.joined_at, mp.last_heartbeat, mp.status as player_status,
                m.host_player_id, m.max_players, m.strict_full, m.join_by_requests, m.extra_json_string,
                m.created_at, m.last_heartbeat as lobby_heartbeat, m.is_started, m.started_at,
                (mp.player_id = m.host_player_id) as is_host,
                COUNT(CASE WHEN mp2.status = 'active' THEN 1 END) as current_players
            FROM matchmaking_players mp
            JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
            LEFT JOIN matchmaking_players mp2 ON m.matchmaking_id = mp2.matchmaking_id
            WHERE mp.player_id = ? AND m.is_started = FALSE
            GROUP BY m.matchmaking_id LIMIT 1
        ");
        $stmt->execute([$player['id']]);
        $matchmaking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$matchmaking) {
            sendResponse([
                'success' => true,
                'in_matchmaking' => false,
                'message' => 'Player is not in any matchmaking lobby'
            ]);
        }

        $pendingRequests = [];
        if ((bool)$matchmaking['is_host']) {
            $stmt = $pdo->prepare("
                SELECT request_id, matchmaking_id, status, requested_at, responded_at
                FROM matchmaking_requests 
                WHERE matchmaking_id = ? AND status = 'pending'
                ORDER BY requested_at DESC LIMIT 5
            ");
            $stmt->execute([$matchmaking['matchmaking_id']]);
            $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $response = [
            'success' => true,
            'in_matchmaking' => true,
            'matchmaking' => [
                'matchmaking_id' => $matchmaking['matchmaking_id'],
                'is_host' => (bool)$matchmaking['is_host'],
                'max_players' => (int)$matchmaking['max_players'],
                'current_players' => (int)$matchmaking['current_players'],
                'strict_full' => (bool)$matchmaking['strict_full'],
                'join_by_requests' => (bool)$matchmaking['join_by_requests'],
                'joined_at' => $matchmaking['joined_at'],
                'player_status' => $matchmaking['player_status'],
                'last_heartbeat' => $matchmaking['last_heartbeat'],
                'lobby_heartbeat' => $matchmaking['lobby_heartbeat'],
                'is_started' => (bool)$matchmaking['is_started'],
                'started_at' => $matchmaking['started_at']
            ],
            'pending_requests' => $pendingRequests
        ];

        // Apply Unity formatting for extra_json_string
        $response['matchmaking'] = formatForUnity($response['matchmaking']);

        sendResponse($response);
    } catch (Exception $e) {
        error_log("Get current matchmaking status failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to get matchmaking status'], 500);
    }
}

function checkRequestStatus() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $requestId = $_GET['requestId'] ?? null;
    if (!$requestId) {
        sendResponse(['success' => false, 'error' => 'Missing required parameter: requestId'], 400);
    }

    global $pdo;
    $stmt = $pdo->prepare("
        SELECT mr.request_id, mr.matchmaking_id, mr.player_id, mr.status, mr.requested_at, mr.responded_at,
               mr.responded_by, gp.player_name as responder_name, m.host_player_id, m.join_by_requests
        FROM matchmaking_requests mr
        LEFT JOIN game_players gp ON mr.responded_by = gp.id
        LEFT JOIN matchmaking m ON mr.matchmaking_id = m.matchmaking_id
        WHERE mr.request_id = ? AND mr.player_id = ?
    ");
    $stmt->execute([$requestId, $player['id']]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        sendResponse(['success' => false, 'error' => 'Request not found or you are not the requester'], 404);
    }

    sendResponse([
        'success' => true,
        'request' => [
            'request_id' => $request['request_id'],
            'matchmaking_id' => $request['matchmaking_id'],
            'status' => $request['status'],
            'requested_at' => $request['requested_at'],
            'responded_at' => $request['responded_at'],
            'responded_by' => $request['responded_by'],
            'responder_name' => $request['responder_name'],
            'join_by_requests' => (bool)$request['join_by_requests']
        ]
    ]);
}

function respondToRequest() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $requestId = $_GET['requestId'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if (!$requestId || empty($data['action'])) {
        sendResponse(['success' => false, 'error' => 'Missing required fields: requestId and action'], 400);
    }

    $action = $data['action'];
    if (!in_array($action, ['approve', 'reject'])) {
        sendResponse(['success' => false, 'error' => 'Action must be "approve" or "reject"'], 400);
    }

    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT mr.*, m.host_player_id, m.matchmaking_id
            FROM matchmaking_requests mr
            JOIN matchmaking m ON mr.matchmaking_id = m.matchmaking_id
            WHERE mr.request_id = ? AND mr.status = 'pending'
        ");
        $stmt->execute([$requestId]);
        $request = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$request) throw new Exception('Request not found or already processed');
        if ($request['host_player_id'] !== $player['id']) throw new Exception('Only the host can respond to join requests');

        $stmt = $pdo->prepare("
            SELECT COUNT(*) as current_players, max_players
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.status = 'active'
            WHERE m.matchmaking_id = ? AND m.is_started = FALSE
            GROUP BY m.matchmaking_id
        ");
        $stmt->execute([$request['matchmaking_id']]);
        $lobbyStatus = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$lobbyStatus) throw new Exception('Matchmaking lobby not found or already started');

        $status = ($action === 'approve') ? 'approved' : 'rejected';
        $pdo->prepare("
            UPDATE matchmaking_requests 
            SET status = ?, responded_at = CURRENT_TIMESTAMP, responded_by = ?
            WHERE request_id = ?
        ")->execute([$status, $player['id'], $requestId]);

        if ($action === 'approve') {
            if ($lobbyStatus['current_players'] >= $lobbyStatus['max_players']) {
                throw new Exception('Matchmaking lobby is full');
            }

            $pdo->prepare("
                INSERT INTO matchmaking_players (matchmaking_id, game_id, player_id)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE status = 'active', joined_at = CURRENT_TIMESTAMP, last_heartbeat = CURRENT_TIMESTAMP
            ")->execute([$request['matchmaking_id'], $request['game_id'], $request['player_id']]);
        }

        $pdo->commit();

        sendResponse([
            'success' => true,
            'message' => "Join request {$status} successfully",
            'request_id' => $requestId,
            'action' => $action
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Respond to request failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }
}

function startMatchmaking() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $currentMatchmaking = getPlayerMatchmakingDetails($player['id']);
    if (!$currentMatchmaking) {
        sendResponse(['success' => false, 'error' => 'You are not in a matchmaking lobby'], 400);
    }

    if (!$currentMatchmaking['is_host']) {
        sendResponse(['success' => false, 'error' => 'Only host can start matchmaking'], 403);
    }

    $matchmakingId = $currentMatchmaking['matchmaking_id'];

    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT m.*, COUNT(mp.player_id) as current_players
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.status = 'active'
            WHERE m.matchmaking_id = ? AND m.is_started = FALSE
            GROUP BY m.matchmaking_id
        ");
        $stmt->execute([$matchmakingId]);
        $matchmaking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$matchmaking) throw new Exception('Matchmaking lobby not found or already started');
        if ($matchmaking['strict_full'] && $matchmaking['current_players'] < $matchmaking['max_players']) {
            throw new Exception('Lobby must be full to start (strict_full enabled)');
        }

        $roomId = bin2hex(random_bytes(16));
        $roomName = 'Game from Matchmaking ' . substr($matchmakingId, 0, 6);

        $pdo->prepare("
            INSERT INTO game_rooms (room_id, game_id, room_name, max_players, matchmaking_id)
            VALUES (?, ?, ?, ?, ?)
        ")->execute([$roomId, $matchmaking['game_id'], $roomName, $matchmaking['max_players'], $matchmakingId]);

        $pdo->prepare("
            INSERT INTO room_players (player_id, room_id, game_id, player_name, is_host, last_heartbeat, joined_at, is_online)
            SELECT mp.player_id, ?, mp.game_id, gp.player_name, 
                   (m.host_player_id = mp.player_id) as is_host, 
                   mp.last_heartbeat, mp.joined_at, TRUE
            FROM matchmaking_players mp
            JOIN game_players gp ON mp.player_id = gp.id
            JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
            WHERE mp.matchmaking_id = ? AND mp.status = 'active'
        ")->execute([$roomId, $matchmakingId]);

        // Set host
        $stmt = $pdo->prepare("SELECT player_id FROM room_players WHERE room_id = ? AND player_id = ?");
        $stmt->execute([$roomId, $player['id']]);
        $hostRoomPlayerId = $stmt->fetchColumn();

        if ($hostRoomPlayerId) {
            $pdo->prepare("UPDATE game_rooms SET host_player_id = ? WHERE room_id = ?")
                ->execute([$hostRoomPlayerId, $roomId]);
        }

        $pdo->prepare("UPDATE matchmaking SET is_started = TRUE, started_at = CURRENT_TIMESTAMP WHERE matchmaking_id = ?")
            ->execute([$matchmakingId]);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'room_id' => $roomId,
            'room_name' => $roomName,
            'players_transferred' => $matchmaking['current_players'],
            'message' => 'Game started successfully'
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Start matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => $e->getMessage()], 400);
    }
}

// ────────────────────────────────────────────────
//      Routing
// ────────────────────────────────────────────────
try {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET' && preg_match('#/list/?$#', $path)) {
        listMatchmaking();
    } elseif ($method === 'POST' && preg_match('#/create/?$#', $path)) {
        createMatchmaking();
    } elseif ($method === 'POST' && preg_match('#/([^/]+)/request/?$#', $path, $matches)) {
        $_GET['matchmakingId'] = $matches[1];
        requestJoin();
    } elseif ($method === 'POST' && preg_match('#/([^/]+)/response/?$#', $path, $matches)) {
        $_GET['requestId'] = $matches[1];
        respondToRequest();
    } elseif ($method === 'GET' && preg_match('#/([^/]+)/status/?$#', $path, $matches)) {
        $_GET['requestId'] = $matches[1];
        checkRequestStatus();
    } elseif ($method === 'GET' && preg_match('#/current/?$#', $path)) {
        getCurrentMatchmakingStatus();
    } elseif ($method === 'POST' && preg_match('#/([^/]+)/join/?$#', $path, $matches)) {
        $_GET['matchmakingId'] = $matches[1];
        joinMatchmaking();
    } elseif ($method === 'POST' && preg_match('#/leave/?$#', $path)) {
        leaveMatchmaking();
    } elseif ($method === 'GET' && preg_match('#/players/?$#', $path)) {
        getMatchmakingPlayers();
    } elseif ($method === 'POST' && preg_match('#/heartbeat/?$#', $path)) {
        updateMatchmakingHeartbeat();
    } elseif ($method === 'POST' && preg_match('#/remove/?$#', $path)) {
        removeMatchmaking();
    } elseif ($method === 'POST' && preg_match('#/start/?$#', $path)) {
        startMatchmaking();
    } else {
        sendResponse(['success' => false, 'error' => 'Not found'], 404);
    }
} catch (Exception $e) {
    error_log("Critical error in matchmaking.php: " . $e->getMessage());
    sendResponse(['success' => false, 'error' => 'Internal server error'], 500);
}
?>