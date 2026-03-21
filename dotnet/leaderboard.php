<?php
// Enable error reporting (keep in dev, consider disabling in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Create logs directory if needed
if (!is_dir(__DIR__ . '/../logs')) {
    @mkdir(__DIR__ . '/../logs', 0777, true);
}

header('Content-Type: application/json');

require_once '../php/config.php';

function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

// Validate API key and get associated game
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

    // Required: sortBy array
    $sortBy = $input['sortBy'] ?? null;
    if (!is_array($sortBy) || empty($sortBy)) {
        sendResponse(['success' => false, 'error' => 'sortBy must be a non-empty array of field names'], 400);
    }

    // Optional: limit (default 10, max 1000)
    $limit = isset($input['limit']) ? (int)$input['limit'] : 10;
    if ($limit < 1 || $limit > 1000) {
        sendResponse(['success' => false, 'error' => 'limit must be between 1 and 1000'], 400);
    }

    // Build ORDER BY clause safely
    $orderByParts = [];
    foreach ($sortBy as $field) {
        // Very strict sanitization – only allow alphanumeric + underscore
        $cleanField = preg_replace('/[^a-zA-Z0-9_]/', '', trim($field));
        if ($cleanField === '') continue;

        // We sort DESC by default (typical for leaderboards)
        $orderByParts[] = "CAST(COALESCE(JSON_EXTRACT(gp.player_data, '$.{$cleanField}'), 0) AS UNSIGNED) DESC";
    }

    if (empty($orderByParts)) {
        sendResponse(['success' => false, 'error' => 'No valid sort fields provided after sanitization'], 400);
    }

    $orderByClause = "ORDER BY " . implode(', ', $orderByParts);

    // Final query – game_id is ALWAYS from the token, exclude players without level
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

    // Build response with ranks
    $leaderboard = [];
    $rank = 1;

    foreach ($players as $row) {
        $playerData = json_decode($row['player_data'] ?? '{}', true) ?: [];

        $leaderboard[] = [
            'rank'       => $rank++,
            'player_id'  => (int)$row['player_id'],
            'player_name'=> $row['player_name'],
            'player_data'=> $playerData,
        ];
    }

    sendResponse([
        'success'     => true,
        'leaderboard' => $leaderboard,
        'total'       => count($leaderboard),
        'sort_by'     => $sortBy,
        'limit'       => $limit,
    ]);

} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    error_log("SQL State: " . $e->getCode());

    sendResponse([
        'success' => false,
        'error'   => 'Database error',
        // Remove 'debug' in production!
        'debug'   => [
            'message' => $e->getMessage(),
            'code'    => $e->getCode(),
        ]
    ], 500);

} catch (Exception $e) {
    error_log("Unexpected: " . $e->getMessage());
    sendResponse([
        'success' => false,
        'error'   => 'Server error',
        // Remove 'debug' in production!
        'debug'   => $e->getMessage()
    ], 500);
}