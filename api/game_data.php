<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Player-Token');
header('Content-Type: application/json');
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Create logs directory if it doesn't exist
if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

// Log the request
error_log("=== New Request ===");
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Headers: " . print_r(getallheaders(), true));

try {
    require_once '../php/config.php';

    // Helper function to send JSON response
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // Helper function to validate API key and return game data
    function validateApiKey($apiKey) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, user_id, game_data FROM api_keys WHERE api_key = ?");
        $stmt->execute([$apiKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Helper function to validate API private key and return game data
    function validateApiKeys($apiKey, $apiPrivateKey) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, user_id, game_data FROM api_keys WHERE api_key = ? AND api_private_key = ?");
        $stmt->execute([$apiKey, $apiPrivateKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Helper function to validate private key and return player data
    function validatePrivateKey($privateKey) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM game_players WHERE private_key = ?");
        $stmt->execute([$privateKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get request method
    $method = $_SERVER['REQUEST_METHOD'];

    // Get API token from query string
    $apiToken = $_GET['api_token'] ?? '';

    // Get API private key from query string
    $apiPrivateKey = $_GET['private_token'] ?? '';

    // Get game player token from query string
    $gamePlayerToken = $_GET['player_token'] ?? '';

    // Get request body
    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    // Get request path for routing
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace('api/game_data.php', '', $path);
    $path = ltrim($path, '/');
    $path = rtrim($path, '/');

    // Route the request
    switch ($method) {
        case 'GET':
            if (empty($apiToken)) {
                sendResponse(['success' => false, 'error' => 'API token is required'], 401);
            }

            // Route: /game_data.php/game/get
            if ($path === 'game/get') {
                // Get game data (requires API key)
                $game = validateApiKey($apiToken);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
                }

                $gameData = (($game['game_data'] ?? '{}') === '{}')
                    ? new stdClass()
                    : json_decode($game['game_data'], true);
                sendResponse([
                    'success' => true,
                    'type' => 'game',
                    'game_id' => $game['id'],
                    'data' => $gameData
                ]);
            }
            // Route: /game_data.php/player/get
            elseif ($path === 'player/get') {
                // Get player data (requires API key, Player private key)
                if (empty($gamePlayerToken)) {
                    sendResponse(['success' => false, 'error' => 'Game player token is required'], 401);
                }

                // Validate API key
                $game = validateApiKey($apiToken);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
                }

                // Validate player token
                $player = validatePrivateKey($gamePlayerToken);
                if (!$player) {
                    sendResponse(['success' => false, 'error' => 'Invalid game player token'], 401);
                }

                // Check if player belongs to this game
                if ($player['game_id'] != $game['id']) {
                    sendResponse(['success' => false, 'error' => 'Player does not belong to this game'], 403);
                }

                // Return player data
                $playerData = json_decode($player['player_data'] ?? '{}', true);
                sendResponse([
                    'success' => true,
                    'type' => 'player',
                    'player_id' => $player['id'],
                    'player_name' => $player['player_name'],
                    'data' => $playerData
                ]);
            }
            else {
                sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
            }
            break;

        case 'PUT':
            if (empty($apiToken)) {
                sendResponse(['success' => false, 'error' => 'API token is required'], 401);
            }

            // Route: /game_data.php/game/update
            if ($path === 'game/update') {
                // Update game data (requires API key, API private key)
                if (empty($apiPrivateKey)) {
                    sendResponse(['success' => false, 'error' => 'API private token is required'], 401);
                }

                // Validate API keys
                $game = validateApiKeys($apiToken, $apiPrivateKey);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token or API private token'], 401);
                }

                // Update game data
                $gameData = json_decode($game['game_data'] ?? '{}', true);
                $updatedData = array_merge($gameData, $input);

                $stmt = $pdo->prepare("UPDATE api_keys SET game_data = ?, updated_at = NOW() WHERE id = ?");
                $gameDataJson = json_encode($updatedData, JSON_UNESCAPED_UNICODE);
                $stmt->execute([$gameDataJson, $game['id']]);

                if ($stmt->rowCount() > 0) {
                    sendResponse([
                        'success' => true,
                        'message' => 'Game data updated successfully',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    sendResponse(['success' => false, 'error' => 'Failed to update game data'], 500);
                }
            }
            // Route: /game_data.php/player/update
            elseif ($path === 'player/update') {
                // Update player data (requires API key, Player private key)
                if (empty($gamePlayerToken)) {
                    sendResponse(['success' => false, 'error' => 'Game player token is required'], 401);
                }

                // Validate API key
                $game = validateApiKey($apiToken);
                if (!$game) {
                    sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
                }

                // Validate player token
                $player = validatePrivateKey($gamePlayerToken);
                if (!$player) {
                    sendResponse(['success' => false, 'error' => 'Invalid game player token'], 401);
                }

                // Check if player belongs to this game
                if ($player['game_id'] != $game['id']) {
                    sendResponse(['success' => false, 'error' => 'Player does not belong to this game'], 403);
                }

                // Update player data
                $playerData = json_decode($player['player_data'] ?? '{}', true);
                $updatedData = array_merge($playerData, $input);

                $stmt = $pdo->prepare("UPDATE game_players SET player_data = ?, updated_at = NOW() WHERE id = ?");
                $playerDataJson = json_encode($updatedData, JSON_UNESCAPED_UNICODE);
                $stmt->execute([$playerDataJson, $player['id']]);

                if ($stmt->rowCount() > 0) {
                    sendResponse([
                        'success' => true,
                        'message' => 'Player data updated successfully',
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    sendResponse(['success' => false, 'error' => 'Failed to update player data'], 500);
                }
            }
            else {
                sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 404);
            }
            break;

        default:
            sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
    }

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    error_log("SQL State: " . $e->getCode());
    sendResponse([
        'success' => false,
        'error' => 'Database error',
        'debug' => [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ], 500);
} catch (Exception $e) {
    error_log("Unexpected Error: " . $e->getMessage());
    error_log("Stack Trace: " . $e->getTraceAsString());
    sendResponse([
        'success' => false,
        'error' => 'An unexpected error occurred',
        'debug' => [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]
    ], 500);
}
?>
