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
    <link rel="icon" type="image/png" href="../logo.png">
    
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
                    <img src="../logo.png" alt="Multiplayer API Logo" class="w-10 h-10 rounded-xl object-contain">
                    <div>
                        <h1 class="text-lg font-bold text-white">Unity Multiplayer API</h1>
                        <p class="text-xs text-white/70">JsonUtility Compatible</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../index.php" class="text-white/80 hover:text-white px-4 py-2 rounded-lg hover:bg-white/10 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Main
                    </a>
                    <a href="https://github.com/levandovici/multiplayer-sdk" target="_blank" class="text-white/80 hover:text-white px-4 py-2 rounded-lg hover:bg-white/10 transition">
                        <i class="fab fa-github mr-2"></i>GitHub
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
                <a href="#sdk" class="btn-primary text-white px-8 py-4 rounded-xl text-lg font-semibold">
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
                    <h4 class="text-xl font-bold text-white mb-3 text-center">Coroutine Based</h4>
                    <p class="text-white/80 text-center">Uses Unity coroutines for async operations, perfect for game loops and frame-based updates.</p>
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
                <!-- Unity SDK -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center mr-4">
                            <i class="fab fa-unity text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">Unity SDK</h4>
                    </div>
                    <p class="text-white/80 mb-6">
                        JsonUtility-compatible SDK designed specifically for Unity. 
                        Handles coroutines, authentication, and proper serialization for mobile builds.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-end mb-4 gap-3 sm:gap-4">
                        <button id="downloadUnitySdk" class="w-full sm:w-auto bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium py-3 sm:py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-download mr-2"></i> Download Unity SDK
                        </button>
                        <button id="downloadUnityExample" class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium py-3 sm:py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-code mr-2"></i> Download Unity Example
                        </button>
                        <a href="https://github.com/levandovici/multiplayer-sdk" target="_blank" class="w-full sm:w-auto bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-medium py-3 sm:py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fab fa-github mr-2"></i> GitHub
                        </a>
                    </div>
                    <div class="relative">
                        <pre><code class="language-csharp"><?php echo htmlspecialchars(file_get_contents('SDK.cs')); ?></code></pre>
                    </div>
                    <script>
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
                            successMsg.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center';
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
                </div>
                
                <!-- Example Usage -->
                <div class="glass-effect p-8 rounded-2xl mt-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-yellow-600 to-orange-600 flex items-center justify-center mr-4">
                            <i class="fas fa-code-branch text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">Unity Example Usage</h4>
                    </div>
                    <p class="text-white/80 mb-6">
                        Here's a complete example of how to use the Unity SDK in your Unity project.
                        This example demonstrates coroutines, JsonUtility usage, and proper Unity integration.
                    </p>
                    
                    <div class="relative mt-8">
                        <pre><code class="language-csharp"><?php echo htmlspecialchars(file_get_contents('Game.cs')); ?></code></pre>
                    </div>
                    
                    <div class="mt-6 p-4 bg-blue-900/20 rounded-lg border border-blue-800/50">
                        <h5 class="text-blue-300 font-medium mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> How to use in Unity
                        </h5>
                        <ol class="text-blue-100/80 text-sm space-y-2 list-decimal list-inside">
                            <li>Create a new Unity project or open existing one</li>
                            <li>Add the downloaded <code class="bg-blue-900/50 px-1 py-0.5 rounded">Unity-SDK.cs</code> file to your project</li>
                            <li>Create an empty GameObject and add the <code class="bg-blue-900/50 px-1.5 py-0.5 rounded">MultiplayerSDK</code> component</li>
                            <li>Copy the example code into a new script and attach to a GameObject</li>
                            <li>Replace <code class="bg-blue-900/50 px-1.5 py-0.5 rounded">YOUR_API_TOKEN</code> with your actual API key</li>
                            <li>Run the scene to see the SDK in action</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Documentation Section -->
    <section id="docs" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">API Documentation</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Complete Unity-compatible API documentation</p>
            </div>
            
            <div class="glass-effect rounded-2xl overflow-hidden">
                <div class="grid grid-cols-12 bg-white/5 border-b border-white/10 p-4 text-white/80 font-medium">
                    <div class="col-span-2">Method</div>
                    <div class="col-span-4">Endpoint</div>
                    <div class="col-span-6">Description</div>
                </div>
                
                <!-- Game Players -->
                <div class="p-4">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Game Players</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_players.php/register</div>
                            <div class="col-span-6">Register player (Unity-compatible JSON responses)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_players.php/login</div>
                            <div class="col-span-6">Authenticate player (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_players.php/heartbeat</div>
                            <div class="col-span-6">Update player heartbeat (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_players.php/logout</div>
                            <div class="col-span-6">Logout player (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_players.php/list</div>
                            <div class="col-span-6">List all players (Unity-compatible)</div>
                        </div>
                    </div>
                </div>

                <!-- Game Data -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Game Data</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_data.php/game/get</div>
                            <div class="col-span-6">Get game data (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_data.php/game/update</div>
                            <div class="col-span-6">Update game data (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_data.php/player/get</div>
                            <div class="col-span-6">Get player data (returns player_data_json string)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_data.php/player/update</div>
                            <div class="col-span-6">Update player data (Unity-compatible)</div>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Leaderboard</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/leaderboard.php</div>
                            <div class="col-span-6">Get ranked leaderboard (Unity-compatible responses)</div>
                        </div>
                    </div>
                </div>

                <!-- Server Data -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Server Data</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/time.php</div>
                            <div class="col-span-6">Get server time (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/time.php?utc=+1</div>
                            <div class="col-span-6">Get server time +1 hour offset (Unity-compatible)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Matchmaking -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Matchmaking</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/list</div>
                            <div class="col-span-6">List all available matchmaking lobbies (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/create</div>
                            <div class="col-span-6">Create matchmaking (extra_json_string for JsonUtility)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/{ID}/request</div>
                            <div class="col-span-6">Request to join matchmaking (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/{ID}/response</div>
                            <div class="col-span-6">Respond to join request (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/current</div>
                            <div class="col-span-6">Get current matchmaking status (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/{ID}/join</div>
                            <div class="col-span-6">Join matchmaking directly (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/leave</div>
                            <div class="col-span-6">Leave current matchmaking (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/remove</div>
                            <div class="col-span-6">Remove matchmaking lobby (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/matchmaking.php/start</div>
                            <div class="col-span-6">Start game from matchmaking (Unity-compatible)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Game Rooms -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Game Rooms</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_room.php/rooms</div>
                            <div class="col-span-6">Create game room (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_room.php/rooms</div>
                            <div class="col-span-6">List all game rooms (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_room.php/rooms/{ID}/join</div>
                            <div class="col-span-6">Join existing room (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_room.php/heartbeat</div>
                            <div class="col-span-6">Send room heartbeat (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_room.php/actions</div>
                            <div class="col-span-6">Submit game action (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_room.php/actions/poll</div>
                            <div class="col-span-6">Get actions result (Unity-compatible)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/unity/game_room.php/current</div>
                            <div class="col-span-6">Get current room status (Unity-compatible)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- REST API -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="glass-effect p-8 rounded-2xl">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-blue-600 flex items-center justify-center mr-4">
                        <i class="fas fa-code text-2xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white">Multiplayer API <span class="font-light">– Core Cells</span></h1>
                </div>
                <p class="text-white/80 mb-6">
                    Use our REST API directly from any platform or language. 
                    All endpoints return JSON responses with consistent error handling.
                </p>

                <div class="space-y-4">
                    <!-- Auto-sized container -->
                    <div class="space-y-4">
                    <!-- 1. Register Player -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_players.php/register?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Creates a new player in game. Returns a <code>player_id</code> and <code>private_key</code> needed for future requests.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "player_name": "TestPlayer",
  "player_data_json": "{\"level\":1,\"score\":0}"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "player_id": "123",
  "private_key": "abc123...",
  "player_name": "TestPlayer",
  "game_id": 1
}</code></pre>
                    </div>

                    <!-- 2. Login Player -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/unity/game_players.php/login?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Authenticates player and returns player information with JsonUtility-compatible data.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "player": {
    "id": 123,
    "game_id": 1,
    "player_name": "TestPlayer",
    "player_data_json": "{\"level\":5,\"score\":100}",
    "is_active": 1,
    "last_login": "2023-01-01 12:00:00",
    "last_heartbeat": "2023-01-01 12:00:00",
    "last_logout": null,
    "created_at": "2023-01-01 10:00:00",
    "updated_at": "2023-01-01 12:00:00"
  }
}</code></pre>
                    </div>

                    <!-- 3. Player Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_players.php/heartbeat?api_token=YOUR_API_TOKEN&game_player_token=YOUR_PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Update player heartbeat to track activity and maintain online status.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Heartbeat updated",
  "last_heartbeat": "2026-03-13 09:46:13"
}</code></pre>
                    </div>

                    <!-- 4. Player Logout -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_players.php/logout?api_token=YOUR_API_TOKEN&game_player_token=YOUR_PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Logout player and update last_logout timestamp. Sets is_active to 0.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Player logged out successfully",
  "last_logout": "2026-03-13 09:46:37"
}</code></pre>
                    </div>

                    <!-- 5. List Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_players.php/list?api_token=YOUR_API_TOKEN&api_private_token=YOUR_PRIVATE_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves a list of all players in game. Useful for admin dashboards or multiplayer matchmaking.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "count": 5,
  "players": [
    {
      "id": 123,
      "player_name": "TestPlayer",
      "is_active": 1,
      "last_login": "2023-01-01 12:00:00",
      "last_logout": null,
      "last_heartbeat": "2023-01-01 12:00:00",
      "created_at": "2023-01-01 10:00:00"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 6. Get Game Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_data.php/game/get?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves global game data with JsonUtility-compatible JSON string.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "type": "game",
  "game_id": 1,
  "data_json": "{\"difficulty\":\"normal\",\"max_players\":10}"
}</code></pre>
                    </div>

                    <!-- 7. Update Game Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/unity/game_data.php/game/update?api_token=YOUR_API_TOKEN&api_private_token=YOUR_PRIVATE_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Updates global game data. Requires API private token for security.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "difficulty": "hard",
  "max_players": 12,
  "last_updated": "2023-01-01T12:00:00Z"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Game data updated successfully",
  "updated_at": "2023-01-01 12:00:00"
}</code></pre>
                    </div>

                    <!-- 8. Get Player Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_data.php/player/get?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves player-specific data as JsonUtility-compatible JSON string.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "type": "player",
  "player_id": 123,
  "player_name": "TestPlayer",
  "data_json": "{\"level\":5,\"score\":100,\"inventory\":[\"sword\",\"potion\"]}"
}</code></pre>
                    </div>

                    <!-- 9. Update Player Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/unity/game_data.php/player/update?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Updates player-specific data like level, score, inventory, and last played timestamp.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "level": 6,
  "score": 150,
  "last_played": "2023-01-01T12:00:00Z"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Player data updated successfully",
  "updated_at": "2023-01-01 12:00:00"
}</code></pre>
                    </div>

                    <!-- 10. Get Server Time -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/time.php?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets current server time in UTC. Useful for time synchronization between client and server.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "utc": "2023-01-01T12:00:00+00:00",
  "timestamp": 1672574400,
  "readable": "2023-01-01 12:00:00 UTC"
}</code></pre>
                    </div>

                    <!-- 11. Get Server Time with Offset -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/time.php?api_token=YOUR_API_TOKEN&utc=1</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets server time with UTC offset. Useful for displaying local time to users.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "utc": "2023-01-01T13:00:00+01:00",
  "timestamp": 1672578000,
  "readable": "2023-01-01 13:00:00 UTC",
  "offset": {
    "offset_hours": 1,
    "offset_string": "+1",
    "original_utc": "2023-01-01T12:00:00+00:00",
    "original_timestamp": 1672574400
  }
}</code></pre>
                    </div>

                    <!-- 12. Create Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_room.php/rooms?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Creates a new game room for real-time multiplayer sessions.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "room_name": "Test Room",
  "password": "secret123",
  "max_players": 4
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "room_id": "abc123...",
  "room_name": "Test Room",
  "is_host": true
}</code></pre>
                    </div>

                    <!-- 13. List Rooms -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_room.php/rooms?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Lists all available game rooms.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "rooms": [
    {
      "room_id": "abc123...",
      "room_name": "Test Room",
      "max_players": 4,
      "current_players": 2,
      "has_password": true
    }
  ]
}</code></pre>
                    </div>

                    <!-- 14. Join Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_room.php/rooms/ROOM_ID/join?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Joins an existing game room.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "password": "secret123"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "room_id": "abc123...",
  "message": "Successfully joined the room"
}</code></pre>
                    </div>

                    <!-- 15. List Room Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_room.php/players?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Lists all players in the current room.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "players": [
    {
      "player_id": "123",
      "player_name": "Player1",
      "is_host": true,
      "is_online": true,
      "last_heartbeat": "2023-01-01 12:00:00"
    }
  ],
  "last_updated": "2023-01-01T12:00:00+00:00"
}</code></pre>
                    </div>

                    <!-- 16. Leave Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_room.php/leave?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Leaves the current game room.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Successfully left the room"
}</code></pre>
                    </div>

                    <!-- 17. Room Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_room.php/heartbeat?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Sends heartbeat to maintain connection in game room.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "status": "ok"
}</code></pre>
                    </div>

                    <!-- 18. Get Current Room Status -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_room.php/current?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets current room status and player information.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "in_room": true,
  "room": {
    "room_id": "abc123...",
    "room_name": "Test Room",
    "is_host": false,
    "is_online": true,
    "max_players": 4,
    "current_players": 2,
    "has_password": true,
    "is_active": true,
    "player_name": "Player1",
    "joined_at": "2023-01-01 12:00:00",
    "last_heartbeat": "2023-01-01 12:00:00",
    "room_created_at": "2023-01-01 11:00:00",
    "room_last_activity": "2023-01-01 12:00:00"
  },
  "pending_actions": [],
  "pending_updates": []
}</code></pre>
                    </div>

                    <!-- 19. Submit Action -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_room.php/actions?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Submits a game action for processing by other players.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "action_type": "player_ready",
  "request_data_json": "{\"player_id\":\"123\",\"ready\":true,\"timestamp\":\"2023-01-01T12:00:00Z\"}"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "action_id": "action123...",
  "status": "pending"
}</code></pre>
                    </div>

                    <!-- 20. Poll Actions -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_room.php/actions/poll?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Polls for completed actions from other players.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "actions": [
    {
      "action_id": "action123...",
      "action_type": "game_start",
      "response_data_json": "{\"game_mode\":\"competitive\",\"start_time\":\"2023-01-01T12:00:00Z\"}",
      "status": "completed"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 21. Get Pending Actions -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_room.php/actions/pending?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets pending actions that need to be processed.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "actions": [
    {
      "action_id": "action123...",
      "player_id": "456",
      "action_type": "player_ready",
      "request_data_json": "{\"player_id\":\"456\",\"ready\":true,\"timestamp\":\"2023-01-01T12:00:00Z\"}",
      "created_at": "2023-01-01 12:00:00",
      "player_name": "Player2"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 22. Complete Action -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_room.php/actions/ACTION_ID/complete?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Marks an action as completed with response data.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "status": "completed",
  "response_data_json": "{\"result\":\"success\",\"processed_at\":\"2023-01-01T12:00:00Z\"}"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Action completed"
}</code></pre>
                    </div>

                    <!-- 23. Send Update to All Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/game_room.php/updates?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Sends update to all players in the room.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "targetPlayerIds": "all",
  "type": "game_start",
  "dataJson": "{\"game_mode\":\"competitive\",\"start_time\":\"2023-01-01T12:00:00Z\"}"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "updates_sent": 3,
  "update_ids": ["update1...", "update2...", "update3..."],
  "target_players": ["123", "456", "789"]
}</code></pre>
                    </div>

                    <!-- 24. Poll Updates -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/game_room.php/updates/poll?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Polls for updates from other players.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "updates": [
    {
      "update_id": "update1...",
      "from_player_id": "123",
      "type": "game_start",
      "data_json": "{\"game_mode\":\"competitive\",\"start_time\":\"2023-01-01T12:00:00Z\"}",
      "created_at": "2023-01-01 12:00:00"
    }
  ],
  "last_update_id": "update1..."
}</code></pre>
                    </div>

                    <!-- 25. List Matchmaking Lobbies -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/matchmaking.php/list?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Lists all available matchmaking lobbies that are not full and not started.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "lobbies": [
    {
      "matchmaking_id": "mm123...",
      "host_player_id": "123",
      "max_players": 4,
      "strict_full": true,
      "extra_json_string": "{\"minLevel\":5,\"rank\":\"silver\"}",
      "created_at": "2023-01-01 11:00:00",
      "last_heartbeat": "2023-01-01 12:00:00",
      "current_players": 2,
      "host_name": "HostPlayer"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 26. Create Matchmaking Lobby -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/create?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Creates a new matchmaking lobby. The creating player automatically becomes the host.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "maxPlayers": 4,
  "strictFull": true,
  "joinByRequests": false,
  "extraJsonString": "{\"minLevel\":5,\"rank\":\"silver\",\"gameMode\":\"competitive\"}"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "matchmaking_id": "mm123...",
  "max_players": 4,
  "strict_full": true,
  "join_by_requests": false,
  "is_host": true
}</code></pre>
                    </div>

                    <!-- 27. Request to Join Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/MM_ID/request?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Requests to join a matchmaking lobby that requires host approval.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "request_id": "req123...",
  "message": "Join request sent to host"
}</code></pre>
                    </div>

                    <!-- 28. Join Matchmaking Directly -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/MM_ID/join?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Joins a matchmaking lobby directly (only works if lobby doesn't require approval).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "matchmaking_id": "mm123...",
  "message": "Successfully joined matchmaking lobby"
}</code></pre>
                    </div>

                    <!-- 29. Leave Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/leave?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Leaves the current matchmaking lobby.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Successfully left matchmaking lobby"
}</code></pre>
                    </div>

                    <!-- 30. Get Matchmaking Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/matchmaking.php/players?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets all players in the current matchmaking lobby.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "players": [
    {
      "player_id": "123",
      "joined_at": "2023-01-01 11:30:00",
      "last_heartbeat": "2023-01-01 12:00:00",
      "status": "active",
      "player_name": "Player1",
      "seconds_since_heartbeat": 0,
      "is_host": true
    }
  ],
  "last_updated": "2023-01-01T12:00:00+00:00"
}</code></pre>
                    </div>

                    <!-- 31. Matchmaking Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/heartbeat?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Sends heartbeat to maintain connection in matchmaking lobby.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "status": "ok"
}</code></pre>
                    </div>

                    <!-- 32. Remove Matchmaking Lobby -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/remove?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Removes the matchmaking lobby (host only).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Matchmaking lobby removed successfully"
}</code></pre>
                    </div>

                    <!-- 33. Start Game from Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/start?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Starts a game from matchmaking lobby (host only).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "room_id": "room123...",
  "room_name": "Game from Matchmaking abc123",
  "players_transferred": 4,
  "message": "Game started successfully"
}</code></pre>
                    </div>

                    <!-- 34. Get Current Matchmaking Status -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/matchmaking.php/current?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets the current player's matchmaking status and lobby information.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "in_matchmaking": true,
  "matchmaking": {
    "matchmaking_id": "mm123...",
    "is_host": true,
    "max_players": 4,
    "current_players": 3,
    "strict_full": true,
    "join_by_requests": false,
    "extra_json_string": "{\"minLevel\":5,\"rank\":\"silver\"}",
    "joined_at": "2023-01-01 11:30:00",
    "player_status": "active",
    "last_heartbeat": "2023-01-01 12:00:00",
    "lobby_heartbeat": "2023-01-01 12:00:00",
    "is_started": false,
    "started_at": null
  },
  "pending_requests": []
}</code></pre>
                    </div>

                    <!-- 35. Check Request Status -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/unity/matchmaking.php/REQ_ID/status?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Checks the status of a join request.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "request": {
    "request_id": "req123...",
    "matchmaking_id": "mm123...",
    "status": "approved",
    "requested_at": "2023-01-01 11:45:00",
    "responded_at": "2023-01-01 11:50:00",
    "responded_by": "123",
    "responder_name": "HostPlayer",
    "join_by_requests": false
  }
}</code></pre>
                    </div>

                    <!-- 36. Respond to Join Request -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/matchmaking.php/REQ_ID/response?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Host responds to a join request (approve/reject).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "action": "approve"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Join request approved successfully",
  "request_id": "req123...",
  "action": "approve"
}</code></pre>
                    </div>

                    <!-- 37. Get Leaderboard -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/unity/leaderboard.php?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets ranked leaderboard based on player data. Supports sorting by multiple fields.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "sortBy": ["level","score"],
  "limit": 10
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "leaderboard": [
    {
      "rank": 1,
      "player_id": 123,
      "player_name": "TopPlayer",
      "player_data_json": "{\"level\":50,\"score\":10000,\"rank\":\"diamond\"}"
    }
  ],
  "total": 1,
  "sort_by": ["level", "score"],
  "limit": 10
}</code></pre>
                    </div>

                    <!-- 38. Error Response Example -->
                    <div class="bg-black/50 p-4 rounded-lg border border-red-900/50">
                        <div class="flex items-center text-sm text-red-400 mb-2">
                            <span class="font-mono bg-red-900/50 px-2 py-1 rounded mr-2">ERROR</span>
                            <span class="font-mono">Any endpoint with invalid/missing API key</span>
                        </div>
                            <strong>Description:</strong> All endpoints return consistent error responses when something goes wrong.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Error Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": false,
  "error": "Invalid API token"
}</code></pre>
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
    </script>

    <?php require_once '../php/footer.php'; ?>
</body>
</html>
