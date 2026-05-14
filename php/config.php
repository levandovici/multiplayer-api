<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$db_host = $_ENV['DB_HOST'];
$db_user = $_ENV['DB_USER'];
$db_pass = $_ENV['DB_PASS'];
$db_name = $_ENV['DB_NAME'];
$base_url = $_ENV['BASE_URL'];

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    // Force UTC at DB level
    $pdo->exec("SET time_zone = '+00:00'");
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Generate UUID for API keys and verification tokens
function generate_uuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function isoUtc($value) {
    if (empty($value)) return null;

    // If your DB stores UTC already (recommended)
    $dt = new DateTime($value, new DateTimeZone('UTC'));

    // Force UTC and format with Z suffix
    return $dt->setTimezone(new DateTimeZone('UTC'))
              ->format('Y-m-d\TH:i:s\Z');
}

// Check if a player is banned
function checkPlayerBan($playerId, $gameId) {
    global $pdo;
    
    $stmt = $pdo->prepare("
        SELECT ban_id, ban_duration, ban_reason, banned_at, banned_until
        FROM player_bans
        WHERE player_id = ? AND game_id = ? AND is_active = TRUE
        AND (banned_until IS NULL OR banned_until > NOW())
        LIMIT 1
    ");
    $stmt->execute([$playerId, $gameId]);
    $ban = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($ban) {
        // Calculate remaining time
        $bannedAt = new DateTime($ban['banned_at']);
        $now = new DateTime();
        $bannedUntil = $ban['banned_until'] ? new DateTime($ban['banned_until']) : null;
        
        $message = "You are banned";
        if ($ban['ban_reason']) {
            $message .= ": " . $ban['ban_reason'];
        }
        
        if ($bannedUntil) {
            $interval = $now->diff($bannedUntil);
            if ($interval->invert === 0) {
                $message .= ". Ban expires in " . formatDuration($interval);
            }
        } else {
            $message .= ". This is a permanent ban.";
        }
        
        return [
            'is_banned' => true,
            'ban_id' => $ban['ban_id'],
            'ban_duration' => $ban['ban_duration'],
            'ban_reason' => $ban['ban_reason'],
            'banned_at' => isoUtc($ban['banned_at']),
            'banned_until' => $ban['banned_until'] ? isoUtc($ban['banned_until']) : null,
            'message' => $message
        ];
    }
    
    return ['is_banned' => false];
}

// Format duration interval for human-readable output
function formatDuration($interval) {
    if ($interval->y > 0) return $interval->y . " year" . ($interval->y > 1 ? "s" : "");
    if ($interval->m > 0) return $interval->m . " month" . ($interval->m > 1 ? "s" : "");
    if ($interval->d > 0) return $interval->d . " day" . ($interval->d > 1 ? "s" : "");
    if ($interval->h > 0) return $interval->h . " hour" . ($interval->h > 1 ? "s" : "");
    if ($interval->i > 0) return $interval->i . " minute" . ($interval->i > 1 ? "s" : "");
    return "less than a minute";
}

// Calculate ban expiration based on duration
function calculateBanExpiration($duration) {
    $now = new DateTime();
    
    switch ($duration) {
        case 'hour':
            $now->add(new DateInterval('PT1H'));
            break;
        case 'day':
            $now->add(new DateInterval('P1D'));
            break;
        case 'week':
            $now->add(new DateInterval('P7D'));
            break;
        case 'month':
            $now->add(new DateInterval('P1M'));
            break;
        case 'quarter':
            $now->add(new DateInterval('P3M'));
            break;
        case 'year':
            $now->add(new DateInterval('P1Y'));
            break;
        case 'forever':
            return null;
        default:
            $now->add(new DateInterval('P1D'));
    }
    
    return $now->format('Y-m-d H:i:s');
}
?>