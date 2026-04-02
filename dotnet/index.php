<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = "Multiplayer API – .NET";
$description = "Multiplayer API with System.Text.Json support. Complete SDK and documentation for C# developers.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/logo.png";
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
    <link rel="icon" type="image/png" href="/logo.png">
    
    <?php require_once '../php/meta-tags.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-csharp.min.js"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            --success-gradient: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
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
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }
        
        .animated-bg {
            background: linear-gradient(-45deg, #10b981, #059669, #3b82f6, #1e40af);
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
                <div class="flex items-center space-x-3">
                    <img src="/logo.png" alt="Multiplayer API Logo" class="w-10 h-10 rounded-xl object-contain">
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
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                .NET SDK for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">Multiplayer Games</span>
            </h1>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#sdk" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-download mr-2"></i>Download .NET SDK
                </a>
                <a href="#docs" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-book mr-2"></i>View Documentation
                </a>
            </div>
        </div>
    </section>

    <!-- SDK Section -->
    <section id="sdk" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">.NET SDK</h3>
            </div>
            
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- .NET SDK Download -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center mr-4">
                            <i class="fab fa-microsoft text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">.NET SDK Download</h4>
                    </div>
                    
                    <!-- Download Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <button id="downloadDotnetSdk" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-download mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download .NET SDK</div>
                                <div class="text-xs opacity-80">SDK.cs - 45KB</div>
                            </div>
                        </button>
                        <button id="downloadDotnetExample" class="flex-1 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-code mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download Example</div>
                                <div class="text-xs opacity-80">Game.cs - 20KB</div>
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
            </div>
        </div>
    </section>

    <!-- .NET-Style Documentation Section -->
    <section id="docs" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">.NET API Documentation</h3>
            </div>
            
            <div class="grid grid-cols-12 gap-8">
                <!-- Sidebar Navigation -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="glass-effect rounded-xl p-6 sticky top-24">
                        <h4 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-list mr-2 text-green-400"></i>
                            API Reference
                        </h4>
                        <nav class="space-y-2">
                            <a href="#gamesdk-class" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">GameSDK Class</a>
                            <a href="#player-management" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Player Management</a>
                            <a href="#game-data" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Game Data</a>
                            <a href="#time-management" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Time Management</a>
                            <a href="#room-management" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Room Management</a>
                            <a href="#room-actions" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Room Actions</a>
                            <a href="#room-updates" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Room Updates</a>
                            <a href="#matchmaking" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Matchmaking</a>
                            <a href="#leaderboard" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Leaderboard</a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-span-12 lg:col-span-9 space-y-8">
                    <!-- GameSDK Class -->
                    <div id="gamesdk-class" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center mr-4">
                                <i class="fab fa-microsoft text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">GameSDK</h4>
                        </div>
                        
                        <div class="bg-black/50 rounded-lg p-4 mb-6">
                            <h5 class="text-green-400 font-mono text-sm mb-2">Constructor</h5>
                            <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public GameSDK(
    string apiToken, 
    string apiPrivateToken, 
    string baseUrl = "https://api.michitai.com/api",
    ILogger? logger = null
)</code></pre>
                            <p class="text-gray-400 text-sm mt-2">Initializes the SDK with API tokens and optional custom base URL.</p>
                        </div>
                    </div>
                    
                    <!-- Player Management -->
                    <div id="player-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center mr-4">
                                <i class="fas fa-users text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Player Management</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">RegisterPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerRegisterResponse&gt; RegisterPlayer&lt;T&gt;(string name, T? playerData = null, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Registers a new player with optional custom data. Uses generic type for player data with System.Text.Json serialization.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">AuthenticatePlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerAuthResponse&lt;T&gt;&gt; AuthenticatePlayer&lt;T&gt;(string playerToken, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Authenticates a player using their private token. Returns player information with typed data support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">GetAllPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerListResponse&gt; GetAllPlayers(CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players (requires private API token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">SendPlayerHeartbeatAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerHeartbeatResponse&gt; SendPlayerHeartbeatAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates player heartbeat to maintain online status.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">LogoutPlayerAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerLogoutResponse&gt; LogoutPlayerAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Logs out a player and updates their last logout timestamp.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Game Data -->
                    <div id="game-data" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center mr-4">
                                <i class="fas fa-database text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Game Data</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">GetGameData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;GameDataResponse&lt;T&gt;&gt; GetGameData&lt;T&gt;(CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves global game data with System.Text.Json compatible nested objects.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">UpdateGameData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;SuccessResponse&gt; UpdateGameData&lt;T&gt;(T data, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates global game data (requires private API token). Uses generic type for type safety.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">GetPlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerDataResponse&lt;T&gt;&gt; GetPlayerData&lt;T&gt;(string playerToken, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a specific player's data using their authentication token with typed support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">UpdatePlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;SuccessResponse&gt; UpdatePlayerData&lt;T&gt;(string playerToken, T data, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates a specific player's data like level, score, and inventory with generic type support.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Time Management -->
                    <div id="time-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-600 to-orange-600 flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Time Management</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">GetServerTime</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ServerTimeResponse&gt; GetServerTime()</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves current server time in multiple formats including UTC timestamp.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">GetServerTimeWithOffset</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ServerTimeWithOffsetResponse&gt; GetServerTimeWithOffset(
    int utcOffset
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves server time with specified UTC offset adjustment.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Management -->
                    <div id="room-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-cyan-600 to-teal-600 flex items-center justify-center mr-4">
                                <i class="fas fa-door-open text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Room Management</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">CreateRoomAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomCreateResponse&gt; CreateRoomAsync&lt;T&gt;(string gamePlayerToken,
    string roomName,
    string? password = null,
    int maxPlayers = 4,
    T? rules = null,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new game room for multiplayer sessions with typed rules support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">GetRoomsAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomListResponse&lt;T&gt;&gt; GetRoomsAsync&lt;T&gt;(CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all available game rooms with typed rules support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">JoinRoomAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomJoinResponse&gt; JoinRoomAsync(
    string gamePlayerToken,
    string roomId,
    string? password = null,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins an existing game room with optional password.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">LeaveRoomAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomLeaveResponse&gt; LeaveRoomAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">GetRoomPlayersAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomPlayersResponse&gt; GetRoomPlayersAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players in the current room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">SendHeartbeatAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;HeartbeatResponse&gt; SendHeartbeatAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends heartbeat to maintain connection in game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">GetCurrentRoomAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;CurrentRoomResponse&lt;T&gt;&gt; GetCurrentRoomAsync&lt;T&gt;(
    string playerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets comprehensive room state including player lists and pending actions with typed support.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Actions -->
                    <div id="room-actions" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-600 to-pink-600 flex items-center justify-center mr-4">
                                <i class="fas fa-play-circle text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Room Actions</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">SubmitActionAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionSubmitResponse&gt; SubmitActionAsync&lt;T&gt;(string gamePlayerToken,
    string actionType,
    T? requestData = null,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Submits a game action for processing by other players with typed request data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">PollActionsAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionPollResponse&lt;T&gt;&gt; PollActionsAsync&lt;T&gt;(string gamePlayerToken, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for completed actions from other players with typed response data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">GetPendingActionsAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionPendingResponse&lt;T&gt;&gt; GetPendingActionsAsync&lt;T&gt;(string gamePlayerToken, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of pending actions that need to be processed with typed request data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">CompleteActionAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionCompleteResponse&gt; CompleteActionAsync&lt;T&gt;(string actionId,
    string gamePlayerToken,
    ActionComplete&lt;T&gt; request,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Marks an action as completed with response data using typed ActionComplete parameter.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Updates -->
                    <div id="room-updates" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center mr-4">
                                <i class="fas fa-sync text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Room Updates</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">UpdatePlayersAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;UpdatePlayersResponse&gt; UpdatePlayersAsync&lt;T&gt;(string gamePlayerToken,
    UpdatePlayers&lt;T&gt; request,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends updates to specific players or all players in the room with typed data support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">PollUpdatesAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PollUpdatesResponse&lt;T&gt;&gt; PollUpdatesAsync&lt;T&gt;(string gamePlayerToken,
    string? lastUpdateId = null,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for updates from other players with optional incremental polling and typed data.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Matchmaking -->
                    <div id="matchmaking" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-600 to-red-600 flex items-center justify-center mr-4">
                                <i class="fas fa-users-cog text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Matchmaking</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">GetMatchmakingLobbiesAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingListResponse&lt;T&gt;&gt; GetMatchmakingLobbiesAsync&lt;T&gt;(CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Lists all available matchmaking lobbies with typed rules support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">CreateMatchmakingLobbyAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingCreateResponse&gt; CreateMatchmakingLobbyAsync&lt;T&gt;(string gamePlayerToken,
    int maxPlayers = 4,
    bool strictFull = false,
    bool joinByRequests = false,
    T? rules = null,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new matchmaking lobby with typed rules support and configurable settings.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">RequestToJoinMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingJoinRequestResponse&gt; RequestToJoinMatchmakingAsync(
    string gamePlayerToken,
    string matchmakingId,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Requests to join a matchmaking lobby that requires host approval.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">RespondToJoinRequestAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingPermissionResponse&gt; RespondToJoinRequestAsync(
    string gamePlayerToken,
    string requestId,
    MatchmakingRequestAction action,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Host responds to a join request (approve or reject).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">CheckJoinRequestStatusAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingRequestStatusResponse&gt; CheckJoinRequestStatusAsync(
    string gamePlayerToken,
    string requestId,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Checks the status of a join request.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">GetCurrentMatchmakingStatusAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingCurrentResponse&lt;T&gt;&gt; GetCurrentMatchmakingStatusAsync&lt;T&gt;(string gamePlayerToken, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets the current player's matchmaking status and lobby information with typed rules.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">JoinMatchmakingDirectlyAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingDirectJoinResponse&gt; JoinMatchmakingDirectlyAsync(
    string gamePlayerToken,
    string matchmakingId,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins a matchmaking lobby directly (only works if lobby doesn't require approval).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">LeaveMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingLeaveResponse&gt; LeaveMatchmakingAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">GetMatchmakingPlayersAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingPlayersResponse&gt; GetMatchmakingPlayersAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets all players in the current matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">SendMatchmakingHeartbeatAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingHeartbeatResponse&gt; SendMatchmakingHeartbeatAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends heartbeat to maintain connection in matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">RemoveMatchmakingLobbyAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingRemoveResponse&gt; RemoveMatchmakingLobbyAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Removes the matchmaking lobby (host only).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">StartGameFromMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingStartResponse&gt; StartGameFromMatchmakingAsync(
    string gamePlayerToken,
    CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Starts a game from matchmaking lobby (host only).</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leaderboard -->
                    <div id="leaderboard" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-600 to-amber-600 flex items-center justify-center mr-4">
                                <i class="fas fa-trophy text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Leaderboard</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">GetLeaderboardAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;LeaderboardResponse&lt;T&gt;&gt; GetLeaderboardAsync&lt;T&gt;(string[] sortBy, int limit = 10, CancellationToken ct = default)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets ranked leaderboard with configurable sorting, limit, and typed player data support.</p>
                                <div class="mt-3 p-3 bg-yellow-900/20 rounded border border-yellow-700/50">
                                    <p class="text-yellow-200 text-xs font-medium mb-2">Example Usage:</p>
                                    <pre class="text-xs text-yellow-100 overflow-x-auto"><code class="language-csharp">// Sort by level, then score
var response = await sdk.GetLeaderboardAsync&lt;PlayerData&gt;(
    new[] { "level", "score" }, 
    10
);

foreach (var entry in response.Leaderboard) {
    Console.WriteLine($"#{entry.Rank} - {entry.Player_name}");
    // Access typed player data: entry.PlayerData
}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="glass-effect border-t border-white/10 mt-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-8 text-center">
            <div class="text-white/60 text-sm">
                &copy; 2026 Nichita Levandovici. All rights reserved.
            </div>
        </div>
    </footer>
    
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

        // Download functionality for .NET SDK files
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
        
        document.getElementById('downloadDotnetSdk').addEventListener('click', (event) => {
            triggerDownload('SDK.cs', 'NET-SDK.cs');
        });
        
        document.getElementById('downloadDotnetExample').addEventListener('click', (event) => {
            triggerDownload('Game.cs', 'NET-Game.cs');
        });
    </script>

    <?php require_once '../php/footer.php'; ?>
</body>
</html>
