<?php
// ====================== CORS & HEADERS ======================
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Player-Token, X-Game-Player-Token');
header('Content-Type: application/json');

// Enable error reporting - disabled display in production for security
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Create logs directory if needed
if (!is_dir(__DIR__ . '/../logs')) {
    @mkdir(__DIR__ . '/../logs', 0777, true);
}

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

// Validate API key
function validateApiKey($apiKey) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, user_id FROM api_keys WHERE api_key = ?");
    $stmt->execute([$apiKey]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

try {
    // Only POST allowed
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(['success' => false, 'error' => 'Method not allowed. Use POST.'], 405);
    }

    $apiToken = $_GET['api_token'] ?? '';
    if (empty($apiToken)) {
        sendResponse(['success' => false, 'error' => 'api_token is required'], 400);
    }

    $game = validateApiKey($apiToken);
    if (!$game) {
        sendResponse(['success' => false, 'error' => 'Invalid or expired api_token'], 401);
    }

    $gameId = (int)$game['id'];

    // Read JSON body
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($input)) {
        sendResponse(['success' => false, 'error' => 'Invalid JSON body'], 400);
    }

    // Required: sort_by array
    $sortBy = $input['sort_by'] ?? null;
    if (!is_array($sortBy) || empty($sortBy)) {
        sendResponse(['success' => false, 'error' => 'sort_by must be a non-empty array of field names'], 400);
    }

    // Optional: limit (default 10, max 1000)
    $limit = isset($input['limit']) ? (int)$input['limit'] : 10;
    if ($limit < 1 || $limit > 1000) {
        sendResponse(['success' => false, 'error' => 'limit must be between 1 and 1000'], 400);
    }

    // Build ORDER BY clause safely
    $orderByParts = [];
    foreach ($sortBy as $field) {
        $cleanField = preg_replace('/[^a-zA-Z0-9_]/', '', trim($field));
        if ($cleanField === '') continue;

        $orderByParts[] = "CAST(COALESCE(JSON_EXTRACT(gp.player_data, '$.{$cleanField}'), 0) AS UNSIGNED) DESC";
    }

    if (empty($orderByParts)) {
        sendResponse(['success' => false, 'error' => 'No valid sort fields provided after sanitization'], 400);
    }

    $orderByClause = "ORDER BY " . implode(', ', $orderByParts);

    // Final query
    $sql = "
        SELECT 
            gp.id AS player_id,
            gp.player_name,
            gp.player_data,
            gp.game_id
        FROM game_players gp
        WHERE gp.game_id = ?
          AND JSON_EXTRACT(gp.player_data, '$.level') IS NOT NULL
        {$orderByClause}
        LIMIT ?
    ";

    $params = [$gameId, $limit];

    error_log("Leaderboard SQL: " . $sql);
    error_log("Params: " . json_encode($params));

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build leaderboard
    $leaderboard = [];
    $rank = 1;

    foreach ($players as $row) {
        // Decode player_data safely - always work with array in PHP
        $playerData = json_decode($row['player_data'] ?? '{}', true);
        
        // If decoding failed or returned non-array, use empty array
        if (!is_array($playerData)) {
            $playerData = [];
        }

        $entry = [
            'rank'        => $rank++,
            'player_id'   => (int)$row['player_id'],
            'player_name' => $row['player_name']
        ];

        if ($isUnity) {
            // For Unity: send as JSON string and force object {} instead of array []
            $entry['player_data_json'] = json_encode(
                (object)$playerData, 
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        } else {
            // Normal API: send as object/array (standard for JSON)
            $entry['player_data'] = $playerData;
        }

        $leaderboard[] = $entry;
    }

    $response = [
        'success'     => true,
        'leaderboard' => $leaderboard,
        'total'       => count($leaderboard),
        'sort_by'     => $sortBy,
        'limit'       => $limit,
    ];

    sendResponse($response);

} catch (PDOException $e) {
    error_log("DB Error in leaderboard.php: " . $e->getMessage());
    sendResponse([
        'success' => false,
        'error'   => 'Database error'
    ], 500);

} catch (Exception $e) {
    error_log("Unexpected error in leaderboard.php: " . $e->getMessage());
    sendResponse([
        'success' => false,
        'error'   => 'Server error'
    ], 500);
}
?>