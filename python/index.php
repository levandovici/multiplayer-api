<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = "Multiplayer API – Python";
$description = "Python SDK for Multiplayer API is coming soon. In the meantime, use the universal REST API with JSON from any Python project.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/michitai.png";
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Platform-specific meta tags for better social sharing
$platform_name = "Multiplayer API";
$platform_type = "developer_tools";
$card_type = "summary_large_image";
$site_twitter = "@michitai";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/png" href="/michitai.png">
    
    <?php require_once '../php/meta-tags.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-python.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-json.min.js"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #3776ab 0%, #ffd43b 100%);
            --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --code-bg: #1e1e2d;
            --code-text: #ffffff;
        }
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .glass-effect {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--glass-border);
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(55, 118, 171, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(55, 118, 171, 0.6);
        }
        
        .animated-bg {
            background: linear-gradient(-45deg, #3776ab, #2b5b84, #4b8bbe, #ffd43b);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .floating-card {
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .floating-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        /* Code block styling */
        pre {
            background: var(--code-bg);
            color: var(--code-text);
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1rem 0;
            font-family: 'Fira Code', 'Consolas', 'Monaco', 'Andale Mono', monospace;
            font-size: 0.85em;
            line-height: 1.5;
            tab-size: 4;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            max-width: 100%;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        pre code {
            background: transparent;
            padding: 0;
            margin: 0;
            white-space: pre;
            word-break: normal;
            word-wrap: normal;
        }
        
        .token {
            background: none !important;
        }
        
        pre::-webkit-scrollbar {
            height: 8px;
        }
        pre::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.03);
        }
        pre::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.18);
            border-radius: 4px;
        }
        pre::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }
        
        /* Python logo-style split icon */
        .python-badge {
            background: linear-gradient(135deg, #3776ab 50%, #ffd43b 50%);
        }
    </style>
</head>
<body class="min-h-screen animated-bg">
    <!-- Header -->
    <header class="glass-effect border-b border-white/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="/michitai.png" alt="Multiplayer API Logo" class="w-10 h-10 rounded-xl object-contain">
                    <div>
                        <h1 class="text-lg font-bold text-white">Multiplayer API</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="https://api.michitai.com" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-24 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <div class="python-badge w-24 h-24 rounded-full flex items-center justify-center mb-8 mx-auto shadow-2xl">
                <i class="fab fa-python text-5xl text-white"></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                Python SDK<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-300 to-yellow-300">Coming Soon</span>
            </h1>
            <p class="text-white/80 text-xl max-w-2xl mx-auto mb-8">
                We're building a native Python SDK for Multiplayer API.
                Until it's ready, everything works today through the universal
                <strong class="text-white">REST API with JSON</strong> — perfect for
                <code class="text-yellow-300 font-mono">requests</code>,
                <code class="text-yellow-300 font-mono">httpx</code> and
                <code class="text-yellow-300 font-mono">aiohttp</code>.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#rest-api" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-plug mr-2"></i>Use the REST API Now
                </a>
                <a href="#examples" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-code mr-2"></i>Python Examples
                </a>
            </div>
        </div>
    </section>

    <!-- Coming Soon Features -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold text-white mb-4">What's Coming</h2>
                <p class="text-white/70 text-lg max-w-3xl mx-auto">
                    The Python SDK will wrap the full REST API with idiomatic Python — type hints, dataclasses and async support.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="glass-effect floating-card rounded-2xl p-8">
                    <div class="feature-icon w-14 h-14 rounded-xl flex items-center justify-center mb-5">
                        <i class="fas fa-box-open text-2xl text-yellow-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">pip Installable</h3>
                    <p class="text-white/70">A proper package with type hints, dataclass responses and full coverage of players, rooms, matchmaking and leaderboards.</p>
                </div>
                <div class="glass-effect floating-card rounded-2xl p-8">
                    <div class="feature-icon w-14 h-14 rounded-xl flex items-center justify-center mb-5">
                        <i class="fas fa-bolt text-2xl text-blue-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Async Realtime</h3>
                    <p class="text-white/70">Native <code class="text-yellow-300 font-mono text-sm">asyncio</code> + <code class="text-yellow-300 font-mono text-sm">websockets</code> client for the realtime relay at <code class="text-yellow-300 font-mono text-sm">wss://realtime.michitai.com</code>.</p>
                </div>
                <div class="glass-effect floating-card rounded-2xl p-8">
                    <div class="feature-icon w-14 h-14 rounded-xl flex items-center justify-center mb-5">
                        <i class="fas fa-gamepad text-2xl text-green-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Engine Friendly</h3>
                    <p class="text-white/70">Works with Pygame, Godot-Python, Panda3D and any backend or tool that can make HTTP requests.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- REST API Section -->
    <section id="rest-api" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-white mb-4">Use It Today via REST API</h2>
                <p class="text-white/70 text-lg">Every SDK feature is a plain JSON endpoint. No SDK required.</p>
            </div>
            
            <div class="glass-effect rounded-2xl p-8 mb-8">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-yellow-500 flex items-center justify-center mr-4">
                        <i class="fas fa-terminal text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Register a player — pure JSON over HTTPS</h3>
                </div>
                <pre><code class="language-python">import requests

API = "https://api.michitai.com/api"
API_TOKEN = "YOUR_API_TOKEN"

response = requests.post(
    f"{API}/game_players.php/register",
    params={"api_token": API_TOKEN},
    json={
        "player_name": "PlayerOne",
        "player_data": {"level": 1, "class": "mage"},
    },
)

data = response.json()
player_token = data["private_key"]
print(data)</code></pre>
            </div>
            
            <div class="glass-effect rounded-2xl p-8 mb-8">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-yellow-500 flex items-center justify-center mr-4">
                        <i class="fas fa-door-open text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Create and join a room</h3>
                </div>
                <pre><code class="language-python"># Create a room
room = requests.post(
    f"{API}/game_room.php/create",
    params={"api_token": API_TOKEN, "player_token": player_token},
    json={"room_name": "Lobby 1", "max_players": 4},
).json()

# Send a heartbeat to stay connected
requests.post(
    f"{API}/game_room.php/heartbeat",
    params={"api_token": API_TOKEN, "player_token": player_token},
)</code></pre>
            </div>
            
            <div class="glass-effect rounded-2xl p-8">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-yellow-500 flex items-center justify-center mr-4">
                        <i class="fas fa-trophy text-white"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Fetch the leaderboard</h3>
                </div>
                <pre><code class="language-python">leaderboard = requests.post(
    f"{API}/leaderboard.php",
    params={"api_token": API_TOKEN},
    json={
        "sort_by": ["level", "score"],
        "limit": 10,
    },
).json()

for entry in leaderboard["leaderboard"]:
    print(f"#{entry['rank']} - {entry['player_name']}")</code></pre>
            </div>
        </div>
    </section>

    <!-- Examples / Next Steps -->
    <section id="examples" class="py-16">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="glass-effect rounded-2xl p-10 lg:p-14 text-center">
                <div class="python-badge w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fab fa-python text-3xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-4">Ready when you are</h2>
                <p class="text-white/70 text-lg max-w-2xl mx-auto mb-6">
                    Create a free developer account, grab an API token and start making JSON calls from Python today.
                    Check the full REST API reference for every endpoint, request body and response schema.
                </p>
                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="../api/index.php" class="btn-primary text-white px-8 py-3 rounded-xl font-semibold inline-flex items-center">
                        <i class="fas fa-book mr-2"></i>REST API Reference
                    </a>
                    <a href="../register.php" class="glass-effect text-white px-8 py-3 rounded-xl font-semibold inline-flex items-center hover:bg-white/20 transition">
                        <i class="fas fa-user-plus mr-2"></i>Create Free Account
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once __DIR__ . '/../php/footer.php'; ?>
    
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>

</body>
</html>
