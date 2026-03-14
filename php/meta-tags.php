<?php
/**
 * Open Graph and Twitter Card Meta Tags Generator
 * 
 * This file generates meta tags for social media sharing
 * Include this file in the <head> section of your pages
 */

// Get the current page URL dynamically
function getCurrentUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $path = $_SERVER['REQUEST_URI'];
    return $protocol . "://" . $host . $path;
}

// Default values
$defaultTitle = "Multiplayer API – Core Cells";
$defaultDescription = "A powerful multiplayer API for real-time gaming experiences";
$defaultImage = "https://" . $_SERVER['HTTP_HOST'] . "/logo.png";
$defaultUrl = getCurrentUrl();

// Override with provided variables if they exist
$title = isset($title) ? $title : $defaultTitle;
$description = isset($description) ? $description : $defaultDescription;
$image = isset($image) && !empty($image) ? $image : $defaultImage;
$url = isset($url) ? $url : $defaultUrl;

// Ensure image has full URL if it's a relative path
if (!filter_var($image, FILTER_VALIDATE_URL) && !str_starts_with($image, 'http')) {
    $image = "https://" . $_SERVER['HTTP_HOST'] . "/" . ltrim($image, '/');
}

// Ensure URL has full URL if it's a relative path
if (!filter_var($url, FILTER_VALIDATE_URL) && !str_starts_with($url, 'http')) {
    $url = "https://" . $_SERVER['HTTP_HOST'] . "/" . ltrim($url, '/');
}

// Output meta tags with safe HTML escaping
?>
<!-- Open Graph Meta Tags -->
<meta property="og:title" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:image" content="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:url" content="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:type" content="article">
<meta property="og:site_name" content="Multiplayer API">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>">

<!-- Additional Meta Tags for Better Sharing -->
<meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
<link rel="canonical" href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>">
