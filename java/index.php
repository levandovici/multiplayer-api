<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = "Multiplayer API – Java";
$description = "Multiplayer API with Jackson Databind support. Complete SDK and documentation for Java developers.";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-java.min.js"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #f97316 0%, #ef4444 100%);
            --secondary-gradient: linear-gradient(135deg, #eab308 0%, #f97316 100%);
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
            box-shadow: 0 4px 15px rgba(249, 115, 22, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(249, 115, 22, 0.6);
        }
        
        .animated-bg {
            background: linear-gradient(-45deg, #f97316, #ef4444, #eab308, #f97316);
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
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                Java SDK for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-orange-400 to-red-500">Multiplayer Games</span>
            </h1>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#sdk" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-download mr-2"></i>Download Java SDK
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
                <h3 class="text-4xl font-black text-white mb-6">Java SDK</h3>
            </div>
            
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Java SDK Download -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-600 to-red-600 flex items-center justify-center mr-4">
                            <i class="fab fa-java text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">Java SDK Download</h4>
                    </div>
                    
                    <!-- Download Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <button id="downloadJavaSdk" class="flex-1 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-download mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download Java SDK</div>
                                <div class="text-xs opacity-80">SDK.zip</div>
                            </div>
                        </button>
                        <button id="downloadJavaExample" class="flex-1 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-code mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download Example</div>
                                <div class="text-xs opacity-80">Game.java</div>
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

    <!-- Java-Style Documentation Section -->
    <section id="docs" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Java API Documentation</h3>
            </div>
            
            <div class="grid grid-cols-12 gap-8">
                <!-- Sidebar Navigation -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="glass-effect rounded-xl p-6 sticky top-24">
                        <h4 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-list mr-2 text-orange-400"></i>
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
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-600 to-red-600 flex items-center justify-center mr-4">
                                <i class="fab fa-java text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Client</h4>
                        </div>
                        
                        <div class="bg-black/50 rounded-lg p-4 mb-6">
                            <h5 class="text-orange-400 font-mono text-sm mb-2">Constructor</h5>
                            <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public Client(
    String apiToken, 
    String apiPrivateToken, 
    String baseUrl,
    Logger logger
)</code></pre>
                            <p class="text-gray-400 text-sm mt-2">Initializes the SDK with API tokens and optional custom base URL. Uses Jackson Databind for JSON serialization.</p>
                        </div>
                    </div>
                    
                    <!-- Player Management -->
                    <div id="player-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-600 to-pink-600 flex items-center justify-center mr-4">
                                <i class="fas fa-users text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Player Management</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">registerPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; PlayerRegisterResponse registerPlayer(
    String name, 
    T playerData
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Registers a new player with optional custom data. Uses generic type for player data with Jackson serialization.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">authenticatePlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; PlayerAuthResponse&lt;T&gt; authenticatePlayer(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Authenticates a player using their private token. Returns player information with typed data support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">getAllPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public PlayerListResponse getAllPlayers() throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players (requires private API token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">sendPlayerHeartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public PlayerHeartbeatResponse sendPlayerHeartbeat(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates player heartbeat to maintain online status. Call every 30-60 seconds.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">logoutPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public PlayerLogoutResponse logoutPlayer(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Logs out a player and updates their last logout timestamp.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">renamePlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public PlayerRenameResponse renamePlayer(
    String playerToken,
    String newName
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Renames a player to a new name. Validates name length (2-50 characters) and requires player authentication.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">banPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public PlayerBanResponse banPlayer(
    int playerId,
    BanTime banDuration,
    String banReason
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Bans a player from the game with specified duration (HOUR, DAY, WEEK, MONTH, QUARTER, YEAR, FOREVER) and optional reason. Requires private API token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">unbanPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public PlayerUnbanResponse unbanPlayer(
    int playerId
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Unbans a previously banned player. Requires private API token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">isBanned</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public static boolean isBanned(ApiResponse response)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Checks if an API response indicates the player is banned. Returns true if the error message contains "You are banned".</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Game Data -->
                    <div id="game-data" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-600 to-orange-600 flex items-center justify-center mr-4">
                                <i class="fas fa-database text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Game Data</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">getGameData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; GameDataResponse&lt;T&gt; getGameData() throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves global game data with Jackson compatible nested objects.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">updateGameData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; SuccessResponse updateGameData(T data) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates global game data (requires private API token). Uses generic type for type safety.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">getPlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; PlayerDataResponse&lt;T&gt; getPlayerData(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a specific player's data using their authentication token with typed support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">updatePlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; SuccessResponse updatePlayerData(
    String playerToken,
    T data
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates a specific player's data like level, score, and inventory with generic type support.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Time Management -->
                    <div id="time-management" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Time Management</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">getServerTime</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public ServerTimeResponse getServerTime() throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves current server time in multiple formats including UTC timestamp.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">getServerTimeWithOffset</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public ServerTimeWithOffsetResponse getServerTimeWithOffset(
    int utcOffset
) throws IOException</code></pre>
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
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">createRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; RoomCreateResponse createRoom(
    String playerToken,
    String roomName,
    int maxPlayers,
    String password,
    boolean hostSwitch,
    boolean canLeaveRoom,
    T rules,
    T playerData
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new game room for multiplayer sessions with typed rules support, host switching, and player data. Max players: 2-16.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">getRooms</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; RoomListResponse&lt;T&gt; getRooms(
    String search,
    Integer limit
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all available game rooms with typed rules support. Supports search and limit parameters (limit: 1-50).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">joinRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; RoomJoinResponse joinRoom(
    String playerToken,
    String roomId,
    String password,
    T playerData
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins an existing game room with optional password and player data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">leaveRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public RoomLeaveResponse leaveRoom(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">getRoomPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public RoomPlayersResponse getRoomPlayers(
            String playerToken
        ) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players in the current room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">sendRoomHeartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public HeartbeatResponse sendRoomHeartbeat(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends heartbeat to maintain connection in game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">getCurrentRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; CurrentRoomResponse&lt;T&gt; getCurrentRoom(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets comprehensive room state including player lists and pending actions with typed support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">stopRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public SuccessResponse stopRoom(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Stops the current game room (Host Only). Completely removes the room and all associated data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">kickPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public RoomKickResponse kickPlayer(
    String playerToken,
    int playerId
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Kicks a player from the game room (Host Only). Cannot kick yourself.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">updateRoomPassword</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public SuccessResponse updateRoomPassword(
    String playerToken,
    String password
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates the room password (Host Only). Use empty string to remove password.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Room Actions -->
                    <div id="room-actions" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center mr-4">
                                <i class="fas fa-play-circle text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Room Actions</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-green-400 font-mono text-sm mb-2">submitAction</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; ActionSubmitResponse submitAction(
    String playerToken,
    SubmitAction&lt;T&gt; request
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Submits a game action to specific targets (host, all, others, or specific players) with typed request data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-green-400 font-mono text-sm mb-2">pollActions</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; ActionPollResponse&lt;T&gt; pollActions(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for completed actions from other players with typed response data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-green-400 font-mono text-sm mb-2">getPendingActions</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; ActionPendingResponse&lt;T&gt; getPendingActions(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of pending actions that need to be processed with typed request data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-green-400 font-mono text-sm mb-2">completeAction</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; ActionCompleteResponse completeAction(
    String actionId,
    String playerToken,
    ActionComplete&lt;T&gt; request
) throws IOException</code></pre>
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
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">updatePlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; UpdatePlayersResponse updatePlayers(
    String playerToken,
    UpdatePlayers&lt;T&gt; request
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends updates to specific players or all players. Uses typed UpdatePlayers parameter.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">pollUpdates</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public PollUpdatesResponse pollUpdates(
    String playerToken,
    PollUpdates request
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for updates from other players. Returns list of update messages with typed data.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Realtime -->
                    <div id="realtime" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-pink-600 to-rose-600 flex items-center justify-center mr-4">
                                <i class="fas fa-bolt text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Realtime</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-pink-400 font-mono text-sm mb-2">connectRealtime</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public void connectRealtime(
    String playerToken,
    RealtimeListener listener
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Connects to realtime WebSocket for instant updates. Requires player authentication.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-pink-400 font-mono text-sm mb-2">disconnectRealtime</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public void disconnectRealtime()</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Disconnects from realtime WebSocket connection.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-pink-400 font-mono text-sm mb-2">sendRealtimeMessage</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; void sendRealtimeMessage(
    String target,
    String type,
    T data
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends realtime message to specific targets with typed data support.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Matchmaking -->
                    <div id="matchmaking" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-600 to-yellow-600 flex items-center justify-center mr-4">
                                <i class="fas fa-random text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Matchmaking</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-amber-400 font-mono text-sm mb-2">createMatchmakingLobby</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T, U&gt; MatchmakingCreateResponse createMatchmakingLobby(
    String playerToken,
    String lobbyName,
    int maxPlayers,
    boolean strictFull,
    boolean joinByRequests,
    boolean hostSwitch,
    boolean canLeaveRoom,
    boolean realtimeRoom,
    String password,
    T playerData,
    U rules
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a matchmaking lobby with typed player data and rules support. Supports approval-based or direct join modes.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-amber-400 font-mono text-sm mb-2">joinMatchmakingDirectly</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; void joinMatchmakingDirectly(
    String playerToken,
    String matchmakingId,
    T playerData
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins a matchmaking lobby directly (requires lobby to be in direct join mode).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-amber-400 font-mono text-sm mb-2">requestToJoinMatchmaking</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; MatchmakingRequestResponse requestToJoinMatchmaking(
    String playerToken,
    String matchmakingId,
    T playerData
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Requests to join a matchmaking lobby (requires lobby to be in approval mode).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-amber-400 font-mono text-sm mb-2">respondToJoinRequest</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public MatchmakingRequestResponse respondToJoinRequest(
    String playerToken,
    String requestId,
    MatchmakingRequestAction action
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Responds to a join request (APPROVE or REJECT). Host only.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-amber-400 font-mono text-sm mb-2">getCurrentMatchmakingStatus</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; MatchmakingStatusResponse&lt;T&gt; getCurrentMatchmakingStatus(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets current matchmaking lobby status with typed rules support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-amber-400 font-mono text-sm mb-2">getMatchmakingPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; MatchmakingPlayersResponse&lt;T&gt; getMatchmakingPlayers(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets list of players in the matchmaking lobby with typed player data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-amber-400 font-mono text-sm mb-2">startGameFromMatchmaking</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public MatchmakingStartResponse startGameFromMatchmaking(
    String playerToken
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Starts the game from matchmaking lobby and creates a room. Host only.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Leaderboard -->
                    <div id="leaderboard" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-lime-600 to-green-600 flex items-center justify-center mr-4">
                                <i class="fas fa-trophy text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Leaderboard</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-lime-400 font-mono text-sm mb-2">getLeaderboard</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public &lt;T&gt; LeaderboardResponse&lt;T&gt; getLeaderboard(
    String[] sortFields,
    int limit
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves leaderboard sorted by specified fields with typed player data support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-lime-400 font-mono text-sm mb-2">updateLeaderboardScore</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-java">public SuccessResponse updateLeaderboardScore(
    String playerToken,
    Map&lt;String, Object&gt; scores
) throws IOException</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates player scores on leaderboard. Requires player authentication.</p>
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
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth' });
            });
        });
        
        // Download handlers
        document.getElementById('downloadJavaSdk').addEventListener('click', function() {
            window.location.href = '/java/SDK.zip';
        });
        
        document.getElementById('downloadJavaExample').addEventListener('click', function() {
            window.location.href = '/java/Game.java';
        });
    </script>
</body>
</html>