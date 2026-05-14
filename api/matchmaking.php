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
    global $isUnity;
    http_response_code($statusCode);
    
    // Apply Unity formatting before sending if needed
    if ($isUnity) {
        $data = formatForUnity($data);
    }
    
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

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

function getPlayerMatchmaking($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT mp.matchmaking_id
        FROM matchmaking_players mp
        JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
        WHERE mp.player_id = ? AND mp.is_online = TRUE
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
            mp.is_online as is_online,
            mp.joined_at,
            mp.last_heartbeat,
            m.max_players,
            m.strict_full,
            m.join_by_requests,
            m.rules,
            m.is_started,
            m.started_at,
            m.created_at,
            m.last_heartbeat as lobby_heartbeat
        FROM matchmaking_players mp
        JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
        WHERE mp.player_id = ? AND mp.is_online = TRUE
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

// ====================== UNITY FORMATTER ======================
// Ensures rules is a JSON string for Unity, and object/null for normal JSON clients
function formatForUnity($data) {
    global $isUnity;
    if (!$isUnity) return $data;

    // Handle top-level rules
    if (isset($data['rules'])) {
        if (is_string($data['rules']) && !empty($data['rules'])) {
            $decoded = json_decode($data['rules'], true);
            $data['rules_json'] = json_encode($decoded ?: new stdClass(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $data['rules_json'] = '{}';
        }

        unset($data['rules']);
    }

    // Handle nested in 'matchmaking' key (used in getCurrentMatchmakingStatus)
    if (isset($data['matchmaking']) && is_array($data['matchmaking'])) {
        if (isset($data['matchmaking']['rules'])) {
            if (is_string($data['matchmaking']['rules']) && !empty($data['matchmaking']['rules'])) {
                $decoded = json_decode($data['matchmaking']['rules'], true);
                $data['matchmaking']['rules_json'] = json_encode($decoded ?: new stdClass(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } else {
                $data['matchmaking']['rules_json'] = '{}';
            }
            
            unset($data['matchmaking']['rules']);
        }
    }

    return $data;
}

// ────────────────────────────────────────────────
//      Endpoints
// ────────────────────────────────────────────────

function listMatchmaking() {
    getAuthContext(); // just validate API key

    global $pdo;
    try {
        $stmt = $pdo->query("
            SELECT 
                m.matchmaking_id,
                m.matchmaking_name,
                m.host_player_id,
                m.max_players,
                m.strict_full,
                m.join_by_requests,
                m.host_switch,
                m.can_leave_room,
                m.realtime_room,
                m.rules,
                m.created_at,
                m.last_heartbeat,
                COUNT(mp.player_id) as current_players,
                gp.player_name as host_name
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.is_online = TRUE
            LEFT JOIN game_players gp ON m.host_player_id = gp.id
            WHERE m.is_started = FALSE
            GROUP BY m.matchmaking_id
            HAVING current_players < m.max_players
            ORDER BY m.created_at ASC
        ");

        $lobbies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($lobbies as &$lobby) {
            $lobby['realtime_room'] = (bool)$lobby['realtime_room'];
        }
        
        sendResponse(['success' => true, 'lobbies' => $lobbies]);
    } catch (Exception $e) {
        error_log("List matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to list matchmaking lobbies'], 500);
    }
}

function createMatchmaking() {
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    // Check if player is already in a matchmaking lobby
    $existingLobby = getPlayerMatchmaking($player['id']);
    if ($existingLobby) {
        sendResponse(['success' => false, 'error' => 'You are already in a matchmaking lobby'], 400);
    }

    // Check if player is already in a game room
    $existingRoom = getPlayerRoom($player['id']);
    if ($existingRoom) {
        sendResponse(['success' => false, 'error' => 'You cannot create matchmaking while in a game room. Leave the room first.'], 400);
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    if (!isset($data['max_players']) || empty($data['max_players'])) {
        sendResponse(['success' => false, 'error' => 'Missing required field: max_players'], 400);
    }

    $maxPlayers = max(2, min(16, (int)$data['max_players']));
    $strictFull = (bool) ($data['strict_full'] ?? false);
    $joinByRequests = (bool) ($data['join_by_requests'] ?? false);
    $hostSwitch = (bool) ($data['host_switch'] ?? false);
    $can_leave_room = (bool) ($data['can_leave_room'] ?? false);
    $realtimeRoom = (bool) ($data['realtime_room'] ?? false);

    $matchmakingId = bin2hex(random_bytes(16));

    if(isset($data['matchmaking_name']) && !empty($data['matchmaking_name'])) {
        $matchmakingName = $data['matchmaking_name'];
    } else {
        $matchmakingName = 'Matchmaking ' . substr($matchmakingId, 0, 6);
    }

    $matchmakingName = mb_substr($matchmakingName, 0, 120);


    if($isUnity)
    {
        $playerData = $data['player_data_json'] ?? null;
    }
    else
    {
        $playerData = $data['player_data'] ?? null;
    }

    if (is_string($playerData)) {
        $playerDataJson = $playerData !== '' ? $playerData : '{}';
    } else {
        $playerDataJson = isset($playerData) ? json_encode($playerData, JSON_UNESCAPED_UNICODE) : '{}';
    }


    if($isUnity)
    {
        $rules = $data['rules_json'] ?? null;
    }
    else
    {
        $rules = $data['rules'] ?? null;
    }

    if (is_string($rules)) {
        $rulesJson = $rules !== '' ? $rules : '{}';
    } else {
        $rulesJson = isset($rules) ? json_encode($rules, JSON_UNESCAPED_UNICODE) : '{}';
    }

    global $pdo;
    $pdo->beginTransaction();
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO matchmaking 
            (matchmaking_id, game_id, matchmaking_name, host_player_id, max_players, strict_full, join_by_requests, host_switch, can_leave_room, realtime_room, rules)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$matchmakingId, $context['api']['id'], $matchmakingName, $player['id'], $maxPlayers, $strictFull, $joinByRequests, $hostSwitch, $can_leave_room, $realtimeRoom, $rulesJson]);

        $stmt = $pdo->prepare("
            INSERT INTO matchmaking_players 
            (matchmaking_id, game_id, player_id, player_data)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$matchmakingId, $context['api']['id'], $player['id'], $playerDataJson]);

        $pdo->commit();

        sendResponse([
            'success' => true,
            'matchmaking_id' => $matchmakingId,
            'matchmaking_name' => $matchmakingName,
            'max_players' => $maxPlayers,
            'strict_full' => $strictFull,
            'join_by_requests' => $joinByRequests,
            'host_switch' => $hostSwitch,
            'can_leave_room' => $can_leave_room,
            'realtime_room' => $realtimeRoom,
            'is_host' => true
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Create matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to create matchmaking lobby'], 500);
    }
}

function requestJoin() {
    global $isUnity;
    
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
        SELECT 1 
        FROM matchmaking_requests 
        WHERE matchmaking_id = ? AND player_id = ? AND status = 'pending'
        LIMIT 1
    ");
    $stmt->execute([$matchmakingId, $player['id']]);
    if ($stmt->fetchColumn()) {
        sendResponse(['success' => false, 'error' => 'You already have a pending request to this matchmaking lobby'], 400);
    }

    $rawInput = file_get_contents('php://input');

    $decoded = json_decode($rawInput, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        $playerDataJson = $rawInput; // valid JSON, keep as-is
    } else {
        $playerDataJson = '{}'; // fallback
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT m.*, COUNT(mp.player_id) as current_players
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.is_online = TRUE
            WHERE m.matchmaking_id = ? AND m.is_started = FALSE
            GROUP BY m.matchmaking_id
        ");
        $stmt->execute([$matchmakingId]);
        $matchmaking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$matchmaking) throw new Exception('Matchmaking lobby not found or already started');
        if ($matchmaking['current_players'] >= $matchmaking['max_players']) throw new Exception('Matchmaking lobby is full');

        $requestId = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("
            INSERT INTO matchmaking_requests 
            (request_id, matchmaking_id, game_id, player_id, player_data)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$requestId, $matchmakingId, $matchmaking['game_id'], $player['id'], $playerDataJson]);

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
    global $isUnity;
    
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

    $rawInput = file_get_contents('php://input');

    $decoded = json_decode($rawInput, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        $playerDataJson = $rawInput; // valid JSON, keep as-is
    } else {
        $playerDataJson = '{}'; // fallback
    }

    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("
            SELECT m.*, COUNT(mp.player_id) as current_players
            FROM matchmaking m
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.is_online = TRUE
            WHERE m.matchmaking_id = ? AND m.is_started = FALSE
            GROUP BY m.matchmaking_id
        ");
        $stmt->execute([$matchmakingId]);
        $matchmaking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$matchmaking) throw new Exception('Matchmaking lobby not found or already started');
        if ($matchmaking['join_by_requests']) throw new Exception('This matchmaking lobby requires host approval. Use /request endpoint instead.');
        if ($matchmaking['current_players'] >= $matchmaking['max_players']) throw new Exception('Matchmaking lobby is full');

        $stmt = $pdo->prepare("
            INSERT INTO matchmaking_players 
            (matchmaking_id, game_id, player_id, player_data)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$matchmakingId, $matchmaking['game_id'], $player['id'], $playerDataJson]);

        checkAndReassignHost($matchmakingId);

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
            SELECT mp.matchmaking_id, m.host_player_id, m.host_switch, m.is_started, m.can_leave_room
            FROM matchmaking_players mp
            JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
            WHERE mp.player_id = ? AND mp.is_online = TRUE
            LIMIT 1
        ");
        $stmt->execute([$player['id']]);
        $playerLobby = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$playerLobby) throw new Exception('You are not in any matchmaking lobby');

        $matchmakingId = $playerLobby['matchmaking_id'];
        $isHost = ($playerLobby['host_player_id'] === $player['id']);
        $isStarted = (bool) $playerLobby['is_started'];
        $canLeaveRoom = (bool) $playerLobby['can_leave_room'];

        // Check if player can leave based on matchmaking status and can_leave_room setting
        if ($isStarted && !$isHost && !$canLeaveRoom) {
            sendResponse(['success' => false, 'error' => 'Players are not allowed to leave this matchmaking lobby'], 403);
        }

        $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ? AND player_id = ?")
             ->execute([$matchmakingId, $player['id']]);

        checkAndReassignHost($matchmakingId);

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
    try {
        $stmt = $pdo->prepare("
            SELECT 
                mp.player_id,
                mp.joined_at,
                mp.last_heartbeat,
                mp.is_online,
                mp.player_data,
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

        foreach ($players as &$playerData) {
            $playerData['player_id'] = (int)$playerData['player_id'];
            $playerData['is_host']   = (bool)$playerData['is_host'];
            $playerData['is_online'] = (bool)$playerData['is_online'];
            $playerData['is_local']  = ($playerData['player_id'] === $player['id']);

            $playerData['joined_at'] = isoUtc($playerData['joined_at']);
            $playerData['last_heartbeat'] = isoUtc($playerData['last_heartbeat']);
            
            // Handle player_data formatting for Unity
            global $isUnity;
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

        sendResponse([
            'success' => true,
            'players' => $players,
            'last_updated' => isoUtc(date('c'))
        ]);
    } catch (Exception $e) {
        error_log("Get matchmaking players failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to get players'], 500);
    }
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
        $pdo->prepare("
            UPDATE matchmaking_players 
            SET last_heartbeat = CURRENT_TIMESTAMP, is_online = TRUE
            WHERE matchmaking_id = ? AND player_id = ?
        ")->execute([$matchmakingId, $player['id']]);

        $pdo->prepare("UPDATE matchmaking SET last_heartbeat = CURRENT_TIMESTAMP WHERE matchmaking_id = ?")
             ->execute([$matchmakingId]);

        $pdo->prepare("UPDATE game_players SET last_heartbeat = CURRENT_TIMESTAMP WHERE id = ?")
             ->execute([$player['id']]);

        checkAndReassignHost($matchmakingId);

        $pdo->commit();
        sendResponse(['success' => true, 'status' => 'ok']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Matchmaking heartbeat update failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to update heartbeat'], 500);
    }
}

function checkAndReassignHost($matchmakingId) {
    global $pdo;

    // Get host_switch setting for this matchmaking
    $stmt = $pdo->prepare("SELECT host_switch FROM matchmaking WHERE matchmaking_id = ?");
    $stmt->execute([$matchmakingId]);
    $matchmakingData = $stmt->fetch(PDO::FETCH_ASSOC);
    $hostSwitch = (bool) ($matchmakingData['host_switch'] ?? false);

    $stmt = $pdo->prepare("
        SELECT mp.player_id, mp.last_heartbeat,
               TIMESTAMPDIFF(SECOND, mp.last_heartbeat, NOW()) as seconds_since_heartbeat,
               gp.last_heartbeat as player_last_heartbeat,
               TIMESTAMPDIFF(SECOND, gp.last_heartbeat, NOW()) as player_seconds_since_heartbeat,
               mp.is_online
        FROM matchmaking_players mp
        JOIN game_players gp ON mp.player_id = gp.id
        WHERE mp.matchmaking_id = ? AND mp.player_id = (SELECT host_player_id FROM matchmaking WHERE matchmaking_id = ?)
        LIMIT 1
    ");
    $stmt->execute([$matchmakingId, $matchmakingId]);
    $currentHost = $stmt->fetch(PDO::FETCH_ASSOC);

    $hostOffline = !$currentHost || (!$currentHost['is_online']);

    if ($hostOffline) {
        if ($currentHost && $hostSwitch === true) {
            // Only remove host status if host_switch is true (allows host transfer)
            $pdo->prepare("
                UPDATE matchmaking 
                SET host_player_id = NULL
                WHERE matchmaking_id = ?
            ")->execute([$matchmakingId]);
        }

        if ($hostSwitch === true) {
            // Find next available active player (oldest joined first)
            $stmt = $pdo->prepare("
                SELECT player_id 
                FROM matchmaking_players 
                WHERE matchmaking_id = ? 
                  AND is_online = TRUE
                ORDER BY joined_at ASC
                LIMIT 1
            ");
            $stmt->execute([$matchmakingId]);
            $newHost = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($newHost) {
                // Assign new host
                $pdo->prepare("
                    UPDATE matchmaking 
                    SET host_player_id = ?
                    WHERE matchmaking_id = ?
                ")->execute([$newHost['player_id'], $matchmakingId]);
            } else {
                // No players left -> delete the entire matchmaking
                $pdo->prepare("DELETE FROM matchmaking_requests WHERE matchmaking_id = ?")->execute([$matchmakingId]);
                $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ?")->execute([$matchmakingId]);
                $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);
            }
        } else {
            // If host_switch is false and host left, delete the entire matchmaking
            $pdo->prepare("DELETE FROM matchmaking_requests WHERE matchmaking_id = ?")->execute([$matchmakingId]);
            $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ?")->execute([$matchmakingId]);
            $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);
        }
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
    global $isUnity;

    $context = getAuthContext();
    $player = requirePlayer($context);

    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT 
                mp.matchmaking_id,
                mp.player_id,
                mp.joined_at,
                mp.last_heartbeat,
                mp.is_online as is_online,
                m.host_player_id,
                m.matchmaking_name,
                m.max_players,
                m.strict_full,
                m.join_by_requests,
                m.host_switch,
                m.can_leave_room,
                m.realtime_room,
                m.rules,
                m.created_at,
                m.last_heartbeat as lobby_heartbeat,
                m.is_started,
                m.started_at,
                (mp.player_id = m.host_player_id) as is_host,
                COUNT(CASE WHEN mp2.is_online = TRUE THEN 1 END) as current_players
            FROM matchmaking_players mp
            JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
            LEFT JOIN matchmaking_players mp2 ON m.matchmaking_id = mp2.matchmaking_id
            WHERE mp.player_id = ?
            GROUP BY m.matchmaking_id
            LIMIT 1
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
                SELECT 
                    request_id, matchmaking_id, status, requested_at, responded_at
                FROM matchmaking_requests 
                WHERE matchmaking_id = ? AND status = 'pending'
                ORDER BY requested_at DESC
                LIMIT 5
            ");
            $stmt->execute([$matchmaking['matchmaking_id']]);
            $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($pendingRequests as &$request) {
                $request['requested_at']     = isoUtc($request['requested_at']);
                $request['responded_at']    = isoUtc($request['responded_at']);
            }
        }

        if($isUnity)
        {
            $decoded = json_decode($matchmaking['rules']);

            $rules = (json_last_error() === JSON_ERROR_NONE && $decoded !== null)
                ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : '{}';
        }
        else
        {
            $rules = (json_last_error() === JSON_ERROR_NONE)
                ? json_decode($matchmaking['rules'])
                : null;
        }

        $responseData = [
            'success' => true,
            'in_matchmaking' => true,
            'matchmaking' => [
                'matchmaking_id' => $matchmaking['matchmaking_id'],
                'matchmaking_name' => $matchmaking['matchmaking_name'],
                'is_host' => (bool)$matchmaking['is_host'],
                'max_players' => (int)$matchmaking['max_players'],
                'current_players' => (int)$matchmaking['current_players'],
                'strict_full' => (bool)$matchmaking['strict_full'],
                'join_by_requests' => (bool)$matchmaking['join_by_requests'],
                'host_switch' => (bool)$matchmaking['host_switch'],
                'can_leave_room' => (bool)$matchmaking['can_leave_room'],
                'realtime_room' => (bool)$matchmaking['realtime_room'],
                'rules' => $rules,
                'joined_at' => isoUtc($matchmaking['joined_at']),
                'is_online' => (bool)$matchmaking['is_online'],
                'last_heartbeat' => isoUtc($matchmaking['last_heartbeat']),
                'lobby_heartbeat' => isoUtc($matchmaking['lobby_heartbeat']),
                'is_started' => (bool)$matchmaking['is_started'],
                'started_at' => isoUtc($matchmaking['started_at'])
            ],
            'pending_requests' => $pendingRequests
        ];

        sendResponse($responseData);
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
        SELECT 
            mr.request_id, mr.matchmaking_id, mr.player_id, mr.status, mr.requested_at, mr.responded_at,
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
            'requested_at' => isoUtc($request['requested_at']),
            'responded_at' => isoUtc($request['responded_at']),
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

    if (!$requestId || !isset($data['action']) || empty($data['action'])) {
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
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.is_online = TRUE
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
                INSERT INTO matchmaking_players (matchmaking_id, game_id, player_id, player_data)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE is_online = TRUE, joined_at = CURRENT_TIMESTAMP, last_heartbeat = CURRENT_TIMESTAMP, player_data = VALUES(player_data)
            ")->execute([$request['matchmaking_id'], $request['game_id'], $request['player_id'], $request['player_data']]);
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

function stopMatchmaking() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    $currentMatchmaking = getPlayerMatchmakingDetails($player['id']);
    if (!$currentMatchmaking) {
        sendResponse(['success' => false, 'error' => 'You are not in a matchmaking lobby'], 400);
    }

    if (!$currentMatchmaking['is_host']) {
        sendResponse(['success' => false, 'error' => 'Only host can stop matchmaking lobby'], 403);
    }

    // Check if matchmaking has already been started
    if ((bool)$currentMatchmaking['is_started']) {
        sendResponse(['success' => false, 'error' => 'Cannot stop matchmaking lobby after it has been started'], 403);
    }

    $matchmakingId = $currentMatchmaking['matchmaking_id'];

    global $pdo;
    $pdo->beginTransaction();
    try {
        // Delete all matchmaking-related data
        $pdo->prepare("DELETE FROM matchmaking_requests WHERE matchmaking_id = ?")->execute([$matchmakingId]);
        $pdo->prepare("DELETE FROM matchmaking_players WHERE matchmaking_id = ?")->execute([$matchmakingId]);
        $pdo->prepare("DELETE FROM matchmaking WHERE matchmaking_id = ?")->execute([$matchmakingId]);

        $pdo->commit();
        sendResponse(['success' => true, 'message' => 'Matchmaking lobby stopped successfully']);
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Stop matchmaking failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to stop matchmaking lobby'], 500);
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
            LEFT JOIN matchmaking_players mp ON m.matchmaking_id = mp.matchmaking_id AND mp.is_online = TRUE
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
        $roomName = $matchmaking['matchmaking_name'] ?? 'Game from Matchmaking ' . substr($matchmakingId, 0, 6);

        $pdo->prepare("
            INSERT INTO game_rooms (room_id, game_id, room_name, max_players, host_switch, can_leave, realtime, matchmaking_id, rules)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ")->execute([$roomId, $matchmaking['game_id'], $roomName, $matchmaking['max_players'], $matchmaking['host_switch'], $matchmaking['can_leave_room'], $matchmaking['realtime_room'], $matchmakingId, $matchmaking['rules']]);

        $pdo->prepare("
            INSERT INTO room_players (player_id, room_id, game_id, player_name, is_host, last_heartbeat, joined_at, is_online, player_data)
            SELECT mp.player_id, ?, mp.game_id, gp.player_name, 
                   (m.host_player_id = mp.player_id) as is_host, 
                   mp.last_heartbeat, mp.joined_at, TRUE, mp.player_data
            FROM matchmaking_players mp
            JOIN game_players gp ON mp.player_id = gp.id
            JOIN matchmaking m ON mp.matchmaking_id = m.matchmaking_id
            WHERE mp.matchmaking_id = ? AND mp.is_online = TRUE
        ")->execute([$roomId, $matchmakingId]);

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
    } elseif ($method === 'POST' && preg_match('#/stop/?$#', $path)) {
        stopMatchmaking();
    } else {
        sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
    }
} catch (Exception $e) {
    error_log("Critical error in matchmaking.php: " . $e->getMessage());
    sendResponse(['success' => false, 'error' => 'Internal server error'], 500);
}
?>