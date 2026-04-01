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
?>