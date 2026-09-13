<?php
session_start();

// Set page-specific meta tag variables
$title = "About – Multiplayer API";
$description = "Learn about Multiplayer API: an open source (MIT-0) cross-platform multiplayer backend for game developers, built and maintained by Nichita Levandovici.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/michitai.png";
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$platform_name = "Multiplayer API";
$platform_type = "developer_tools";
$card_type = "summary_large_image";
$site_twitter = "@michitai";
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
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
                        <p class="text-xs text-white/70">About</p>
                    </div>
                </a>

                <div class="flex items-center space-x-3">
                    <a href="index.php" class="text-white/70 hover:text-white transition-colors font-medium">Home</a>
                    <a href="faq.php" class="text-white/70 hover:text-white transition-colors font-medium">FAQ</a>
                    <a href="login.php" class="text-white/70 hover:text-white transition-colors font-medium">Sign In</a>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-6 lg:px-8 py-12">
        <div class="glass-effect rounded-2xl p-8 lg:p-12">
            <h1 class="text-4xl font-bold text-white mb-8">About Multiplayer API</h1>

            <div class="space-y-8 text-white/80">
                <section>
                    <h2 class="text-2xl font-semibold text-white mb-4">The Project</h2>
                    <p class="mb-4">
                        Multiplayer API (api.michitai.com) is a cross-platform multiplayer backend for game
                        developers. It provides the server-side building blocks every online game needs —
                        player accounts, game rooms, matchmaking, leaderboards and realtime state updates —
                        exposed through a simple REST API and official SDKs.
                    </p>
                    <p>
                        The goal is to let indie developers and studios ship multiplayer features without
                        building and operating their own backend infrastructure.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-white mb-4">Open Source</h2>
                    <p class="mb-4">
                        The project — server code and all SDKs — is released under the
                        <a href="/LICENSE" class="text-blue-400 hover:text-blue-300">MIT No Attribution (MIT-0) license</a>:
                    </p>
                    <ul class="list-disc list-inside space-y-2">
                        <li>Use it in personal, commercial and closed-source projects</li>
                        <li>Modify and redistribute it freely</li>
                        <li>No attribution or copyright notice required</li>
                        <li>No warranty — the software is provided "AS IS"</li>
                    </ul>
                    <p class="mt-4">
                        The hosted service at api.michitai.com is operated under the
                        <a href="terms.php" class="text-blue-400 hover:text-blue-300">Terms of Service</a> and
                        <a href="privacy.php" class="text-blue-400 hover:text-blue-300">Privacy Policy</a>.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-white mb-4">Technology</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white/5 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2"><i class="fas fa-server mr-2 text-blue-400"></i>Backend</h3>
                            <p class="text-sm">PHP REST API on a MySQL database, with a Node.js realtime relay for room events. Hosted in Europe with a multi-region server network.</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2"><i class="fas fa-toolbox mr-2 text-purple-400"></i>SDKs</h3>
                            <p class="text-sm">Official SDKs for Unity (JsonUtility), Java (Jackson), .NET (System.Text.Json) and C++ (nlohmann::json), plus direct REST API access for everything else.</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2"><i class="fas fa-globe mr-2 text-green-400"></i>Infrastructure</h3>
                            <p class="text-sm">Servers across Europe, North America, Asia and India — see the <a href="servers/" class="text-blue-400 hover:text-blue-300">server map</a> for live locations.</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-4">
                            <h3 class="font-semibold text-white mb-2"><i class="fas fa-road mr-2 text-pink-400"></i>Development</h3>
                            <p class="text-sm">Actively developed. Follow progress on the public <a href="roadmap.php" class="text-blue-400 hover:text-blue-300">roadmap</a>.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-white mb-4">Author</h2>
                    <p>
                        Multiplayer API is designed, built and maintained by
                        <strong class="text-white">Nichita Levandovici</strong> (Michitai), Republic of Moldova.
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-white mb-4">Contact</h2>
                    <div class="bg-white/5 rounded-lg p-6">
                        <ul class="space-y-2">
                            <li class="flex items-center">
                                <i class="fas fa-envelope text-blue-400 w-5 mr-3"></i>
                                <a href="mailto:support@michitai.com" class="text-blue-400 hover:text-blue-300">support@michitai.com</a>
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-phone text-blue-400 w-5 mr-3"></i>
                                <a href="tel:+37369186845" class="text-blue-400 hover:text-blue-300">+373 69 186 845</a>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>

            <div class="mt-12 pt-8 border-t border-white/20">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <a href="index.php" class="btn-primary text-white px-6 py-3 rounded-xl font-semibold border-0">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Home
                    </a>
                    <div class="text-sm text-white/60 text-center sm:text-right">
                        <p>© 2026 Nichita Levandovici. All rights reserved.</p>
                        <p class="mt-1">
                            <a href="terms.php" class="text-blue-400 hover:text-blue-300">Terms of Service</a> •
                            <a href="privacy.php" class="text-blue-400 hover:text-blue-300">Privacy Policy</a> •
                            <a href="faq.php" class="text-blue-400 hover:text-blue-300">FAQ</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/php/footer.php'; ?>
</body>
</html>
