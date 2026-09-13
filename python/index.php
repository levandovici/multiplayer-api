<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = "Multiplayer API – Python";
$description = "Official Python SDK for the Multiplayer API. Type hints, dataclass responses, typed errors and asyncio realtime support.";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-bash.min.js"></script>
    
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
                Python SDK for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-300 to-yellow-300">Multiplayer Games</span>
            </h1>
            <p class="text-white/80 text-xl max-w-2xl mx-auto mb-8">
                Full coverage of players, rooms, matchmaking, leaderboards and realtime
                WebSocket rooms — with type hints, dataclass responses and typed errors.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#sdk" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-download mr-2"></i>Install the SDK
                </a>
                <a href="#docs" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-book mr-2"></i>View Documentation
                </a>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
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

    <!-- SDK Section -->
    <section id="sdk" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Python SDK</h3>
            </div>
            
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-yellow-500 flex items-center justify-center mr-4">
                            <i class="fab fa-python text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">Download</h4>
                    </div>
                    
                    <pre><code class="language-bash"># Requires Python 3.9+ — install the dependencies, then run the example
pip install requests            # core SDK
pip install websockets          # optional, realtime rooms only

python Game.py</code></pre>

                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <button id="downloadPythonSdk" class="flex-1 bg-gradient-to-r from-blue-600 to-yellow-500 hover:from-blue-700 hover:to-yellow-600 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-download mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download SDK</div>
                                <div class="text-xs opacity-80">Python-SDK.zip</div>
                            </div>
                        </button>
                        <button id="downloadPythonExample" class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-code mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download Example</div>
                                <div class="text-xs opacity-80">Game.py</div>
                            </div>
                        </button>
                        <a href="https://github.com/levandovici/multiplayer-sdk" target="_blank" class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fab fa-github mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">View on GitHub</div>
                                <div class="text-xs opacity-80">Repository</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Quick Start -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center mr-4">
                            <i class="fas fa-rocket text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">Quick Start</h4>
                    </div>
                    <pre><code class="language-python">from multiplayer import Client

client = Client("YOUR_API_TOKEN", "YOUR_PRIVATE_TOKEN")

# Register &amp; authenticate a player
reg = client.players.register("PlayerOne", {"level": 1, "class": "mage"})
player_token = reg.private_key

# Create and join a room
room = client.rooms.create(player_token, "Lobby 1", max_players=4)
client.rooms.heartbeat(player_token)

