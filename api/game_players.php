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

// Register a new player - supports both array and JSON string
function registerPlayer($gameId, $playerName, $playerData = []) {
    global $pdo;
    
    $privateKey = bin2hex(random_bytes(18));
    
    // Normalize playerData to JSON string
    if (is_string($playerData)) {
        $playerDataJson = $playerData !== '' ? $playerData : '{}';
    } else {
        $playerDataArray = is_array($playerData) ? $playerData : [];
        $playerDataJson = json_encode($playerDataArray, JSON_UNESCAPED_UNICODE);
    }
    
    $stmt = $pdo->prepare("INSERT INTO game_players (game_id, player_name, private_key, player_data) VALUES (?, ?, ?, ?)");
    $stmt->execute([$gameId, $playerName, $privateKey, $playerDataJson]);
    
    if ($stmt->rowCount() > 0) {
        return [
            'success'     => true,
            'player_id'   => (int)$pdo->lastInsertId(),
            'private_key' => $privateKey,
            'player_name' => $playerName,
            'game_id'     => (int)$gameId
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
            
            if (!isset($input['player_name']) || empty($input['player_name'])) {
                sendResponse(['success' => false, 'error' => 'Player name is required'], 400);
            }

            $playerName = $input['player_name'];
            
            if($isUnity)
            {
                // For Unity (JSON string)
                $result = registerPlayer($game['id'], $playerName, $input['player_data_json'] ?? '');
            }
            else
            {
                // For normal web requests (array)
                $result = registerPlayer($game['id'], $playerName, $input['player_data'] ?? []);
            }
            
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
            
            // Check if player is banned
            $banCheck = checkPlayerBan($player['id'], $game['id']);
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
                        'is_online'       => (bool)$player['is_online'],
                        'last_login'      => isoUtc($player['last_login']),
                        'last_logout'     => isoUtc($player['last_logout']),
                        'last_heartbeat'  => isoUtc($player['last_heartbeat']),
                        'created_at'      => isoUtc($player['created_at'])
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
                        'is_online'      => (bool)$player['is_online'],
                        'last_login'     => isoUtc($player['last_login']),
                        'last_logout'    => isoUtc($player['last_logout']),
                        'last_heartbeat' => isoUtc($player['last_heartbeat']),
                        'created_at'     => isoUtc($player['created_at'])
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
            
            // Check if player is banned
            $banCheck = checkPlayerBan($player['id'], $game['id']);
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
            
            if (updatePlayerHeartbeat($player['id'])) {
                sendResponse([
                    'success' => true,
                    'message' => 'Heartbeat updated',
                    'last_heartbeat' => isoUtc(date('Y-m-d H:i:s'))
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
            
            // Check if player is banned
            $banCheck = checkPlayerBan($player['id'], $game['id']);
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
            
            $pdo->prepare("UPDATE game_players SET last_logout = NOW(), is_online = FALSE WHERE id = ?")
                ->execute([$player['id']]);
            
            sendResponse([
                'success' => true,
                'message' => 'Player logged out successfully',
                'last_logout' => isoUtc(date('Y-m-d H:i:s'))
            ]);
            break;

        // ====================== RENAME ======================
        case 'rename':
            if ($method !== 'PUT') {
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
            
            // Check if player is banned
            $banCheck = checkPlayerBan($player['id'], $game['id']);
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
            
            if (!isset($input['new_name']) || empty($input['new_name'])) {
                sendResponse(['success' => false, 'error' => 'New name is required'], 400);
            }
            
            $newName = trim($input['new_name']);
            
            if (strlen($newName) < 2 || strlen($newName) > 50) {
                sendResponse(['success' => false, 'error' => 'Player name must be between 2 and 50 characters'], 400);
            }
            
            $stmt = $pdo->prepare("UPDATE game_players SET player_name = ? WHERE id = ?");
            if ($stmt->execute([$newName, $player['id']])) {
                sendResponse([
                    'success' => true,
                    'message' => 'Player name updated successfully',
                    'new_name' => $newName,
                    'player_id' => (int)$player['id']
                ]);
            } else {
                sendResponse(['success' => false, 'error' => 'Failed to update player name'], 500);
            }
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
            
            $stmt = $pdo->prepare("SELECT id, player_name, is_online, last_login, last_logout, last_heartbeat, created_at 
                                   FROM game_players WHERE game_id = ?");
            $stmt->execute([$game['id']]);
            $players = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($players as &$player) {
                $player['id']        = (int)$player['id'];
                $player['is_online'] = (bool)$player['is_online'];

                $player['last_login']     = isoUtc($player['last_login']);
                $player['last_logout']    = isoUtc($player['last_logout']);
                $player['last_heartbeat'] = isoUtc($player['last_heartbeat']);
                $player['created_at']     = isoUtc($player['created_at']);
            }

            sendResponse([
                'success' => true,
                'count'   => count($players),
                'players' => $players
            ]);
            break;

        // ====================== BAN ======================
        case 'ban':
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($apiPrivateToken)) {
                sendResponse(['success' => false, 'error' => 'API token and private token are required'], 401);
            }
            
            $game = validateApiKeys($apiToken, $apiPrivateToken);
            if (!$game) {
                sendResponse(['success' => false, 'error' => 'Invalid API credentials'], 401);
            }
            
            if (!isset($input['player_id']) || empty($input['player_id'])) {
                sendResponse(['success' => false, 'error' => 'player_id is required'], 400);
            }
            
            if (!isset($input['ban_duration']) || empty($input['ban_duration'])) {
                sendResponse(['success' => false, 'error' => 'ban_duration is required (hour, day, week, month, quarter, year, forever)'], 400);
            }
            
            $validDurations = ['hour', 'day', 'week', 'month', 'quarter', 'year', 'forever'];
            if (!in_array($input['ban_duration'], $validDurations)) {
                sendResponse(['success' => false, 'error' => 'Invalid ban_duration. Must be one of: hour, day, week, month, quarter, year, forever'], 400);
            }
            
            $banDuration = $input['ban_duration'];
            $banReason = $input['ban_reason'] ?? null;
            
            // Get player to ban
            $stmt = $pdo->prepare("SELECT id, game_id FROM game_players WHERE id = ? AND game_id = ?");
            $stmt->execute([$input['player_id'], $game['id']]);
            $playerToBan = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$playerToBan) {
                sendResponse(['success' => false, 'error' => 'Player not found or does not belong to this game'], 404);
            }
            
            // Calculate ban expiration
            $bannedUntil = calculateBanExpiration($banDuration);
            
            // Deactivate any existing active bans for this player
            $pdo->prepare("UPDATE player_bans SET is_active = FALSE WHERE player_id = ? AND game_id = ? AND is_active = TRUE")
                ->execute([$playerToBan['id'], $game['id']]);
            
            // Insert new ban
            $banId = bin2hex(random_bytes(16));
            $stmt = $pdo->prepare("
                INSERT INTO player_bans (ban_id, player_id, game_id, ban_duration, ban_reason, banned_at, banned_until, is_active)
                VALUES (?, ?, ?, ?, ?, NOW(), ?, TRUE)
            ");
            $stmt->execute([$banId, $playerToBan['id'], $game['id'], $banDuration, $banReason, $bannedUntil]);
            
            sendResponse([
                'success' => true,
                'message' => 'Player banned successfully',
                'ban_id' => $banId,
                'player_id' => (int)$playerToBan['id'],
                'ban_duration' => $banDuration,
                'ban_reason' => $banReason,
                'banned_until' => $bannedUntil ? isoUtc($bannedUntil) : null
            ]);
            break;

        // ====================== UNBAN ======================
        case 'unban':
            if ($method !== 'POST') {
                sendResponse(['success' => false, 'error' => 'Method not allowed'], 405);
            }
            
            if (empty($apiToken) || empty($apiPrivateToken)) {
                sendResponse(['success' => false, 'error' => 'API token and private token are required'], 401);
            }
            
            $game = validateApiKeys($apiToken, $apiPrivateToken);
            if (!$game) {
                sendResponse(['success' => false, 'error' => 'Invalid API credentials'], 401);
            }
            
            if (!isset($input['player_id']) || empty($input['player_id'])) {
                sendResponse(['success' => false, 'error' => 'player_id is required'], 400);
            }
            
            // Get player to unban
            $stmt = $pdo->prepare("SELECT id, game_id FROM game_players WHERE id = ? AND game_id = ?");
            $stmt->execute([$input['player_id'], $game['id']]);
            $playerToUnban = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$playerToUnban) {
                sendResponse(['success' => false, 'error' => 'Player not found or does not belong to this game'], 404);
            }
            
            // Deactivate all active bans for this player
            $stmt = $pdo->prepare("UPDATE player_bans SET is_active = FALSE WHERE player_id = ? AND game_id = ? AND is_active = TRUE");
            $stmt->execute([$playerToUnban['id'], $game['id']]);
            
            $affectedRows = $stmt->rowCount();
            
            sendResponse([
                'success' => true,
                'message' => $affectedRows > 0 ? 'Player unbanned successfully' : 'No active bans found for this player',
                'player_id' => (int)$playerToUnban['id'],
                'bans_removed' => $affectedRows
            ]);
            break;
            
        default:
            sendResponse(['success' => false, 'error' => 'Invalid endpoint'], 400);
    }
    
} catch (Exception $e) {
    error_log('Error in game_players.php: ' . $e->getMessage());
    sendResponse([
        'success' => false,
        'error'   => 'Internal server error'
    ], 500);
}
?>