<?php
header('Content-Type: application/json');
require_once '../php/config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper function to send JSON response
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
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

// Helper function to update player heartbeat
function updatePlayerHeartbeat($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE game_players SET last_heartbeat = NOW() WHERE id = ?");
    return $stmt->execute([$playerId]);
}

// Register a new player for a game
function registerPlayer($gameId, $playerName, $playerData = []) {
    global $pdo;
    
    // Generate a secure private key for the player
    $privateKey = bin2hex(random_bytes(18));
    $playerDataJson = json_encode($playerData, JSON_UNESCAPED_UNICODE);
    
    $stmt = $pdo->prepare("INSERT INTO game_players (game_id, player_name, private_key, player_data) VALUES (?, ?, ?, ?)");
    $stmt->execute([$gameId, $playerName, $privateKey, $playerDataJson]);
    
    if ($stmt->rowCount() > 0) {
        return [
            'success' => true,
            'player_id' => $pdo->lastInsertId(),
            'private_key' => $privateKey,
            'player_name' => $playerName,
            'game_id' => $gameId
        ];
    }
    
    return false;
}

// Main request handler
try {
    // Get the path from URL for routing
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = parse_url($requestUri, PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    
    // Get the endpoint (last part of the path)
    $endpoint = end($pathParts);
    
    // Get request method
    $method = $_SERVER['REQUEST_METHOD'];
    
    // Get API token from query string or headers
    $apiToken = $_GET['api_token'] ?? $_SERVER['HTTP_X_API_TOKEN'] ?? '';

    // Get API private token from query string or headers
    $apiPrivateToken = $_GET['api_private_token'] ?? $_SERVER['HTTP_X_API_PRIVATE_TOKEN'] ?? '';
    
    // Get game player token from query string or headers
    $gamePlayerToken = $_GET['game_player_token'] ?? $_SERVER['HTTP_X_GAME_PLAYER_TOKEN'] ?? '';
    
    // Get request body
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    
    // Route the request based on endpoint
    switch ($endpoint) {
        case 'register':
            // Register new player for a game
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken)) {
                sendResponse(['success' => false, 'error' => 'API token is required'], 401);
            }
            
            // Validate API key
            $game = validateApiKey($apiToken);
            if (!$game) {
                sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
            }
            
            // Get player name from input
            $playerName = $input['player_name'] ?? '';
            if (empty($playerName)) {
                sendResponse(['success' => false, 'error' => 'Player name is required'], 400);
            }
            
            // Register the player
            $result = registerPlayer($game['id'], $playerName, $input['player_data'] ?? []);
            if ($result['success']) {
                sendResponse($result);
            } else {
                sendResponse($result, 500);
            }
            break;
            
        case 'login':
            // Authenticate player with private key
            if ($method !== 'PUT') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($gamePlayerToken)) {
                sendResponse(['success' => false, 'error' => 'API token and game player token are required'], 401);
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
            
            // Update last login time
            $stmt = $pdo->prepare("UPDATE game_players SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$player['id']]);
            
            // Return player data (excluding sensitive fields)
            unset($player['private_key']);
            $player['player_data'] = json_decode($player['player_data'] ?? '{}', true);
            
            sendResponse([
                'success' => true,
                'player' => $player
            ]);
            break;
            
        case 'heartbeat':
            // Update player heartbeat
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($gamePlayerToken)) {
                sendResponse(['success' => false, 'error' => 'API token and game player token are required'], 401);
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
            
            // Update heartbeat
            if (updatePlayerHeartbeat($player['id'])) {
                sendResponse([
                    'success' => true,
                    'message' => 'Heartbeat updated',
                    'last_heartbeat' => date('Y-m-d H:i:s')
                ]);
            } else {
                sendResponse(['success' => false, 'error' => 'Failed to update heartbeat'], 500);
            }
            break;
            
        case 'logout':
            // Logout player and update last_logout
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($gamePlayerToken)) {
                sendResponse(['success' => false, 'error' => 'API token and game player token are required'], 401);
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
            
            // Update last logout time and set is_active to 0
            $stmt = $pdo->prepare("UPDATE game_players SET last_logout = NOW(), is_active = 0 WHERE id = ?");
            $stmt->execute([$player['id']]);
            
            sendResponse([
                'success' => true,
                'message' => 'Player logged out successfully',
                'last_logout' => date('Y-m-d H:i:s')
            ]);
            break;
            
        case 'list':
            // List all players for a game
            if ($method !== 'GET') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken)) {
                sendResponse(['success' => false, 'error' => 'API token is required'], 401);
            }

            if(empty($apiPrivateToken)) {
                sendResponse(['success' => false, 'error' => 'API private token is required'], 401);
            }
            
            // Validate API keys
            $game = validateApiKeys($apiToken, $apiPrivateToken);
            if (!$game) {
                sendResponse(['success' => false, 'error' => 'Invalid API token or API private token'], 401);
            }
            
            // Get all players for this game
            $stmt = $pdo->prepare("SELECT id, player_name, is_active, last_login, last_logout, last_heartbeat, created_at FROM game_players WHERE game_id = ?");
            $stmt->execute([$game['id']]);
            $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            sendResponse([
                'success' => true,
                'count' => count($players),
                'players' => $players
            ]);
            break;
            
        default:
            sendResponse(['success' => false, 'error' => 'Endpoint not found'], 404);
    }
    
} catch (Exception $e) {
    error_log('Error in game_players.php: ' . $e->getMessage());
    sendResponse([
        'success' => false,
        'error' => 'Internal server error',
        'debug' => (ENVIRONMENT === 'development') ? $e->getMessage() : null
    ], 500);
}
?>
