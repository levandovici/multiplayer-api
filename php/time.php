<?php
// time.php  – place it in the root of your site (or any folder you like)

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
error_log("=== Time API Request ===");
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Method: " . $_SERVER['REQUEST_METHOD']);
error_log("Headers: " . print_r(getallheaders(), true));

// ... [previous code remains the same until line 20]

try {
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');   // allow Unity WebGL
    require_once 'config.php';

    // Helper function to send JSON response
    function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    // Helper function to validate API key
    function validateApiKey($apiKey) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT id, user_id, game_data FROM api_keys WHERE api_key = ?");
        $stmt->execute([$apiKey]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get request method
    $method = $_SERVER['REQUEST_METHOD'];

    // Only allow GET requests
    if ($method !== 'GET') {
        sendResponse([
            'success' => false,
            'error' => 'Method not allowed',
            'allowed_methods' => ['GET']
        ], 405);
    }

    // Get API token from query string
    $apiToken = $_GET['api_token'] ?? '';
    if (empty($apiToken)) {
        sendResponse([
            'success' => false,
            'error' => 'API key is required',
            'hint' => 'Add ?api_token=YOUR_API_KEY to your request'
        ], 401);
    }

    // Get UTC offset from query string (e.g., ?utc+1 or ?utc-2)
    $utcOffset = 0;
    if (isset($_GET['utc'])) {
        $utcParam = trim(preg_replace('/\s+/', '', $_GET['utc']));

        if (preg_match('/^([+-]?)(\d+)$/', $utcParam, $matches)) {
            $sign = $matches[1] === '-' ? -1 : 1;
            $hours = (int)$matches[2];
            $utcOffset = $sign * $hours;
        }
    }

    // Validate API key
    $keyData = validateApiKey($apiToken);
    if (!$keyData) {
        sendResponse([
            'success' => false,
            'error' => 'Invalid API key',
            'hint' => 'Please check your API key and try again'
        ], 403);
    }

    // Set timezone to UTC
    date_default_timezone_set('UTC');
    $timestamp = time();
    
    // Apply UTC offset if provided
    if ($utcOffset !== 0) {
        $timestamp += ($utcOffset * 3600); // Convert hours to seconds
    }
    
    $utc = date('c', $timestamp); // Use offset-adjusted timestamp with proper timezone
    $readable = gmdate('Y-m-d H:i:s', $timestamp) . ' UTC';
    
    // Add offset info to response if used
    $offsetInfo = [];
    if ($utcOffset !== 0) {
        $offsetInfo = [
            'offset_hours' => $utcOffset,
            'offset_string' => ($utcOffset >= 0 ? '+' : '') . $utcOffset,
            'original_utc' => gmdate('c'),
            'original_timestamp' => time()
        ];
    }

    // Log successful response
    error_log("Time API Response for user_id: " . $keyData['user_id']);

    // Return time data
    $response = [
        'success' => true,
        'utc' => $utc,
        'timestamp' => $timestamp,
        'readable' => $readable
    ];
    
    // Add offset info if used
    if (!empty($offsetInfo)) {
        $response['offset'] = $offsetInfo;
    }
    
    sendResponse($response);

} catch (Exception $e) {
    error_log("Time API Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    sendResponse([
        'success' => false,
        'error' => 'Internal server error',
        'message' => $e->getMessage()
    ], 500);
}