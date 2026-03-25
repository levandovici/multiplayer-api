<?php
// ====================== CORS & HEADERS ======================
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Player-Token, X-Game-Player-Token');
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Create logs directory if needed
if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

// Log the request
error_log("=== Game Data Request ===");
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Method: " . $_SERVER['REQUEST_METHOD']);

try {
    require_once '../php/config.php';

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

    // ====================== VALIDATION HELPERS ======================
    function validateApiKey($apiKey) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, user_id, game_data FROM api_keys WHERE api_key = ?");
        $stmt->execute([$apiKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function validateApiKeys($apiKey, $apiPrivateKey) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, user_id, game_data FROM api_keys WHERE api_key = ? AND api_private_key = ?");
        $stmt->execute([$apiKey, $apiPrivateKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function validatePrivateKey($privateKey) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM game_players WHERE private_key = ?");
        $stmt->execute([$privateKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ====================== MAIN LOGIC ======================
    $method = $_SERVER['REQUEST_METHOD'];

    $apiToken       = $_GET['api_token'] ?? '';
    $apiPrivateKey  = $_GET['private_token'] ?? '';
    $gamePlayerToken = $_GET['player_token'] ?? '';

    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    // Routing path
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace('api/game_data.php', '', $path);
    $path = ltrim($path, '/');
    $path = rtrim($path, '/');

    switch ($method) {
        case 'GET':
            if (empty($apiToken)) {
                sendResponse(['success' => false, 'error' => 'API token is required'], 401);
            }

            // ====================== GAME GET ======================
            if ($path === 'game/get') {
                $game = validateApiKey($apiToken);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
                }

                $gameData = (($game['game_data'] ?? '{}') === '{}')
                    ? new stdClass()
                    : json_decode($game['game_data'], true);

                $response = [
                    'success' => true,
                    'type'    => 'game',
                    'game_id' => (int)$game['id']
                ];

                if ($isUnity) {
                    $response['data_json'] = json_encode($gameData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    $response['data'] = $gameData;
                }

                sendResponse($response);
            }

            // ====================== PLAYER GET ======================
            elseif ($path === 'player/get') {
                if (empty($gamePlayerToken)) {
                    sendResponse(['success' => false, 'error' => 'Game player token is required'], 401);
                }

                $game = validateApiKey($apiToken);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
                }

                $player = validatePrivateKey($gamePlayerToken);
                if (!$player) {
                    sendResponse(['success' => false, 'error' => 'Invalid game player token'], 401);
                }

                if ($player['game_id'] != $game['id']) {
                    sendResponse(['success' => false, 'error' => 'Player does not belong to this game'], 403);
                }

                $playerData = json_decode($player['player_data'] ?? '{}', true) ?: new stdClass();

                $response = [
                    'success'     => true,
                    'type'        => 'player',
                    'player_id'   => (int)$player['id'],
                    'player_name' => $player['player_name']
                ];

                if ($isUnity) {
                    $response['data_json'] = json_encode($playerData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    $response['data'] = $playerData;
                }

                sendResponse($response);
            }
            else {
                sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
            }
            break;

        case 'PUT':
            if (empty($apiToken)) {
                sendResponse(['success' => false, 'error' => 'API token is required'], 401);
            }

            // ====================== GAME UPDATE ======================
            if ($path === 'game/update') {
                if (empty($apiPrivateKey)) {
                    sendResponse(['success' => false, 'error' => 'API private token is required'], 401);
                }

                $game = validateApiKeys($apiToken, $apiPrivateKey);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token or private token'], 401);
                }

                $gameData = json_decode($game['game_data'] ?? '{}', true);
                $updatedData = array_merge($gameData, $input);

                $stmt = $pdo->prepare("UPDATE api_keys SET game_data = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([json_encode($updatedData, JSON_UNESCAPED_UNICODE), $game['id']]);

                sendResponse([
                    'success'    => true,
                    'message'    => 'Game data updated successfully',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            // ====================== PLAYER UPDATE ======================
            elseif ($path === 'player/update') {
                if (empty($gamePlayerToken)) {
                    sendResponse(['success' => false, 'error' => 'Game player token is required'], 401);
                }

                $game = validateApiKey($apiToken);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
                }

                $player = validatePrivateKey($gamePlayerToken);
                if (!$player || $player['game_id'] != $game['id']) {
                    sendResponse(['success' => false, 'error' => 'Invalid player or does not belong to game'], 403);
                }

                $playerData = json_decode($player['player_data'] ?? '{}', true);
                $updatedData = array_merge($playerData, $input);

                $stmt = $pdo->prepare("UPDATE game_players SET player_data = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([json_encode($updatedData, JSON_UNESCAPED_UNICODE), $player['id']]);

                sendResponse([
                    'success'    => true,
                    'message'    => 'Player data updated successfully',
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
            else {
                sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
            }
            break;

        default:
            sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
    }

} catch (PDOException $e) {
    error_log("Database Error in game_data.php: " . $e->getMessage());
    sendResponse([
        'success' => false,
        'error'   => 'Database error'
    ], 500);
} catch (Exception $e) {
    error_log("Unexpected Error in game_data.php: " . $e->getMessage());
    sendResponse([
        'success' => false,
        'error'   => 'An unexpected error occurred'
    ], 500);
}
?>