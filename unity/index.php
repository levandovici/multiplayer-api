<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = "Unity Multiplayer API – JsonUtility Compatible";
$description = "Unity multiplayer API with JsonUtility compatibility. Complete SDK and documentation for Unity game developers.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/logo.png";
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Platform-specific meta tags for better social sharing
$platform_name = "Unity Multiplayer API";
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
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
                Unity SDK for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-500">Multiplayer Games</span>
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto mb-10">
                JsonUtility-compatible SDK designed specifically for Unity developers. 
                Full serialization support, coroutines, and seamless integration with your Unity projects.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#sdk" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-download mr-2"></i>Download Unity SDK
                </a>
                <a href="#docs" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-book mr-2"></i>View Documentation
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Unity-Specific Features</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Built with Unity developers in mind</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-cube text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 text-center">JsonUtility Ready</h4>
                    <p class="text-white/80 text-center">Full compatibility with Unity's JsonUtility serialization system. No reflection or complex JSON handling needed.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-cyan-400 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-clock text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 text-center">Async/Await</h4>
                    <p class="text-white/80 text-center">Modern async/await patterns with Task-based operations for responsive applications.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-gamepad text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 text-center">IL2CPP Safe</h4>
                    <p class="text-white/80 text-center">Arrays instead of Lists, no reflection, and IL2CPP-compatible serialization for mobile builds.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SDK Section -->
    <section id="sdk" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Unity SDK</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Complete JsonUtility-compatible SDK for Unity developers</p>
            </div>
            
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Unity SDK Download -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center mr-4">
                            <i class="fab fa-unity text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">Unity SDK Download</h4>
                    </div>
                    <p class="text-white/80 mb-8">
                        Complete JsonUtility-compatible SDK designed specifically for Unity developers. 
                        Includes comprehensive multiplayer functionality with coroutines, authentication, 
                        and proper serialization for mobile builds.
                    </p>
                    
                    <!-- Download Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <button id="downloadUnitySdk" class="flex-1 glass-effect text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 hover:bg-white/20 flex items-center justify-center">
                            <i class="fas fa-download mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download Unity SDK</div>
                                <div class="text-xs opacity-80">SDK.cs - 50KB</div>
                            </div>
                        </button>
                        <button id="downloadUnityExample" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-code mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download Example</div>
                                <div class="text-xs opacity-80">Game.cs Demo - 25KB</div>
                            </div>
                        </button>
                        <a href="https://github.com/levandovici/multiplayer-sdk" target="_blank" class="flex-1 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fab fa-github mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">View on GitHub</div>
                                <div class="text-xs opacity-80">Full Repository</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Unity-Style Documentation Section -->
    <section id="docs" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Unity Scripting API</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Complete Unity-compatible API documentation for multiplayer games</p>
            </div>
            
            <!-- Navigation Sidebar Style -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <div class="glass-effect rounded-2xl p-6 sticky top-24">
                        <h4 class="text-white font-bold mb-4">Classes</h4>
                        <nav class="space-y-2">
                            <a href="#multiplayersdk" class="block text-white/70 hover:text-white py-1 text-sm transition">MultiplayerSDK</a>
                            <a href="#player-management" class="block text-white/70 hover:text-white py-1 text-sm transition">Player Management</a>
                            <a href="#game-data" class="block text-white/70 hover:text-white py-1 text-sm transition">Game Data</a>
                            <a href="#time-management" class="block text-white/70 hover:text-white py-1 text-sm transition">Time Management</a>
                            <a href="#room-management" class="block text-white/70 hover:text-white py-1 text-sm transition">Room Management</a>
                            <a href="#room-actions" class="block text-white/70 hover:text-white py-1 text-sm transition">Room Actions</a>
                            <a href="#room-updates" class="block text-white/70 hover:text-white py-1 text-sm transition">Room Updates</a>
                            <a href="#matchmaking" class="block text-white/70 hover:text-white py-1 text-sm transition">Matchmaking</a>
                            <a href="#leaderboard" class="block text-white/70 hover:text-white py-1 text-sm transition">Leaderboard</a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    
                    <!-- GameSDK Class -->
                    <div id="multiplayersdk" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6">
                            <h2 class="text-2xl font-bold text-white">GameSDK</h2>
                            <p class="text-white/80">Main SDK class for Unity multiplayer functionality</p>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-3">Constructor</h3>
                            <pre class="bg-black/30 text-purple-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public GameSDK(string apiToken, string apiPrivateToken, string baseUrl = "https://api.michitai.com/api", 
               ILogger logger = null, HttpClient httpClient = null, bool useUnityFormat = true)</code></pre>
                            <p class="text-white/70 mt-2">Initializes the SDK with API tokens and optional Unity formatting.</p>
                        </div>
                    </div>

                    <!-- Player Management Section -->
                    <div id="player-management" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-green-600 to-emerald-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Player Management</h2>
                            <p class="text-white/80">Methods for player registration, authentication, and lifecycle management</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">RegisterPlayer</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;PlayerRegisterResponse&gt; RegisterPlayer&lt;T&gt;(string name, T playerData = null, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Registers a new player with optional custom data. Uses generic type for player data with JsonUtility serialization.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">AuthenticatePlayer</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;PlayerAuthResponse&lt;T&gt;&gt; AuthenticatePlayer&lt;T&gt;(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Authenticates a player using their private token. Returns player information with typed data support.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendPlayerHeartbeatAsync</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;PlayerHeartbeatResponse&gt; SendPlayerHeartbeatAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Sends a heartbeat to maintain the player's connection. Call every 30-60 seconds to prevent timeout.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">LogoutPlayerAsync</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;PlayerLogoutResponse&gt; LogoutPlayerAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Logs out the current player and invalidates their session. Updates last logout timestamp.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetAllPlayers</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;PlayerListResponse&gt; GetAllPlayers(CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all registered players. Requires private API token. Useful for admin dashboards.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Game Data Section -->
                    <div id="game-data" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Game Data Management</h2>
                            <p class="text-white/80">Methods for managing global and player-specific game data</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetGameData</h3>
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;GameDataResponse&lt;T&gt;&gt; GetGameData&lt;T&gt;(CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves global game data with generic type support. Data is automatically deserialized using JsonUtility.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">UpdateGameData</h3>
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;SuccessResponse&gt; UpdateGameData&lt;T&gt;(T data, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Updates global game data. Requires private API token. Uses generic type for type safety.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetPlayerData</h3>
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;PlayerDataResponse&lt;T&gt;&gt; GetPlayerData&lt;T&gt;(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves player-specific data with generic type support. Requires player authentication.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">UpdatePlayerData</h3>
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;SuccessResponse&gt; UpdatePlayerData&lt;T&gt;(string playerToken, T data, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Updates player-specific data with generic type support. Requires player authentication.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time Management Section -->
                    <div id="time-management" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Time Management</h2>
                            <p class="text-white/80">Methods for server time synchronization</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetServerTime</h3>
                                    <pre class="bg-black/30 text-indigo-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;ServerTimeResponse&gt; GetServerTime(CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves current server time in UTC and readable formats.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetServerTimeWithOffset</h3>
                                    <pre class="bg-black/30 text-indigo-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;ServerTimeWithOffsetResponse&gt; GetServerTimeWithOffset(int utcOffset, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves server time with specified UTC offset. Useful for time synchronization.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Management Section -->
                    <div id="room-management" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-600 to-red-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Room Management</h2>
                            <p class="text-white/80">Methods for creating and managing game rooms</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">CreateRoomAsync</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;RoomCreateResponse&gt; CreateRoomAsync&lt;T&gt;(string playerToken, string roomName, string password = null, int maxPlayers = 4, T rules = null, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Creates a new game room with optional password and rules. The creating player becomes the host.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetRoomsAsync</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;RoomListResponse&lt;T&gt;&gt; GetRoomsAsync&lt;T&gt;(CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves all available game rooms with typed rules support. Useful for server browser.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">JoinRoomAsync</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;RoomJoinResponse&gt; JoinRoomAsync(string playerToken, string roomId, string password = null, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Joins an existing room. Password required for private rooms.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetRoomPlayersAsync</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;RoomPlayersResponse&gt; GetRoomPlayersAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all players in the current room with heartbeat status.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">LeaveRoomAsync</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;RoomLeaveResponse&gt; LeaveRoomAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Leaves the current room and updates player status.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendRoomHeartbeatAsync</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;HeartbeatResponse&gt; SendRoomHeartbeatAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Sends heartbeat to maintain room connection. Call every 30-60 seconds while in room.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetCurrentRoomAsync</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;CurrentRoomResponse&gt; GetCurrentRoomAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Gets comprehensive room state including player lists and pending actions.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Actions Section -->
                    <div id="room-actions" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-teal-600 to-green-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Room Actions</h2>
                            <p class="text-white/80">Methods for submitting and processing game actions</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SubmitActionAsync</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;ActionSubmitResponse&gt; SubmitActionAsync&lt;T&gt;(string playerToken, string actionType, T requestData = null, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Submits an action for processing by other players. Uses generic type for request data.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">PollActionsAsync</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;ActionPollResponse&gt; PollActionsAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Polls for completed actions from other players. Call periodically to check for new actions.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetPendingActionsAsync</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;ActionPendingResponse&lt;T&gt;&gt; GetPendingActionsAsync&lt;T&gt;(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Gets actions awaiting processing by the current player with typed request data.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">CompleteActionAsync</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;ActionCompleteResponse&gt; CompleteActionAsync&lt;T&gt;(string actionId, string playerToken, ActionComplete&lt;T&gt; request, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Marks an action as completed with processing results using typed response data.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Room Updates Section -->
                    <div id="room-updates" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-cyan-600 to-blue-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Room Updates</h2>
                            <p class="text-white/80">Methods for real-time data synchronization between players</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">UpdatePlayersAsync</h3>
                                    <pre class="bg-black/30 text-cyan-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;UpdatePlayersResponse&gt; UpdatePlayersAsync&lt;T&gt;(string playerToken, UpdatePlayers&lt;T&gt; request, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Sends real-time updates to specific players or all players. Uses typed UpdatePlayers parameter.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">PollUpdatesAsync</h3>
                                    <pre class="bg-black/30 text-cyan-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;PollUpdatesResponse&gt; PollUpdatesAsync(string playerToken, string lastUpdateId = null, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Polls for updates sent by other players. Supports incremental polling with lastUpdateId.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Matchmaking Section -->
                    <div id="matchmaking" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-pink-600 to-purple-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Matchmaking</h2>
                            <p class="text-white/80">Methods for matchmaking lobby management and game start</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetMatchmakingLobbiesAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingListResponse&gt; GetMatchmakingLobbiesAsync(CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all available matchmaking lobbies. Useful for lobby browser functionality.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">CreateMatchmakingLobbyAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingCreateResponse&gt; CreateMatchmakingLobbyAsync&lt;T&gt;(string playerToken, int maxPlayers = 4, bool strictFull = false, bool joinByRequests = false, T rules = null, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Creates a new matchmaking lobby with typed rules support. The creating player becomes the host.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">RequestToJoinMatchmakingAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingJoinRequestResponse&gt; RequestToJoinMatchmakingAsync(string playerToken, string matchmakingId, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Requests to join a lobby that requires host approval. Returns request ID for tracking.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">RespondToJoinRequestAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingPermissionResponse&gt; RespondToJoinRequestAsync(string playerToken, string requestId, MatchmakingRequestAction action, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Responds to a join request (approve/reject). Only the lobby host can call this.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">CheckJoinRequestStatusAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingRequestStatusResponse&gt; CheckJoinRequestStatusAsync(string playerToken, string requestId, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Checks the status of a join request. Useful for tracking approval/rejection status.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetCurrentMatchmakingStatusAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingCurrentResponse&lt;T&gt;&gt; GetCurrentMatchmakingStatusAsync&lt;T&gt;(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Gets comprehensive lobby state with typed rules including player status and pending requests.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">JoinMatchmakingDirectlyAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingDirectJoinResponse&gt; JoinMatchmakingDirectlyAsync(string playerToken, string matchmakingId, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Joins a matchmaking lobby directly. Only works if lobby allows direct join.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">LeaveMatchmakingAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingLeaveResponse&gt; LeaveMatchmakingAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Leaves the current matchmaking lobby and updates player status.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetMatchmakingPlayersAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingPlayersResponse&gt; GetMatchmakingPlayersAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all players in the current matchmaking lobby. Useful for displaying player lists.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendMatchmakingHeartbeatAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingHeartbeatResponse&gt; SendMatchmakingHeartbeatAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Sends heartbeat to maintain lobby connection. Call every 30-60 seconds while in lobby.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">RemoveMatchmakingLobbyAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingRemoveResponse&gt; RemoveMatchmakingLobbyAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Removes the matchmaking lobby and kicks all players. Only the lobby host can call this.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">StartGameFromMatchmakingAsync</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;MatchmakingStartResponse&gt; StartGameFromMatchmakingAsync(string playerToken, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Starts a game from matchmaking lobby. Transfers all players to a new game room.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Leaderboard Section -->
                    <div id="leaderboard" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-yellow-600 to-orange-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Leaderboard</h2>
                            <p class="text-white/80">Methods for competitive rankings and player statistics</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetLeaderboardAsync</h3>
                                    <pre class="bg-black/30 text-yellow-400 p-3 rounded text-sm overflow-x-auto whitespace-pre-wrap break-all"><code>public Task&lt;LeaderboardResponse&lt;T&gt;&gt; GetLeaderboardAsync&lt;T&gt;(string[] sortBy, int limit = 10, CancellationToken ct = default)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves ranked players with configurable sorting criteria and typed player data support.</p>
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

        // Download functionality for Unity SDK files
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
        
        document.getElementById('downloadUnitySdk').addEventListener('click', (event) => {
            triggerDownload('SDK.cs', 'Unity-SDK.cs');
        });
        
        document.getElementById('downloadUnityExample').addEventListener('click', (event) => {
            triggerDownload('Game.cs', 'Unity-Game.cs');
        });
    </script>

    <?php require_once '../php/footer.php'; ?>
</body>
</html>