# Fetch the leaderboard
lb = client.leaderboard.get(["level", "score"], limit=10)
for entry in lb.leaderboard:
    print(f"#{entry.rank} - {entry.player_name}")</code></pre>
                    <p class="text-gray-400 text-sm">
                        Every response is a dataclass extending <code class="text-yellow-300 font-mono">ApiResponse</code> —
                        check <code class="text-yellow-300 font-mono">response.success</code>,
                        <code class="text-yellow-300 font-mono">response.error</code>,
                        <code class="text-yellow-300 font-mono">response.error_type</code> (typed enum) and
                        <code class="text-yellow-300 font-mono">response.error_message</code>.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Documentation Section -->
    <section id="docs" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Python API Documentation</h3>
            </div>
            
            <div class="grid grid-cols-12 gap-8">
                <!-- Sidebar Navigation -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="glass-effect rounded-xl p-6 sticky top-24">
                        <h4 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-list mr-2 text-yellow-300"></i>
                            API Reference
                        </h4>
                        <nav class="space-y-2">
                            <a href="#client-class" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Client Class</a>
                            <a href="#player-management" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Player Management</a>
                            <a href="#game-data" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Game Data</a>
                            <a href="#time-management" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Time Management</a>
                            <a href="#room-management" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Room Management</a>
                            <a href="#room-actions" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Room Actions</a>
                            <a href="#room-updates" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Room Updates</a>
                            <a href="#realtime" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Realtime</a>
                            <a href="#matchmaking" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Matchmaking</a>
                            <a href="#leaderboard" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Leaderboard</a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-span-12 lg:col-span-9 space-y-8">
                    <!-- Client Class -->
                    <div id="client-class" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-yellow-500 flex items-center justify-center mr-4">
                                <i class="fab fa-python text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Client</h4>
                        </div>
                        
                        <div class="bg-black/50 rounded-lg p-4 mb-6">
                            <h5 class="text-yellow-300 font-mono text-sm mb-2">Constructor</h5>
                            <pre class="text-sm text-white overflow-x-auto"><code class="language-python">Client(
    api_token: str,
    api_private_token: str,
    base_url: str = "https://api.michitai.com/api",
    logger: Logger | None = None,
    timeout: float = 30.0,
    session: requests.Session | None = None,
)</code></pre>
                            <p class="text-gray-400 text-sm mt-2">Initializes the SDK with API tokens. Services are exposed as <code class="text-yellow-300 font-mono">client.players</code>, <code class="text-yellow-300 font-mono">client.rooms</code>, <code class="text-yellow-300 font-mono">client.actions</code>, <code class="text-yellow-300 font-mono">client.updates</code>, <code class="text-yellow-300 font-mono">client.matchmaking</code>, <code class="text-yellow-300 font-mono">client.matchmaking_requests</code>, <code class="text-yellow-300 font-mono">client.leaderboard</code>, <code class="text-yellow-300 font-mono">client.games</code> and <code class="text-yellow-300 font-mono">client.time</code>.</p>
                        </div>
                    </div>
                    
                    <!-- Player Management -->
                    <div id="player-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center mr-4">
                                <i class="fas fa-users text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Player Management — client.players</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">register</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.register(name: str, player_data: Any = None) -> PlayerRegisterResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Registers a new player with optional custom data. Returns <code class="text-yellow-300 font-mono">player_id</code> and <code class="text-yellow-300 font-mono">private_key</code> (the player token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">authenticate</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.authenticate(player_token: str, data_cls: type | None = None) -> PlayerAuthResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Authenticates a player using their private token. Pass a dataclass as <code class="text-yellow-300 font-mono">data_cls</code> to deserialize <code class="text-yellow-300 font-mono">player.player_data</code> into it.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">get_all_players</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.games.get_all_players() -> PlayerListResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players (requires private API token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">heartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.heartbeat(player_token: str) -> PlayerHeartbeatResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates player heartbeat to maintain online status.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">logout</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.logout(player_token: str) -> PlayerLogoutResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Logs out a player and updates their last logout timestamp.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">rename</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.rename(player_token: str, new_name: str) -> PlayerRenameResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Renames a player (2-50 characters).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">ban</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.ban(player_id: int, ban_duration: BanTime, ban_reason: str | None = None) -> PlayerBanResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Bans a player with a <code class="text-yellow-300 font-mono">BanTime</code> duration (HOUR, DAY, WEEK, MONTH, QUARTER, YEAR, FOREVER) and optional reason. Requires private API token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">unban</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.unban(player_id: int) -> PlayerUnbanResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Unbans a previously banned player. Requires private API token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">is_banned</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">from multiplayer import is_banned
is_banned(response: ApiResponse) -> bool</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Checks if an API response indicates the player is banned (error contains "You are banned" or <code class="text-yellow-300 font-mono">ban_info</code> is present).</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Game Data -->
                    <div id="game-data" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center mr-4">
                                <i class="fas fa-database text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Game Data — client.games / client.players</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">games.get_data</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.games.get_data(data_cls: type | None = None) -> GameDataResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves global game data. Pass a dataclass as <code class="text-yellow-300 font-mono">data_cls</code> for typed <code class="text-yellow-300 font-mono">data</code>.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">games.update_data</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.games.update_data(data: Any) -> SuccessResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates global game data (requires private API token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">players.get_data</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.get_data(player_token: str, data_cls: type | None = None) -> PlayerDataResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a specific player's data using their authentication token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">players.update_data</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.players.update_data(player_token: str, data: Any) -> SuccessResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates a specific player's data (level, score, inventory, ...).</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Time Management -->
                    <div id="time-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-600 to-orange-600 flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Time Management — client.time</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">get_server_time</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.time.get_server_time() -> ServerTimeResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves current server time: <code class="text-yellow-300 font-mono">utc</code> (datetime), <code class="text-yellow-300 font-mono">timestamp</code> and <code class="text-yellow-300 font-mono">readable</code>.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">get_server_time_with_offset</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.time.get_server_time_with_offset(utc_offset: int) -> ServerTimeWithOffsetResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves server time adjusted by a UTC offset in hours (e.g. <code class="text-yellow-300 font-mono">3</code> for UTC+3, <code class="text-yellow-300 font-mono">-5</code> for UTC-5).</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Management -->
                    <div id="room-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-600 to-teal-600 flex items-center justify-center mr-4">
                                <i class="fas fa-door-open text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Room Management — client.rooms</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">create</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.create(
    player_token: str,
    room_name: str,
    max_players: int = 4,
    password: str | None = None,
    host_switch: bool = False,
    realtime: bool = False,
    player_data: Any = None,
    rules: Any = None,
) -> RoomCreateResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new game room with optional password, rules and host player data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">list</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.list(search: str | None = None, limit: int | None = None, rules_cls: type | None = None) -> RoomListResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves available game rooms. Pass <code class="text-yellow-300 font-mono">rules_cls</code> to deserialize each room's <code class="text-yellow-300 font-mono">rules</code>.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">join</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.join(player_token: str, room_id: str, password: str | None = None, player_data: Any = None) -> RoomJoinResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins an existing game room with optional password and player data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">leave</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.leave(player_token: str) -> RoomLeaveResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">get_players</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.get_players(player_token: str, data_cls: type | None = None) -> RoomPlayersResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves all players in the current room with typed <code class="text-yellow-300 font-mono">player_data</code>.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">heartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.heartbeat(player_token: str) -> HeartbeatResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends a heartbeat to maintain connection in the game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">get_current</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.get_current(player_token: str, rules_cls: type | None = None) -> CurrentRoomResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets comprehensive room state including pending actions and updates.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">stop</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.stop(player_token: str) -> RoomStopResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Stops the current game room (host only). Completely removes the room and all associated data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">kick</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.kick(player_token: str, player_id: int) -> RoomKickResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Kicks a player from the game room (host only). Cannot kick yourself.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">update_password</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.rooms.update_password(player_token: str, password: str | None = None) -> RoomPasswordResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates the room password (host only). Pass <code class="text-yellow-300 font-mono">None</code> to remove it.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Actions -->
                    <div id="room-actions" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-600 to-pink-600 flex items-center justify-center mr-4">
                                <i class="fas fa-play-circle text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Room Actions — client.actions</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">submit</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.actions.submit(
    player_token: str,
    action_type: str,
    request_data: Any = None,
    target_players: RoomTargetPlayers = RoomTargetPlayers.ALL,
    target_players_ids: list[int] | None = None,
) -> ActionSubmitResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Submits a game action to target players (HOST, ALL, OTHERS or SPECIFIC ids).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">poll</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.actions.poll(player_token: str, data_cls: type | None = None) -> ActionPollResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for completed actions targeted at the current player.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">get_pending</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.actions.get_pending(player_token: str, data_cls: type | None = None) -> ActionPendingResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves pending actions to process (host only).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">complete</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.actions.complete(
    player_token: str,
    action_id: str,
    status: RoomCompleteActionStatus = RoomCompleteActionStatus.COMPLETED,
    response_data: Any = None,
) -> ActionCompleteResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Marks an action as complete with optional response data (host only).</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Updates -->
                    <div id="room-updates" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center mr-4">
                                <i class="fas fa-sync text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Room Updates — client.updates</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">send</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.updates.send(
    player_token: str,
    type: str,
    data: Any = None,
    target_players: RoomTargetPlayers = RoomTargetPlayers.ALL,
    target_players_ids: list[int] | None = None,
) -> UpdatePlayersResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends an update to specific players or all players in the room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">poll</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.updates.poll(
    player_token: str,
    from_players: RoomTargetPlayers = RoomTargetPlayers.HOST,
    from_players_ids: list[int] | None = None,
    last_update: str | None = None,
    data_cls: type | None = None,
) -> PollUpdatesResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for updates sent to the current player, optionally filtered by source players and <code class="text-yellow-300 font-mono">last_update</code> id.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Realtime -->
                    <div id="realtime" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center mr-4">
                                <i class="fas fa-bolt text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Realtime — asyncio + websockets</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">Constructor</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">Realtime(realtime_websocket_url: str = "wss://realtime.michitai.com")</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Initializes the realtime WebSocket client with optional custom server URL. Requires <code class="text-yellow-300 font-mono">pip install websockets</code>.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">get_token</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">Realtime.get_token(client: Client, player_token: str) -> TokenResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Generates a realtime authentication token (player must be in a realtime-enabled room).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">connect</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">await realtime.connect(realtime_token: str) -> bool</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Connects to the WebSocket server using the realtime token. Starts the message listener and the 20s heartbeat loop automatically.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">send</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">await realtime.send(
    target: RoomTargetPlayer,
    command: str,
    data: Any = None,
    target_ids: list[int] | None = None,
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends a message to ALL, HOST, OTHERS or SPECIFIC player ids with optional data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">disconnect</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">await realtime.disconnect()</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Disconnects from the WebSocket server and cleans up tasks.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">Callbacks</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">realtime.on_receive = lambda command, data, sender: ...
realtime.on_connected = lambda: ...</code></pre>
                                <p class="text-gray-400 text-sm mt-2"><code class="text-yellow-300 font-mono">on_receive(command, data, sender)</code> fires for each incoming message; <code class="text-yellow-300 font-mono">on_connected()</code> fires once the socket is open. Both sync and async callbacks are supported.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Matchmaking -->
                    <div id="matchmaking" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-600 to-red-600 flex items-center justify-center mr-4">
                                <i class="fas fa-users-cog text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Matchmaking — client.matchmaking / client.matchmaking_requests</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">list</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.list(search: str | None = None, limit: int | None = None, rules_cls: type | None = None) -> MatchmakingListResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Lists all available matchmaking lobbies with optional search and typed rules.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">create (direct join)</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.create(
    player_token: str,
    matchmaking_name: str,
    max_players: int = 4,
    strict_full: bool = False,
    host_switch: bool = False,
    can_leave_room: bool = False,
    realtime_room: bool = False,
    password: str | None = None,
    player_data: Any = None,
    rules: Any = None,
) -> MatchmakingCreateResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a lobby that players can join directly. For approval-based joins use <code class="text-yellow-300 font-mono">client.matchmaking_requests.create(..., join_by_requests=True)</code>.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">request_to_join</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking_requests.request_to_join(player_token: str, matchmaking_id: str, player_data: Any = None) -> MatchmakingJoinRequestResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Requests to join a lobby that requires host approval.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">respond</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking_requests.respond(player_token: str, request_id: str, action: MatchmakingRequestAction) -> MatchmakingPermissionResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Host responds to a join request (<code class="text-yellow-300 font-mono">MatchmakingRequestAction.APPROVE</code> or <code class="text-yellow-300 font-mono">REJECT</code>).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">check_status</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking_requests.check_status(player_token: str, request_id: str) -> MatchmakingRequestStatusResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Checks the status of a join request.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">get_current</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.get_current(player_token: str, rules_cls: type | None = None) -> MatchmakingCurrentResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets the current lobby status including pending join requests.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">join</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.join(player_token: str, matchmaking_id: str, player_data: Any = None) -> MatchmakingDirectJoinResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins a matchmaking lobby directly (only if it doesn't require approval).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">leave</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.leave(player_token: str) -> MatchmakingLeaveResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">get_players</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.get_players(player_token: str, data_cls: type | None = None) -> MatchmakingPlayersResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets all players in the current lobby with typed player data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">heartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.heartbeat(player_token: str) -> MatchmakingHeartbeatResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends a heartbeat to maintain presence in the lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">remove</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.remove(player_token: str) -> MatchmakingRemoveResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Removes the matchmaking lobby (host only).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">start</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.start(player_token: str) -> MatchmakingStartResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Starts the game: creates a game room and transfers all lobby players (host only).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">stop</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.stop(player_token: str) -> MatchmakingStopResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Stops the matchmaking lobby (host only, before the game has started).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">kick</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.kick(player_token: str, player_id: int) -> MatchmakingKickResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Kicks a player from the lobby (host only, before the game has started).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">update_password</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.matchmaking.update_password(player_token: str, password: str | None = None) -> MatchmakingPasswordResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates the lobby password (host only, before the game has started). Pass <code class="text-yellow-300 font-mono">None</code> to remove it.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leaderboard -->
                    <div id="leaderboard" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-600 to-amber-600 flex items-center justify-center mr-4">
                                <i class="fas fa-trophy text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Leaderboard — client.leaderboard</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">get</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-python">client.leaderboard.get(sort_by: list[str], limit: int = 10, data_cls: type | None = None) -> LeaderboardResponse</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets the ranked leaderboard sorted by player-data fields (limit 1-100).</p>
                                <div class="mt-3 p-3 bg-yellow-900/20 rounded border border-yellow-700/50">
                                    <p class="text-yellow-200 text-xs font-medium mb-2">Example Usage:</p>
                                    <pre class="text-xs text-yellow-100 overflow-x-auto"><code class="language-python"># Sort by level, then score
