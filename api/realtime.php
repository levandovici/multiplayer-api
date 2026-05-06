<?php
// ====================== CORS & HEADERS ======================
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
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
error_log("=== REALTIME TOKEN REQUEST ===");
error_log("URI: " . $_SERVER['REQUEST_URI']);
error_log("Method: " . $_SERVER['REQUEST_METHOD']);

require_once __DIR__ . '/../php/config.php';

// Helper function to send JSON response
function sendResponse($data, $statusCode = 200) {
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
    return $context['player'];
}

function generateSecureToken($length = 64) {
    return bin2hex(random_bytes($length / 2));
}

// ====================== ENDPOINTS ======================

function generateToken() {
    $context = getAuthContext();
    $player = requirePlayer($context);

    global $pdo;
    
    try {
        // Check if player already has a token
        $stmt = $pdo->prepare("
            SELECT rp.*, gp.player_name, gr.room_id, gr.realtime, rp2.is_host
            FROM realtime_players rp
            JOIN game_players gp ON rp.game_player_id = gp.id
            LEFT JOIN room_players rp2 ON rp.game_player_id = rp2.player_id AND rp2.is_online = TRUE
            LEFT JOIN game_rooms gr ON rp2.room_id = gr.room_id
            WHERE rp.game_player_id = ?
        ");
        $stmt->execute([$player['id']]);
        $existingToken = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if player is in a realtime-enabled room
        $playerRoom = null;
        $realtimeEnabled = false;
        $isHost = false;

        if ($existingToken && $existingToken['room_id']) {
            // Player is already in a room, check if it supports realtime
            if ($existingToken['realtime']) {
                $playerRoom = $existingToken['room_id'];
                $realtimeEnabled = true;
                $isHost = (bool)$existingToken['is_host'];
            }
        } else {
            // Check current room status
            $stmt = $pdo->prepare("
                SELECT gr.room_id, gr.realtime, rp.is_host
                FROM room_players rp
                JOIN game_rooms gr ON rp.room_id = gr.room_id
                WHERE rp.player_id = ? AND gr.is_active = TRUE
                LIMIT 1
            ");
            $stmt->execute([$player['id']]);
            $roomInfo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($roomInfo && $roomInfo['realtime']) {
                $playerRoom = $roomInfo['room_id'];
                $realtimeEnabled = true;
                $isHost = (bool)$roomInfo['is_host'];
            }
        }

        if (!$realtimeEnabled) {
            sendResponse([
                'success' => false, 
                'error' => 'Player is not in a realtime-enabled room'
            ], 400);
        }

        // If token exists and is not connected, return existing token
        if ($existingToken && !$existingToken['is_connected']) {
            // Update room info if needed
            if ($existingToken['game_room_id'] !== $playerRoom) {
                $stmt = $pdo->prepare("
                    UPDATE realtime_players SET game_room_id = ? WHERE game_player_id = ?
                ");
                $stmt->execute([$playerRoom, $player['id']]);
            }

            sendResponse([
                'success' => true,
                'token' => $existingToken['token'],
                'player_info' => [
                    'player_id' => $player['id'],
                    'player_name' => $player['player_name'],
                    'room_id' => $playerRoom,
                    'is_host' => $isHost
                ]
            ]);
            return;
        }

        // Drop any existing connection for this player
        $stmt = $pdo->prepare("
            UPDATE realtime_players 
            SET is_connected = FALSE, connection_id = NULL 
            WHERE game_player_id = ? AND is_connected = TRUE
        ");
        $stmt->execute([$player['id']]);

        // Generate new token only if needed
        $token = generateSecureToken();
        
        // Insert new realtime player record
        $stmt = $pdo->prepare("
            INSERT INTO realtime_players 
            (game_player_id, game_id, game_room_id, token, is_connected, created_at)
            VALUES (?, ?, ?, ?, FALSE, NOW())
        ");
        $stmt->execute([$player['id'], $context['api']['id'], $playerRoom, $token]);

        sendResponse([
            'success' => true,
            'token' => $token,
            'player_info' => [
                'player_id' => $player['id'],
                'player_name' => $player['player_name'],
                'room_id' => $playerRoom,
                'is_host' => $isHost
            ]
        ]);

    } catch (Exception $e) {
        error_log("Generate realtime token failed: " . $e->getMessage());
        sendResponse(['success' => false, 'error' => 'Failed to generate realtime token'], 500);
    }
}


// ====================== ROUTING ======================
try {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST' && preg_match('#/token/?$#', $path)) {
        generateToken();
    } else {
        sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
    }
} catch (Exception $e) {
    error_log("Critical error in realtime.php: " . $e->getMessage());
    sendResponse(['success' => false, 'error' => 'Internal server error'], 500);
}
?>
