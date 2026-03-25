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

require_once '../php/config.php';

// Helper function to send JSON response
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// ====================== FORMAT HANDLING ======================
$format = strtolower($_GET['format'] ?? 'json');
$isUnity = ($format === 'unity');
// ============================================================

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

function updatePlayerHeartbeat($playerId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE game_players SET last_heartbeat = NOW() WHERE id = ?");
    return $stmt->execute([$playerId]);
}

// Register a new player
function registerPlayer($gameId, $playerName, $playerData = []) {
    global $pdo;
    
    $privateKey = bin2hex(random_bytes(18));
    $playerDataJson = json_encode($playerData, JSON_UNESCAPED_UNICODE);
    
    $stmt = $pdo->prepare("INSERT INTO game_players (game_id, player_name, private_key, player_data) VALUES (?, ?, ?, ?)");
    $stmt->execute([$gameId, $playerName, $privateKey, $playerDataJson]);
    
    if ($stmt->rowCount() > 0) {
        return [
            'success'     => true,
            'player_id'   => $pdo->lastInsertId(),
            'private_key' => $privateKey,
            'player_name' => $playerName,
            'game_id'     => $gameId
        ];
    }
    return false;
}

// ====================== MAIN REQUEST HANDLER ======================
try {
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = parse_url($requestUri, PHP_URL_PATH);
    $pathParts = explode('/', trim($path, '/'));
    $endpoint = end($pathParts);
    
    $method = $_SERVER['REQUEST_METHOD'];
    
    $apiToken       = $_GET['api_token'] ?? $_SERVER['HTTP_X_API_TOKEN'] ?? '';
    $apiPrivateToken = $_GET['private_token'] ?? $_SERVER['HTTP_X_API_PRIVATE_TOKEN'] ?? '';
    $gamePlayerToken = $_GET['player_token'] ?? $_SERVER['HTTP_X_GAME_PLAYER_TOKEN'] ?? '';
    
    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    switch ($endpoint) {
        
        // ====================== REGISTER ======================
        case 'register':
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken)) {
                sendResponse(['success' => false, 'error' => 'API token is required'], 401);
            }
            
            $game = validateApiKey($apiToken);
            if (!$game) {
                sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
            }
            
            $playerName = $input['player_name'] ?? '';
            if (empty($playerName)) {
                sendResponse(['success' => false, 'error' => 'Player name is required'], 400);
            }
            
            $result = registerPlayer($game['id'], $playerName, $input['player_data'] ?? []);
            
            if ($result && $result['success']) {
                sendResponse($result);
            } else {
                sendResponse(['success' => false, 'error' => 'Failed to register player'], 500);
            }
            break;

        // ====================== LOGIN ======================
        case 'login':
            if ($method !== 'PUT') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($gamePlayerToken)) {
                sendResponse(['success' => false, 'error' => 'API token and game player token are required'], 401);
            }
            
            $game = validateApiKey($apiToken);
            if (!$game) {
                sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
            }
            
            $player = validatePrivateKey($gamePlayerToken);
            if (!$player || $player['game_id'] != $game['id']) {
                sendResponse(['success' => false, 'error' => 'Invalid game player token'], 403);
            }
            
            // Update last login
            $pdo->prepare("UPDATE game_players SET last_login = NOW() WHERE id = ?")
                ->execute([$player['id']]);
            
            // Prepare base player data (keep all fields from DB except private_key)
            unset($player['private_key']);
            
            $playerData = json_decode($player['player_data'] ?? '{}', true) ?: new stdClass();
            
            // Build response according to format
            if ($isUnity) {
                $response = [
                    'success' => true,
                    'player'  => [
                        'id'              => (int)$player['id'],
                        'game_id'         => (int)$player['game_id'],
                        'player_name'     => $player['player_name'],
                        'player_data_json'=> json_encode($playerData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                        'is_active'       => (bool)$player['is_active'],
                        'last_login'      => $player['last_login'],
                        'last_logout'     => $player['last_logout'],
                        'last_heartbeat'  => $player['last_heartbeat'],
                        'created_at'      => $player['created_at']
                    ]
                ];
            } else {
                // Exact structure as .bak (and .NET logs)
                $response = [
                    'success' => true,
                    'player'  => [
                        'id'             => (int)$player['id'],
                        'game_id'        => (int)$player['game_id'],
                        'player_name'    => $player['player_name'],
                        'player_data'    => $playerData,
                        'is_active'      => (bool)$player['is_active'],
                        'last_login'     => $player['last_login'],
                        'last_logout'    => $player['last_logout'],
                        'last_heartbeat' => $player['last_heartbeat'],
                        'created_at'     => $player['created_at']
                    ]
                ];
            }
            
            sendResponse($response);
            break;

        // ====================== HEARTBEAT ======================
        case 'heartbeat':
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($gamePlayerToken)) {
                sendResponse(['success' => false, 'error' => 'API token and game player token are required'], 401);
            }
            
            $game = validateApiKey($apiToken);
            if (!$game) sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
            
            $player = validatePrivateKey($gamePlayerToken);
            if (!$player || $player['game_id'] != $game['id']) {
                sendResponse(['success' => false, 'error' => 'Invalid player token'], 403);
            }
            
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

        // ====================== LOGOUT ======================
        case 'logout':
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($gamePlayerToken)) {
                sendResponse(['success' => false, 'error' => 'API token and game player token are required'], 401);
            }
            
            $game = validateApiKey($apiToken);
            if (!$game) sendResponse(['success' => false, 'error' => 'Invalid API token'], 401);
            
            $player = validatePrivateKey($gamePlayerToken);
            if (!$player || $player['game_id'] != $game['id']) {
                sendResponse(['success' => false, 'error' => 'Invalid player token'], 403);
            }
            
            $pdo->prepare("UPDATE game_players SET last_logout = NOW(), is_active = 0 WHERE id = ?")
                ->execute([$player['id']]);
            
            sendResponse([
                'success' => true,
                'message' => 'Player logged out successfully',
                'last_logout' => date('Y-m-d H:i:s')
            ]);
            break;

        // ====================== LIST ======================
        case 'list':
            if ($method !== 'GET') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($apiPrivateToken)) {
                sendResponse(['success' => false, 'error' => 'API token and private token are required'], 401);
            }
            
            $game = validateApiKeys($apiToken, $apiPrivateToken);
            if (!$game) {
                sendResponse(['success' => false, 'error' => 'Invalid API credentials'], 401);
            }
            
            $stmt = $pdo->prepare("SELECT id, player_name, is_active, last_login, last_logout, last_heartbeat, created_at 
                                   FROM game_players WHERE game_id = ?");
            $stmt->execute([$game['id']]);
            $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            sendResponse([
                'success' => true,
                'count'   => count($players),
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
        'error'   => 'Internal server error'
    ], 500);
}
?>