response = client.leaderboard.get(["level", "score"], limit=10)

for entry in response.leaderboard:
    print(f"#{entry.rank} - {entry.player_name}")
    # typed player data: entry.player_data</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
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

        // Download functionality for Python SDK files
        function triggerDownload(url, filename) {
            const originalText = event.target.innerHTML;
            event.target.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Preparing...';
            event.target.disabled = true;
            
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            
            document.body.appendChild(link);
            const clickEvent = new MouseEvent('click', {
                view: window,
                bubbles: true,
                cancelable: false
            });
            link.dispatchEvent(clickEvent);
            
            setTimeout(() => {
                document.body.removeChild(link);
                event.target.innerHTML = originalText;
                event.target.disabled = false;
                
                const successMsg = document.createElement('div');
                successMsg.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center z-50';
                successMsg.innerHTML = `<i class="fas fa-check-circle mr-2"></i> Downloaded ${filename} successfully!`;
                document.body.appendChild(successMsg);
                
                setTimeout(() => {
                    successMsg.style.opacity = '0';
                    setTimeout(() => successMsg.remove(), 300);
                }, 3000);
            }, 100);
        }
        
        document.getElementById('downloadPythonSdk').addEventListener('click', (event) => {
            triggerDownload('SDK.zip', 'Python-SDK.zip');
        });
        
        document.getElementById('downloadPythonExample').addEventListener('click', (event) => {
            triggerDownload('Game.py', 'Game.py');
        });
    </script>

</body>
</html>
