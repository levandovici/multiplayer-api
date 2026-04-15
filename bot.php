<?php
require_once 'php/config.php';

// Enable error logging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/bot.log');

// Log incoming requests
$logFile = __DIR__ . '/bot.log';
$timestamp = date('Y-m-d H:i:s');

// Get the raw POST data
$input = file_get_contents('php://input');
$logMessage = "[{$timestamp}] [INFO] Webhook received - Raw input length: " . strlen($input) . " bytes" . PHP_EOL;
file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

// Log the full input for debugging
$logMessage = "[{$timestamp}] [DEBUG] Raw input: {$input}" . PHP_EOL;
file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

// Parse the update
$update = json_decode($input, true);

if (!$update) {
    $logMessage = "[{$timestamp}] [ERROR] Failed to parse JSON input" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    http_response_code(400);
    echo 'Invalid JSON';
    exit;
}

$logMessage = "[{$timestamp}] [INFO] Successfully parsed update" . PHP_EOL;
file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

// Handle the update
handleTelegramUpdate($update);

// Respond to Telegram to confirm receipt
http_response_code(200);
echo 'OK';

function handleTelegramUpdate($update) {
    global $logFile, $timestamp;
    
    $logMessage = "[{$timestamp}] [INFO] Processing update" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    // Handle both regular messages and channel posts
    $message = null;
    $messageType = '';
    
    if (isset($update['message'])) {
        $message = $update['message'];
        $messageType = 'message';
    } elseif (isset($update['channel_post'])) {
        $message = $update['channel_post'];
        $messageType = 'channel_post';
    } else {
        $logMessage = "[{$timestamp}] [DEBUG] No message or channel_post in update" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        return;
    }
    
    $chatId = $message['chat']['id'] ?? 'N/A';
    $chatType = $message['chat']['type'] ?? 'N/A';
    $chatTitle = $message['chat']['title'] ?? $message['chat']['username'] ?? 'N/A';
    $text = $message['text'] ?? 'No text';
    $messageId = $message['message_id'] ?? 'N/A';
    $date = $message['date'] ?? 'N/A';
    $fromUser = $message['from']['username'] ?? $message['from']['first_name'] ?? 'Channel Post';
    
    $logMessage = "[{$timestamp}] [DEBUG] {$messageType} {$messageId} from {$fromUser} in chat {$chatId} ({$chatType}, {$chatTitle}) at {$date}: " . substr($text, 0, 100) . "..." . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    // Check if this is a roadmap message
    if (strpos($text, 'Upcoming updates:') !== false) {
        $logMessage = "[{$timestamp}] [SUCCESS] FOUND ROADMAP MESSAGE! Chat: {$chatId}, Type: {$chatType}, Title: {$chatTitle}, Message Type: {$messageType}" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        
        // Update the cache file
        updateRoadmapCache($text, $chatId);
    } else {
        $logMessage = "[{$timestamp}] [DEBUG] Not a roadmap message (doesn't contain 'Upcoming updates:')" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

function updateRoadmapCache($message, $chatId) {
    global $logFile, $timestamp;
    
    $cacheFile = __DIR__ . '/cached.json';
    
    $logMessage = "[{$timestamp}] [INFO] Updating roadmap cache" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    // Load existing cache or create new
    $cacheData = [];
    if (file_exists($cacheFile)) {
        $cacheData = json_decode(file_get_contents($cacheFile), true);
    }
    
    // Update cache with new message
    $cacheData['telegram_message'] = $message;
    $cacheData['chat_id'] = $chatId;
    $cacheData['last_updated'] = date('Y-m-d H:i:s');
    $cacheData['update_source'] = 'webhook';
    
    // Save updated cache
    if (file_put_contents($cacheFile, json_encode($cacheData, JSON_PRETTY_PRINT))) {
        $logMessage = "[{$timestamp}] [SUCCESS] Cache updated successfully with message from chat {$chatId}" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    } else {
        $logMessage = "[{$timestamp}] [ERROR] Failed to update cache file" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    }
}

function getWebhookInfo() {
    global $logFile, $timestamp;
    
    $cacheFile = __DIR__ . '/cached.json';
    $cacheData = [];
    
    if (file_exists($cacheFile)) {
        $cacheData = json_decode(file_get_contents($cacheFile), true);
    }
    
    if (empty($cacheData['telegram_bot_token'])) {
        $logMessage = "[{$timestamp}] [ERROR] No bot token found in cache" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        return null;
    }
    
    $botToken = $cacheData['telegram_bot_token'];
    $ch = curl_init();
    $url = "https://api.telegram.org/bot{$botToken}/getWebhookInfo";
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    $logMessage = "[{$timestamp}] [DEBUG] getWebhookInfo - HTTP: {$httpCode}, Error: {$curlError}" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data['ok']) {
            $logMessage = "[{$timestamp}] [SUCCESS] Webhook info retrieved: " . json_encode($data['result']) . PHP_EOL;
            file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
            return $data['result'];
        }
    }
    
    return null;
}

function setWebhook($webhookUrl) {
    global $logFile, $timestamp;
    
    $cacheFile = __DIR__ . '/cached.json';
    $cacheData = [];
    
    if (file_exists($cacheFile)) {
        $cacheData = json_decode(file_get_contents($cacheFile), true);
    }
    
    if (empty($cacheData['telegram_bot_token'])) {
        $logMessage = "[{$timestamp}] [ERROR] No bot token found in cache" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        return false;
    }
    
    $botToken = $cacheData['telegram_bot_token'];
    $ch = curl_init();
    $url = "https://api.telegram.org/bot{$botToken}/setWebhook";
    
    $params = [
        'url' => $webhookUrl,
        'allowed_updates' => ['message', 'channel_post']
    ];
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    $logMessage = "[{$timestamp}] [DEBUG] setWebhook - HTTP: {$httpCode}, Error: {$curlError}, Response: {$response}" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data['ok']) {
            $logMessage = "[{$timestamp}] [SUCCESS] Webhook set to: {$webhookUrl}" . PHP_EOL;
            file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
            return true;
        }
    }
    
    $logMessage = "[{$timestamp}] [ERROR] Failed to set webhook" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    return false;
}

// Handle admin actions if requested
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch ($action) {
        case 'set_webhook':
            if (isset($_GET['url'])) {
                setWebhook($_GET['url']);
            }
            break;
            
        case 'get_webhook_info':
            $info = getWebhookInfo();
            if ($info) {
                header('Content-Type: application/json');
                echo json_encode($info);
            }
            break;
            
        case 'remove_webhook':
            $cacheFile = __DIR__ . '/cached.json';
            $cacheData = [];
            if (file_exists($cacheFile)) {
                $cacheData = json_decode(file_get_contents($cacheFile), true);
            }
            if (!empty($cacheData['telegram_bot_token'])) {
                $botToken = $cacheData['telegram_bot_token'];
                $ch = curl_init();
                $url = "https://api.telegram.org/bot{$botToken}/deleteWebhook";
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $response = curl_exec($ch);
                curl_close($ch);
                
                $logMessage = "[{$timestamp}] [INFO] Webhook removed" . PHP_EOL;
                file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
            }
            break;
    }
    
    exit;
}
?>
