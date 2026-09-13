<?php
session_start();

// Set page-specific meta tag variables
$title = "FAQ – Multiplayer API";
$description = "Frequently asked questions about Multiplayer API: features, pricing, SDKs for Unity/Java/.NET/C++, the MIT-0 license, self-hosting and support.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/michitai.png";
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$platform_name = "Multiplayer API";
$platform_type = "developer_tools";
$card_type = "summary_large_image";
$site_twitter = "@michitai";

// FAQ entries – rendered below and reused for JSON-LD structured data
$faqs = [
    [
        'q' => 'What is Multiplayer API?',
        'a' => 'Multiplayer API is a cross-platform backend for multiplayer games. It provides player accounts, game rooms, matchmaking, leaderboards and realtime updates through a REST API and official SDKs for Unity, Java, .NET and C++.'
    ],
    [
        'q' => 'Is Multiplayer API free to use?',
        'a' => 'Yes. There is a free plan with 128 MB storage, 1 game and up to 100 concurrent players. Paid plans (Standard and Pro) raise the limits for larger games. The source code and SDKs themselves are open source under MIT-0.'
    ],
    [
        'q' => 'What license is the project released under?',
        'a' => 'The server source code and all SDKs are released under the MIT No Attribution (MIT-0) license. You can use, modify and distribute the code in any project — commercial or not — without giving attribution.'
    ],
    [
        'q' => 'Which platforms and languages are supported?',
        'a' => 'Official SDKs exist for Unity (JsonUtility), Java (Jackson), .NET (System.Text.Json) and C++ (nlohmann::json). Any other platform can integrate directly through the language-agnostic REST API.'
    ],
    [
        'q' => 'What features does the API provide?',
        'a' => 'Player registration and authentication, sessions and heartbeats, game data storage, game rooms with realtime actions, matchmaking lobbies with join requests and passwords, leaderboards, server time sync, and player moderation (timed and permanent bans).'
    ],
    [
        'q' => 'Do I need to host my own server?',
        'a' => 'No. You can use the hosted service at api.michitai.com with a free developer account. Because the project is MIT-0 licensed, you may also self-host it — in that case your own deployment and privacy policy apply.'
    ],
    [
        'q' => 'How do I get started?',
        'a' => 'Create a free account, generate an API token from your cabinet, then pick an SDK (Unity, Java, .NET or C++) or call the REST API directly. The API documentation includes copy-paste examples for every endpoint.'
    ],
    [
        'q' => 'Where is my data stored?',
        'a' => 'The hosted service stores data on servers in Europe (France) provided by Hostinger, in compliance with GDPR. See the Privacy Policy for details on retention and your rights.'
    ],
    [
        'q' => 'How do I report a bug or request a feature?',
        'a' => 'Contact support@michitai.com. You can also follow planned and completed work on the public development roadmap.'
    ],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/png" href="/michitai.png">
    <meta name="robots" content="index, follow">

    <?php require_once 'php/meta-tags.php'; ?>

    <!-- FAQ structured data for search engines -->
    <script type="application/ld+json">
    <?= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(fn($f) => [
            '@type' => 'Question',
            'name' => $f['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
        ], $faqs),
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .glass-effect {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
        }

        .btn-primary {
            background: var(--primary-gradient);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .animated-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        details summary::-webkit-details-marker {
            display: none;
        }

        details[open] .faq-chevron {
            transform: rotate(180deg);
        }
    </style>
</head>

<body class="min-h-screen animated-bg">
    <!-- Header -->
    <header class="glass-effect border-b border-white/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="index.php" class="flex items-center space-x-3">
                    <img src="/michitai.png" alt="Multiplayer API Logo" class="w-10 h-10 rounded-xl object-contain">
                    <div>
                        <h1 class="text-lg font-bold text-white">Multiplayer API</h1>
                        <p class="text-xs text-white/70">FAQ</p>
                    </div>
                </a>

                <div class="flex items-center space-x-3">
                    <a href="index.php" class="text-white/70 hover:text-white transition-colors font-medium">Home</a>
                    <a href="about.php" class="text-white/70 hover:text-white transition-colors font-medium">About</a>
                    <a href="login.php" class="text-white/70 hover:text-white transition-colors font-medium">Sign In</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-6 lg:px-8 py-12">
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-white mb-4">Frequently Asked Questions</h1>
            <p class="text-white/70 text-lg">Everything you need to know about Multiplayer API.</p>
        </div>

        <div class="space-y-4">
            <?php foreach ($faqs as $faq): ?>
            <details class="glass-effect rounded-xl overflow-hidden group">
                <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                    <h2 class="text-lg font-semibold text-white pr-4"><?= htmlspecialchars($faq['q'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <i class="fas fa-chevron-down text-white/60 faq-chevron transition-transform flex-shrink-0"></i>
                </summary>
                <div class="px-6 pb-6 text-white/75 leading-relaxed">
                    <?= htmlspecialchars($faq['a'], ENT_QUOTES, 'UTF-8') ?>
                </div>
            </details>
            <?php endforeach; ?>
        </div>

        <div class="glass-effect rounded-2xl p-8 mt-12 text-center">
            <h2 class="text-2xl font-bold text-white mb-3">Still have questions?</h2>
            <p class="text-white/70 mb-6">Reach out and we'll get back to you.</p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="mailto:support@michitai.com" class="btn-primary text-white px-6 py-3 rounded-xl font-semibold">
                    <i class="fas fa-envelope mr-2"></i>support@michitai.com
                </a>
                <a href="index.php" class="text-white/80 hover:text-white font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>
        </div>

        <div class="mt-8 text-center text-sm text-white/60">
            <a href="about.php" class="text-blue-400 hover:text-blue-300">About</a> •
            <a href="terms.php" class="text-blue-400 hover:text-blue-300">Terms of Service</a> •
            <a href="privacy.php" class="text-blue-400 hover:text-blue-300">Privacy Policy</a>
        </div>
    </div>

    <?php include_once __DIR__ . '/php/footer.php'; ?>
</body>
</html>
