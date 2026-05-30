<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = "Multiplayer API – C++";
$description = "Multiplayer API with nlohmann::json support. Complete SDK and documentation for C++ developers.";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-cpp.min.js"></script>
    
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
                C++ SDK for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">Multiplayer Games</span>
            </h1>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#sdk" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-download mr-2"></i>Download C++ SDK
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
                <h3 class="text-4xl font-black text-white mb-6">C++ SDK</h3>
            </div>
            
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- C++ SDK Download -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center mr-4">
                            <i class="fas fa-code text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">C++ SDK Download</h4>
                    </div>
                    
                    <!-- Download Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <button id="downloadCppSdk" class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-download mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download C++ SDK</div>
                                <div class="text-xs opacity-80">SDK.zip - 50KB</div>
                            </div>
                        </button>
                        <button id="downloadCppExample" class="flex-1 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-code mr-3"></i>
                            <div class="text-left">
                                <div class="font-bold">Download Example</div>
                                <div class="text-xs opacity-80">Game.cpp - 22KB</div>
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

    <!-- C++-Style Documentation Section -->
    <section id="docs" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">C++ API Documentation</h3>
            </div>
            
            <div class="grid grid-cols-12 gap-8">
                <!-- Sidebar Navigation -->
                <div class="col-span-12 lg:col-span-3">
                    <div class="glass-effect rounded-xl p-6 sticky top-24">
                        <h4 class="text-lg font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-list mr-2 text-blue-400"></i>
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
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center mr-4">
                                <i class="fas fa-code text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Client</h4>
                        </div>
                        
                        <div class="bg-black/50 rounded-lg p-4 mb-6">
                            <h5 class="text-blue-400 font-mono text-sm mb-2">Constructor</h5>
                            <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">Client(
    const std::string& apiToken,
    const std::string& apiPrivateToken,
    const std::string& baseUrl = "https://api.michitai.com/api",
    std::shared_ptr&lt;ILogger&gt; logger = nullptr
)</code></pre>
                            <p class="text-gray-400 text-sm mt-2">Initializes the SDK with API tokens and optional custom base URL and logger.</p>
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
                                <h5 class="text-blue-400 font-mono text-sm mb-2">registerPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static PlayerRegisterResponse registerPlayer(
    Client& client,
    const std::string& name,
    const std::optional&lt;T&gt;& playerData = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Registers a new player with optional custom data. Uses template type for player data with nlohmann::json serialization.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">authenticatePlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static PlayerAuthResponse&lt;T&gt; authenticatePlayer(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Authenticates a player using their private token. Returns player information with typed data support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">getAllPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static PlayerListResponse getAllPlayers(Client& client)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players (requires private API token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">sendPlayerHeartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static PlayerHeartbeatResponse sendPlayerHeartbeat(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates player heartbeat to maintain online status.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">logoutPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static PlayerLogoutResponse logoutPlayer(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Logs out a player and updates their last logout timestamp.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">renamePlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static PlayerRenameResponse renamePlayer(
    Client& client,
    const std::string& playerToken,
    const std::string& newName
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Renames a player to a new name. Validates name length (2-50 characters) and requires player authentication.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">banPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static PlayerBanResponse banPlayer(
    Client& client,
    int playerId,
    EBanTime banDuration,
    const std::optional&lt;std::string&gt;& banReason = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Bans a player from the game with specified duration (Hour, Day, Week, Month, Quarter, Year, Forever) and optional reason. Requires private API token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">unbanPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static PlayerUnbanResponse unbanPlayer(
    Client& client,
    int playerId
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Unbans a previously banned player. Requires private API token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">isBanned</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static bool isBanned(const ApiResponse& response)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Checks if an API response indicates the player is banned. Returns true if the error message contains "You are banned".</p>
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
                                <h5 class="text-purple-400 font-mono text-sm mb-2">getGameData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static GameDataResponse&lt;T&gt; getGameData(Client& client)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves global game data with nlohmann::json compatible nested objects.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">updateGameData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T&gt;
static SuccessResponse updateGameData(Client& client, const T& data)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates global game data (requires private API token). Uses template type for type safety.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">getPlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static PlayerDataResponse&lt;T&gt; getPlayerData(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a specific player's data using their authentication token with typed support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">updatePlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T&gt;
static SuccessResponse updatePlayerData(
    Client& client,
    const std::string& playerToken,
    const T& data
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates a specific player's data like level, score, and inventory with template type support.</p>
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
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">getServerTime</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static ServerTimeResponse getServerTime(Client& client)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves current server time in multiple formats including UTC timestamp.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">getServerTimeWithOffset</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static ServerTimeWithOffsetResponse getServerTimeWithOffset(
    Client& client,
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
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">createRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename TPlayerData = nlohmann::json, typename TRules = nlohmann::json&gt;
static RoomCreateResponse createRoom(
    Client& client,
    const std::string& playerToken,
    const std::string& roomName,
    int maxPlayers = 4,
    const std::optional&lt;std::string&gt;& password = std::nullopt,
    bool hostSwitch = false,
    bool canLeaveRoom = true,
    const std::optional&lt;TPlayerData&gt;& playerData = std::nullopt,
    const std::optional&lt;TRules&gt;& rules = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new game room for multiplayer sessions with typed rules support, host switching, and player data. Max players: 2-16.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">getRooms</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static RoomListResponse&lt;T&gt; getRooms(
    Client& client,
    const std::optional&lt;std::string&gt;& search = std::nullopt,
    const std::optional&lt;int&gt;& limit = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all available game rooms with typed rules support. Supports search and limit parameters (limit: 1-50).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">joinRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static RoomJoinResponse joinRoom(
    Client& client,
    const std::string& playerToken,
    const std::string& roomId,
    const std::optional&lt;std::string&gt;& password = std::nullopt,
    const std::optional&lt;T&gt;& playerData = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins an existing game room with optional password and player data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">leaveRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static RoomLeaveResponse leaveRoom(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">getRoomPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static RoomPlayersResponse&lt;T&gt; getRoomPlayers(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players in the current room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">sendRoomHeartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static HeartbeatResponse sendRoomHeartbeat(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends heartbeat to maintain connection in game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">getCurrentRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static CurrentRoomResponse&lt;T&gt; getCurrentRoom(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets comprehensive room state including player lists and pending actions with typed support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">stopRoom</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static SuccessResponse stopRoom(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Stops the current game room (Host Only). Completely removes the room and all associated data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">kickPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static RoomKickResponse kickPlayer(
    Client& client,
    const std::string& playerToken,
    int playerId
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Kicks a player from the game room (Host Only). Cannot kick yourself.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">updateRoomPassword</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static SuccessResponse updateRoomPassword(
    Client& client,
    const std::string& playerToken,
    const std::optional&lt;std::string&gt;& password = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates the room password (Host Only). Use nullopt to remove password.</p>
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
                                <h5 class="text-red-400 font-mono text-sm mb-2">submitAction</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static ActionSubmitResponse submitAction(
    Client& client,
    const std::string& playerToken,
    const SubmitAction&lt;T&gt;& request
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Submits a game action to specific targets (host, all, others, or specific players) with typed request data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">pollActions</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static ActionPollResponse&lt;T&gt; pollActions(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for completed actions from other players with typed response data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">getPendingActions</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static ActionPendingResponse&lt;T&gt; getPendingActions(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of pending actions that need to be processed with typed request data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">completeAction</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static ActionCompleteResponse completeAction(
    Client& client,
    const std::string& actionId,
    const std::string& playerToken,
    const ActionComplete&lt;T&gt;& request
)</code></pre>
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
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static UpdatePlayersResponse updatePlayers(
    Client& client,
    const std::string& playerToken,
    const UpdatePlayers&lt;T&gt;& request
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends updates to specific players or all players in the room with typed data support.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">pollUpdates</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static PollUpdatesResponse&lt;T&gt; pollUpdates(
    Client& client,
    const std::string& playerToken,
    const PollUpdates& request
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for updates from specific source players with typed data support. Use PollUpdates request object to specify from_players, from_players_ids, and last_update.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Realtime -->
                    <div id="realtime" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center mr-4">
                                <i class="fas fa-bolt text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Realtime</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">getToken (Static)</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static TokenResponse getToken(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Static method to generate a realtime authentication token using the API client.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">buildWebSocketUrl (Static)</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static std::string buildWebSocketUrl(
    const RealtimeServerInfo& serverInfo,
    const std::string& token,
    const std::string& clientType = "json"
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Constructs a WebSocket URL from server info and token. Full WebSocket implementation requires additional dependencies (e.g., websocketpp, uWebSockets).</p>
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
                                <h5 class="text-orange-400 font-mono text-sm mb-2">getMatchmakingLobbies</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static MatchmakingListResponse&lt;T&gt; getMatchmakingLobbies(
    Client& client,
    const std::optional&lt;std::string&gt;& search = std::nullopt,
    const std::optional&lt;int&gt;& limit = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Lists all available matchmaking lobbies with typed rules support. Supports search and limit parameters (limit: 1-50).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">createMatchmakingLobby</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename TPlayerData = nlohmann::json, typename TRules = nlohmann::json&gt;
static MatchmakingCreateResponse createMatchmakingLobby(
    Client& client,
    const std::string& playerToken,
    const std::string& matchmakingName,
    int maxPlayers = 4,
    bool strictFull = false,
    bool joinByRequests = false,
    bool hostSwitch = false,
    bool canLeaveRoom = true,
    bool realtimeRoom = false,
    const std::optional&lt;std::string&gt;& password = std::nullopt,
    const std::optional&lt;TPlayerData&gt;& playerData = std::nullopt,
    const std::optional&lt;TRules&gt;& rules = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new matchmaking lobby with name, optional password, typed rules support, host switching, and player data. Max players: 2-16.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">getCurrentMatchmakingStatus</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static MatchmakingCurrentResponse&lt;T&gt; getCurrentMatchmakingStatus(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets the current player's matchmaking status and lobby information with typed rules.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">joinMatchmakingDirectly</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static MatchmakingDirectJoinResponse joinMatchmakingDirectly(
    Client& client,
    const std::string& playerToken,
    const std::string& matchmakingId,
    const std::optional&lt;T&gt;& playerData = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins a matchmaking lobby directly (only works if lobby doesn't require approval).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">leaveMatchmaking</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static MatchmakingLeaveResponse leaveMatchmaking(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">getMatchmakingPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static MatchmakingPlayersResponse&lt;T&gt; getMatchmakingPlayers(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets all players in the current matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">sendMatchmakingHeartbeat</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static MatchmakingHeartbeatResponse sendMatchmakingHeartbeat(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends heartbeat to maintain connection in matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">removeMatchmakingLobby</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static MatchmakingRemoveResponse removeMatchmakingLobby(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Removes the matchmaking lobby (host only).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">startGameFromMatchmaking</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static MatchmakingStartResponse startGameFromMatchmaking(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Starts a game from matchmaking lobby (host only).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">stopMatchmaking</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static SuccessResponse stopMatchmaking(
    Client& client,
    const std::string& playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Stops matchmaking lobby (Host Only). Cannot be called after game has started.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">kickPlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static MatchmakingKickResponse kickPlayer(
    Client& client,
    const std::string& playerToken,
    int playerId
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Kicks a player from matchmaking lobby (Host Only). Cannot kick yourself or after matchmaking has started.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">updateMatchmakingPassword</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">static SuccessResponse updateMatchmakingPassword(
    Client& client,
    const std::string& playerToken,
    const std::optional&lt;std::string&gt;& password = std::nullopt
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates the matchmaking password (Host Only). Cannot change password after matchmaking has started. Use nullopt to remove password.</p>
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
                                <h5 class="text-yellow-400 font-mono text-sm mb-2">getLeaderboard</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-cpp">template&lt;typename T = nlohmann::json&gt;
static LeaderboardResponse&lt;T&gt; getLeaderboard(
    Client& client,
    const std::vector&lt;std::string&gt;& sortBy,
    int limit = 10
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets ranked leaderboard with configurable sorting, limit (1-100), and typed player data support.</p>
                                <div class="mt-3 p-3 bg-yellow-900/20 rounded border border-yellow-700/50">
                                    <p class="text-yellow-200 text-xs font-medium mb-2">Example Usage:</p>
                                    <pre class="text-xs text-yellow-100 overflow-x-auto"><code class="language-cpp">// Sort by level, then score
auto response = Leaderboard::getLeaderboard&lt;PlayerData&gt;(
    client, 
    {"level", "score"}, 
    10
);

for (const auto& entry : response.leaderboard) {
    std::cout << "#" << entry.rank << " - " << entry.playerName << std::endl;
    // Access typed player data: entry.playerData
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

        // Download functionality for C++ SDK files
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
        
        document.getElementById('downloadCppSdk').addEventListener('click', (event) => {
            triggerDownload('SDK.zip', 'CPP-SDK.zip');
        });
        
        document.getElementById('downloadCppExample').addEventListener('click', (event) => {
            triggerDownload('Game.cpp', 'CPP-Game.cpp');
        });
    </script>

    <?php require_once '../php/footer.php'; ?>
</body>
</html>
