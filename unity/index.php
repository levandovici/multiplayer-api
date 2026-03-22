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
                    
                    <!-- Feature Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/5 p-4 rounded-lg border border-white/10">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-cube text-purple-400 mr-2"></i>
                                <h5 class="text-white font-medium">JsonUtility Ready</h5>
                            </div>
                            <p class="text-white/60 text-sm">Full compatibility with Unity's JsonUtility serialization system</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-lg border border-white/10">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-clock text-blue-400 mr-2"></i>
                                <h5 class="text-white font-medium">Coroutine Based</h5>
                            </div>
                            <p class="text-white/60 text-sm">Uses Unity coroutines for async operations and game loops</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-lg border border-white/10">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-gamepad text-green-400 mr-2"></i>
                                <h5 class="text-white font-medium">IL2CPP Safe</h5>
                            </div>
                            <p class="text-white/60 text-sm">Arrays instead of Lists, no reflection for mobile builds</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-lg border border-white/10">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-users text-orange-400 mr-2"></i>
                                <h5 class="text-white font-medium">Multiplayer Ready</h5>
                            </div>
                            <p class="text-white/60 text-sm">Complete player management and matchmaking system</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-lg border border-white/10">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-door-open text-cyan-400 mr-2"></i>
                                <h5 class="text-white font-medium">Game Rooms</h5>
                            </div>
                            <p class="text-white/60 text-sm">Real-time room management with actions and updates</p>
                        </div>
                        <div class="bg-white/5 p-4 rounded-lg border border-white/10">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-trophy text-yellow-400 mr-2"></i>
                                <h5 class="text-white font-medium">Leaderboards</h5>
                            </div>
                            <p class="text-white/60 text-sm">Competitive rankings and player statistics</p>
                        </div>
                    </div>
                    
                    <!-- Download Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mb-6">
                        <button id="downloadUnitySdk" class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
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
                    
                    <!-- Installation Guide -->
                    <div class="bg-blue-900/20 rounded-lg border border-blue-800/50 p-6">
                        <h5 class="text-blue-300 font-bold mb-6 flex items-center">
                            <i class="fas fa-rocket mr-2"></i> Quick Installation Guide
                        </h5>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Step 1 -->
                            <div class="bg-blue-800/20 p-4 rounded-lg border border-blue-700/50">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">1</div>
                                    <h6 class="text-blue-200 font-bold">Download & Add SDK</h6>
                                </div>
                                <ol class="text-blue-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Click "Download Unity SDK" button</li>
                                    <li>Save <code class="bg-blue-900/50 px-1 py-0.5 rounded">SDK.cs</code></li>
                                    <li>In Unity: Assets → Import New Asset</li>
                                    <li>Select the downloaded SDK file</li>
                                    <li>Verify file appears in Project window</li>
                                </ol>
                                <div class="mt-3 p-2 bg-blue-900/30 rounded border border-blue-700/50">
                                    <p class="text-blue-200 text-xs"><i class="fas fa-lightbulb mr-1"></i> Tip: Create a "Scripts" folder to organize your files</p>
                                </div>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="bg-blue-800/20 p-4 rounded-lg border border-blue-700/50">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">2</div>
                                    <h6 class="text-blue-200 font-bold">Setup SDK Component</h6>
                                </div>
                                <ol class="text-blue-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Create empty GameObject (GameObject → Create Empty)</li>
                                    <li>Name it "MultiplayerManager" or similar</li>
                                    <li>Select the GameObject in Hierarchy</li>
                                    <li>Click "Add Component" in Inspector</li>
                                    <li>Search and add "SDK" component</li>
                                </ol>
                                <div class="mt-3 p-2 bg-blue-900/30 rounded border border-blue-700/50">
                                    <p class="text-blue-200 text-xs"><i class="fas fa-lightbulb mr-1"></i> Tip: Place this GameObject in your main scene for persistence</p>
                                </div>
                            </div>
                            
                            <!-- Step 3 -->
                            <div class="bg-blue-800/20 p-4 rounded-lg border border-blue-700/50">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">3</div>
                                    <h6 class="text-blue-200 font-bold">Configure & Initialize</h6>
                                </div>
                                <ol class="text-blue-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Get your API token from dashboard</li>
                                    <li>Set token in SDK Inspector field (Api Token)</li>
                                    <li>Get your private API token for player operations</li>
                                    <li>Set private token in SDK Inspector field (Private Api Token)</li>
                                    <li>Create new C# script for game logic</li>
                                    <li>Download example code for reference</li>
                                    <li>Replace <code class="bg-blue-900/50 px-1.5 py-0.5 rounded">YOUR_API_TOKEN</code> and <code class="bg-blue-900/50 px-1.5 py-0.5 rounded">YOUR_PRIVATE_API_TOKEN</code> with actual tokens</li>
                                </ol>
                                <div class="mt-3 p-2 bg-blue-900/30 rounded border border-blue-700/50">
                                    <p class="text-blue-200 text-xs"><i class="fas fa-lightbulb mr-1"></i> Tip: Store API token in ScriptableObject for production</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Setup Steps -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-green-900/20 p-4 rounded-lg border border-green-700/50">
                                <h6 class="text-green-300 font-bold mb-3 flex items-center">
                                    <i class="fas fa-play-circle mr-2"></i> Testing Your Setup
                                </h6>
                                <ol class="text-green-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Attach your game script to a GameObject</li>
                                    <li>Add reference to SDK component</li>
                                    <li>Press Play in Unity Editor</li>
                                    <li>Check Console for SDK initialization</li>
                                    <li>Verify API calls work (test with RegisterPlayer)</li>
                                </ol>
                            </div>
                            
                            <div class="bg-purple-900/20 p-4 rounded-lg border border-purple-700/50">
                                <h6 class="text-purple-300 font-bold mb-3 flex items-center">
                                    <i class="fas fa-cogs mr-2"></i> Production Checklist
                                </h6>
                                <ul class="text-purple-100/80 text-sm space-y-2 list-disc list-inside">
                                    <li>Secure API token (don't hardcode in builds)</li>
                                    <li>Enable IL2CPP for mobile builds</li>
                                    <li>Test on target platforms</li>
                                    <li>Handle network errors gracefully</li>
                                    <li>Implement proper authentication flow</li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Common Issues -->
                        <div class="mt-6 p-4 bg-yellow-900/20 rounded-lg border border-yellow-700/50">
                            <h6 class="text-yellow-300 font-bold mb-2 flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Common Issues & Solutions
                            </h6>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-yellow-200 font-medium mb-1">Compilation Errors:</p>
                                    <p class="text-yellow-100/80">Ensure .NET Framework compatibility in Player Settings</p>
                                </div>
                                <div>
                                    <p class="text-yellow-200 font-medium mb-1">API Timeouts:</p>
                                    <p class="text-yellow-100/80">Check internet connection and API token validity</p>
                                </div>
                                <div>
                                    <p class="text-yellow-200 font-medium mb-1">JsonUtility Issues:</p>
                                    <p class="text-yellow-100/80">Use [Serializable] classes and proper field names</p>
                                </div>
                                <div>
                                    <p class="text-yellow-200 font-medium mb-1">Coroutine Problems:</p>
                                    <p class="text-yellow-100/80">Start coroutines from MonoBehaviour instances</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-400">50+</div>
                            <div class="text-white/60 text-sm">API Methods</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-400">25+</div>
                            <div class="text-white/60 text-sm">Response Classes</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-400">100%</div>
                            <div class="text-white/60 text-sm">JsonUtility Compatible</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-400">IL2CPP</div>
                            <div class="text-white/60 text-sm">Mobile Safe</div>
                        </div>
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
                            <a href="#http-communication" class="block text-white/70 hover:text-white py-1 text-sm transition">HTTP Communication</a>
                            <a href="#utility-methods" class="block text-white/70 hover:text-white py-1 text-sm transition">Utility Methods</a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-8">
                    
                    <!-- MultiplayerSDK Class -->
                    <div id="multiplayersdk" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6">
                            <h2 class="text-2xl font-bold text-white">MultiplayerSDK</h2>
                            <p class="text-white/80">Main SDK class for Unity multiplayer functionality</p>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-white mb-3">Description</h3>
                            <p class="text-white/80 mb-6">
                                The MultiplayerSDK class provides complete multiplayer functionality for Unity games. 
                                It handles player management, matchmaking, game rooms, real-time actions, and leaderboards 
                                with full JsonUtility compatibility for Unity serialization.
                            </p>
                            
                            <h3 class="text-lg font-semibold text-white mb-3">Public Methods</h3>
                            <div class="space-y-4">
                                <div class="border-l-4 border-purple-500 pl-4">
                                    <h4 class="text-white font-medium">void SetApiToken(string token)</h4>
                                    <p class="text-white/70 text-sm">Sets the API authentication token for all requests</p>
                                </div>
                                <div class="border-l-4 border-purple-500 pl-4">
                                    <h4 class="text-white font-medium">void SetGamePlayerToken(string token)</h4>
                                    <p class="text-white/70 text-sm">Sets the player authentication token for player-specific operations</p>
                                </div>
                                <div class="border-l-4 border-purple-500 pl-4">
                                    <h4 class="text-white font-medium">string SerializeToJson&lt;T&gt;(T obj)</h4>
                                    <p class="text-white/70 text-sm">Serializes object to JSON using Unity's JsonUtility</p>
                                </div>
                                <div class="border-l-4 border-purple-500 pl-4">
                                    <h4 class="text-white font-medium">T DeserializeFromJson&lt;T&gt;(string json)</h4>
                                    <p class="text-white/70 text-sm">Deserializes JSON to object using Unity's JsonUtility</p>
                                </div>
                            </div>
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
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm"><code>void RegisterPlayer(string playerName, string playerDataJson, Action&lt;RegisterPlayerResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Registers a new player account. The playerDataJson should contain initial player data as a JSON string for Unity compatibility.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">LoginPlayer</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm"><code>void LoginPlayer(Action&lt;LoginResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Authenticates the player using the currently set game player token. Returns complete player information.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendPlayerHeartbeat</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm"><code>void SendPlayerHeartbeat(Action&lt;HeartbeatResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Sends a heartbeat to maintain the player's connection. Call every 30-60 seconds to prevent timeout.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">LogoutPlayer</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm"><code>void LogoutPlayer(Action&lt;LogoutResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Logs out the current player and invalidates their session. Updates last logout timestamp.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">ListPlayers</h3>
                                    <pre class="bg-black/30 text-green-400 p-3 rounded text-sm"><code>void ListPlayers(Action&lt;ListPlayersResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all registered players. Useful for admin dashboards or player discovery systems.</p>
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
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm"><code>void GetGameData(Action&lt;GameDataResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves global game data and settings. Data is returned as JSON string for Unity compatibility.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">UpdateGameData</h3>
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm"><code>void UpdateGameData(string dataJson, Action&lt;UpdateDataResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Updates global game data. The dataJson should contain the fields to update as JSON string.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetPlayerData</h3>
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm"><code>void GetPlayerData(Action&lt;PlayerDataResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves player-specific data. Requires player authentication token to be set.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">UpdatePlayerData</h3>
                                    <pre class="bg-black/30 text-blue-400 p-3 rounded text-sm"><code>void UpdatePlayerData(string dataJson, Action&lt;UpdateDataResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Updates player-specific data. The dataJson should contain fields to update as JSON string.</p>
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
                                    <pre class="bg-black/30 text-indigo-400 p-3 rounded text-sm"><code>void GetServerTime(Action&lt;TimeResponse&gt; callback, int utcOffset = 0)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves server time with optional UTC offset. Useful for time synchronization and anti-cheat measures.</p>
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
                                    <h3 class="text-white font-semibold mb-2">CreateRoom</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm"><code>void CreateRoom(string roomName, string password, int maxPlayers, Action&lt;CreateRoomResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Creates a new game room. The creating player becomes the room host.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">ListRooms</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm"><code>void ListRooms(Action&lt;ListRoomsResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves all available game rooms. Useful for server browser functionality.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">JoinRoom</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm"><code>void JoinRoom(string roomId, string password, Action&lt;JoinRoomResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Joins an existing room. Password required for private rooms.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">ListRoomPlayers</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm"><code>void ListRoomPlayers(Action&lt;ListRoomPlayersResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all players in the current room. Useful for displaying player lists and checking occupancy.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">LeaveRoom</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm"><code>void LeaveRoom(Action&lt;BaseResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Leaves the current room and updates player status.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendRoomHeartbeat</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm"><code>void SendRoomHeartbeat(Action&lt;BaseResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Sends heartbeat to maintain room connection. Call every 30-60 seconds while in room.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetCurrentRoomStatus</h3>
                                    <pre class="bg-black/30 text-orange-400 p-3 rounded text-sm"><code>void GetCurrentRoomStatus(Action&lt;CurrentRoomStatusResponse&gt; callback)</code></pre>
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
                                    <h3 class="text-white font-semibold mb-2">SubmitAction</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm"><code>void SubmitAction(string actionType, string requestDataJson, Action&lt;SubmitActionResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Submits an action for processing by other players. Actions are processed asynchronously.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">PollActions</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm"><code>void PollActions(Action&lt;PollActionsResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Polls for completed actions from other players. Call periodically to check for new actions.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetPendingActions</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm"><code>void GetPendingActions(Action&lt;GetPendingActionsResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Gets actions awaiting processing by the current player. Call to find actions to complete.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">CompleteAction</h3>
                                    <pre class="bg-black/30 text-teal-400 p-3 rounded text-sm"><code>void CompleteAction(string actionId, string status, string responseDataJson, Action&lt;CompleteActionResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Marks an action as completed with processing results. Used after processing pending actions.</p>
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
                                    <h3 class="text-white font-semibold mb-2">SendUpdate</h3>
                                    <pre class="bg-black/30 text-cyan-400 p-3 rounded text-sm"><code>void SendUpdate(string targetPlayerIds, string type, string dataJson, Action&lt;SendUpdateResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Sends real-time updates to specific players or all players. Target can be "all" or JSON array of IDs.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">PollUpdates</h3>
                                    <pre class="bg-black/30 text-cyan-400 p-3 rounded text-sm"><code>void PollUpdates(string lastUpdateId, Action&lt;PollUpdatesResponse&gt; callback)</code></pre>
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
                                    <h3 class="text-white font-semibold mb-2">ListMatchmaking</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void ListMatchmaking(Action&lt;ListMatchmakingResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all available matchmaking lobbies. Useful for lobby browser functionality.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">CreateMatchmaking</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void CreateMatchmaking(int maxPlayers, bool strictFull, bool joinByRequests, string extraJsonString, Action&lt;CreateMatchmakingResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Creates a new matchmaking lobby. The creating player becomes the lobby host.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">RequestJoinMatchmaking</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void RequestJoinMatchmaking(string matchmakingId, Action&lt;JoinRequestResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Requests to join a lobby that requires host approval. Returns request ID for tracking.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">JoinMatchmaking</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void JoinMatchmaking(string matchmakingId, Action&lt;JoinMatchmakingResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Joins a matchmaking lobby directly. Only works if lobby allows direct join.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">LeaveMatchmaking</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void LeaveMatchmaking(Action&lt;LeaveMatchmakingResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Leaves the current matchmaking lobby and updates player status.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetMatchmakingPlayers</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void GetMatchmakingPlayers(Action&lt;GetMatchmakingPlayersResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Lists all players in the current matchmaking lobby. Useful for displaying player lists.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendMatchmakingHeartbeat</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void SendMatchmakingHeartbeat(Action&lt;BaseResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Sends heartbeat to maintain lobby connection. Call every 30-60 seconds while in lobby.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">RemoveMatchmaking</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void RemoveMatchmaking(Action&lt;BaseResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Removes the matchmaking lobby and kicks all players. Only the lobby host can call this.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">StartMatchmaking</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void StartMatchmaking(Action&lt;StartMatchmakingResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Starts a game from matchmaking lobby. Transfers all players to a new game room.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">GetCurrentMatchmakingStatus</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void GetCurrentMatchmakingStatus(Action&lt;CurrentMatchmakingStatusResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Gets comprehensive lobby state including player status and pending requests.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">CheckRequestStatus</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void CheckRequestStatus(string requestId, Action&lt;CheckRequestStatusResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Checks the status of a join request. Useful for tracking approval/rejection status.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">RespondToRequest</h3>
                                    <pre class="bg-black/30 text-pink-400 p-3 rounded text-sm"><code>void RespondToRequest(string requestId, string action, Action&lt;RespondToRequestResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Responds to a join request (approve/reject). Only the lobby host can call this.</p>
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
                                    <h3 class="text-white font-semibold mb-2">GetLeaderboard</h3>
                                    <pre class="bg-black/30 text-yellow-400 p-3 rounded text-sm"><code>void GetLeaderboard(string[] sortBy, int limit, Action&lt;LeaderboardResponse&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Retrieves ranked players with configurable sorting criteria. Supports multiple sort fields.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- HTTP Communication Section -->
                    <div id="http-communication" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-600 to-gray-700 p-6">
                            <h2 class="text-2xl font-bold text-white">HTTP Communication</h2>
                            <p class="text-white/80">Internal HTTP request handling methods</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendRequest&lt;T&gt;</h3>
                                    <pre class="bg-black/30 text-gray-400 p-3 rounded text-sm"><code>private void SendRequest&lt;T&gt;(string url, string method, string bodyJson, Action&lt;T&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Internal method for sending HTTP requests with authentication and error handling.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SendRequestCoroutine&lt;T&gt;</h3>
                                    <pre class="bg-black/30 text-gray-400 p-3 rounded text-sm"><code>private IEnumerator SendRequestCoroutine&lt;T&gt;(string url, string method, string bodyJson, Action&lt;T&gt; callback)</code></pre>
                                    <p class="text-white/70 mt-2">Internal coroutine for async HTTP requests with comprehensive error handling.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Utility Methods Section -->
                    <div id="utility-methods" class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6">
                            <h2 class="text-2xl font-bold text-white">Utility Methods</h2>
                            <p class="text-white/80">Helper methods for JSON serialization and token management</p>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SetApiToken</h3>
                                    <pre class="bg-black/30 text-emerald-400 p-3 rounded text-sm"><code>public void SetApiToken(string token)</code></pre>
                                    <p class="text-white/70 mt-2">Sets the API authentication token. Required for all API calls.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SetGamePlayerToken</h3>
                                    <pre class="bg-black/30 text-emerald-400 p-3 rounded text-sm"><code>public void SetGamePlayerToken(string token)</code></pre>
                                    <p class="text-white/70 mt-2">Sets the player authentication token. Required for player-specific operations.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">SerializeToJson&lt;T&gt;</h3>
                                    <pre class="bg-black/30 text-emerald-400 p-3 rounded text-sm"><code>public string SerializeToJson&lt;T&gt;(T obj)</code></pre>
                                    <p class="text-white/70 mt-2">Serializes object to JSON using Unity's JsonUtility. Ensures Unity compatibility.</p>
                                </div>
                                
                                <div>
                                    <h3 class="text-white font-semibold mb-2">DeserializeFromJson&lt;T&gt;</h3>
                                    <pre class="bg-black/30 text-emerald-400 p-3 rounded text-sm"><code>public T DeserializeFromJson&lt;T&gt;(string json)</code></pre>
                                    <p class="text-white/70 mt-2">Deserializes JSON to object using Unity's JsonUtility. For parsing API responses.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Response Classes -->
                    <div class="glass-effect rounded-2xl overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-600 to-gray-700 p-6">
                            <h2 class="text-2xl font-bold text-white">Response Classes</h2>
                            <p class="text-white/80">Data classes for API responses with JsonUtility compatibility</p>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">BaseResponse</h4>
                                    <p class="text-white/60 text-sm">Base class for all API responses with success/error fields</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">RegisterPlayerResponse</h4>
                                    <p class="text-white/60 text-sm">Response from player registration with player credentials</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">LoginResponse</h4>
                                    <p class="text-white/60 text-sm">Response from player login with complete player info</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">CreateRoomResponse</h4>
                                    <p class="text-white/60 text-sm">Response from room creation with room details</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">CreateMatchmakingResponse</h4>
                                    <p class="text-white/60 text-sm">Response from matchmaking lobby creation</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">LeaderboardResponse</h4>
                                    <p class="text-white/60 text-sm">Response from leaderboard with ranked players</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">TimeResponse</h4>
                                    <p class="text-white/60 text-sm">Response from server time with timezone information</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">GameDataResponse</h4>
                                    <p class="text-white/60 text-sm">Response from game data retrieval</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">PlayerDataResponse</h4>
                                    <p class="text-white/60 text-sm">Response from player data retrieval</p>
                                </div>
                                <div class="bg-black/20 p-4 rounded">
                                    <h4 class="text-white font-medium">UpdateDataResponse</h4>
                                    <p class="text-white/60 text-sm">Response from data update operations</p>
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
