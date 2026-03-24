<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = ".NET Multiplayer API – System.Text.Json";
$description = ".NET multiplayer API with System.Text.Json support. Complete SDK and documentation for C# developers.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/logo.png";
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Platform-specific meta tags for better social sharing
$platform_name = ".NET Multiplayer API";
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
            --primary-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6);
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
                .NET SDK for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">Modern C# Applications</span>
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto mb-10">
                Modern .NET SDK with System.Text.Json support. 
                Async/await patterns, full serialization, and seamless integration with your C# projects.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#sdk" class="btn-primary text-white px-8 py-4 rounded-xl text-lg font-semibold">
                    <i class="fas fa-download mr-2"></i>Download .NET SDK
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
                <h3 class="text-4xl font-black text-white mb-6">.NET-Specific Features</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Built for modern C# development</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-bolt text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 text-center">Async/Await</h4>
                    <p class="text-white/80 text-center">Modern async/await patterns with Task-based operations for responsive applications.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-code text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 text-center">System.Text.Json</h4>
                    <p class="text-white/80 text-center">Full serialization support with System.Text.Json for modern .NET applications.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-transform">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center mb-6 mx-auto">
                        <i class="fas fa-shield-alt text-2xl text-white"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3 text-center">Type Safe</h4>
                    <p class="text-white/80 text-center">Strong typing with generics, nullable reference types, and comprehensive error handling.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SDK Section -->
    <section id="sdk" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">.NET SDK</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Complete System.Text.Json SDK for C# developers</p>
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
                    <p class="text-white/80 mb-8">
                        Modern .NET SDK with System.Text.Json support designed specifically for C# developers. 
                        Includes comprehensive multiplayer functionality with async/await patterns, authentication, 
                        and full serialization capabilities for modern applications.
                    </p>
                    
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
                                <div class="text-xs opacity-80">Game.cs Demo - 20KB</div>
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
                    <div class="bg-green-900/20 rounded-lg border border-green-800/50 p-6">
                        <h5 class="text-green-300 font-bold mb-6 flex items-center">
                            <i class="fas fa-rocket mr-2"></i> Quick Installation Guide
                        </h5>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Step 1 -->
                            <div class="bg-green-800/20 p-4 rounded-lg border border-green-700/50">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">1</div>
                                    <h6 class="text-green-200 font-bold">Download & Add SDK</h6>
                                </div>
                                <ol class="text-green-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Click "Download .NET SDK" button</li>
                                    <li>Save <code class="bg-green-900/50 px-1 py-0.5 rounded">SDK.cs</code></li>
                                    <li>In Visual Studio: Project → Add Existing Item</li>
                                    <li>Select the downloaded SDK file</li>
                                    <li>Verify file appears in Solution Explorer</li>
                                </ol>
                                <div class="mt-3 p-2 bg-green-900/30 rounded border border-green-700/50">
                                    <p class="text-green-200 text-xs"><i class="fas fa-lightbulb mr-1"></i> Tip: Create a "Models" folder to organize your files</p>
                                </div>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="bg-green-800/20 p-4 rounded-lg border border-green-700/50">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">2</div>
                                    <h6 class="text-green-200 font-bold">Setup Project</h6>
                                </div>
                                <ol class="text-green-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Create new C# Console Application</li>
                                    <li>Name it "MultiplayerDemo" or similar</li>
                                    <li>Ensure .NET 6.0 or later is selected</li>
                                    <li>Add System.Text.Json NuGet package</li>
                                    <li>Add SDK.cs to your project</li>
                                </ol>
                                <div class="mt-3 p-2 bg-green-900/30 rounded border border-green-700/50">
                                    <p class="text-green-200 text-xs"><i class="fas fa-lightbulb mr-1"></i> Tip: Use .NET CLI: dotnet add package System.Text.Json</p>
                                </div>
                            </div>
                            
                            <!-- Step 3 -->
                            <div class="bg-green-800/20 p-4 rounded-lg border border-green-700/50">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">3</div>
                                    <h6 class="text-green-200 font-bold">Configure & Initialize</h6>
                                </div>
                                <ol class="text-green-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Get your API token from dashboard</li>
                                    <li>Set token in SDK constructor (Api Token)</li>
                                    <li>Get your private API token for player operations</li>
                                    <li>Set private token in SDK constructor (Private Api Token)</li>
                                    <li>Download example code for reference</li>
                                    <li>Copy example to Program.cs</li>
                                    <li>Replace <code class="bg-green-900/50 px-1.5 py-0.5 rounded">YOUR_API_TOKEN</code> and <code class="bg-green-900/50 px-1.5 py-0.5 rounded">YOUR_PRIVATE_API_TOKEN</code> with actual tokens</li>
                                </ol>
                                <div class="mt-3 p-2 bg-green-900/30 rounded border border-green-700/50">
                                    <p class="text-green-200 text-xs"><i class="fas fa-lightbulb mr-1"></i> Tip: Store tokens in appsettings.json for production</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Setup Steps -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-900/20 p-4 rounded-lg border border-blue-700/50">
                                <h6 class="text-blue-300 font-bold mb-3 flex items-center">
                                    <i class="fas fa-play-circle mr-2"></i> Testing Your Setup
                                </h6>
                                <ol class="text-blue-100/80 text-sm space-y-2 list-decimal list-inside">
                                    <li>Build the project (Ctrl+Shift+B)</li>
                                    <li>Run the application (F5)</li>
                                    <li>Check Console for SDK initialization</li>
                                    <li>Verify API calls work (test with RegisterPlayer)</li>
                                    <li>Monitor async operations with await</li>
                                </ol>
                            </div>
                            
                            <div class="bg-purple-900/20 p-4 rounded-lg border border-purple-700/50">
                                <h6 class="text-purple-300 font-bold mb-3 flex items-center">
                                    <i class="fas fa-cogs mr-2"></i> Production Checklist
                                </h6>
                                <ul class="text-purple-100/80 text-sm space-y-2 list-disc list-inside">
                                    <li>Secure API tokens (use configuration files)</li>
                                    <li>Enable proper error handling and logging</li>
                                    <li>Test on target .NET versions</li>
                                    <li>Handle network timeouts gracefully</li>
                                    <li>Implement proper async/await patterns</li>
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
                                    <p class="text-yellow-100/80">Ensure .NET 6.0+ and System.Text.Json package installed</p>
                                </div>
                                <div>
                                    <p class="text-yellow-200 font-medium mb-1">API Timeouts:</p>
                                    <p class="text-yellow-100/80">Check internet connection and API token validity</p>
                                </div>
                                <div>
                                    <p class="text-yellow-200 font-medium mb-1">Json Serialization:</p>
                                    <p class="text-yellow-100/80">Use [JsonPropertyName] attributes for proper mapping</p>
                                </div>
                                <div>
                                    <p class="text-yellow-200 font-medium mb-1">Async Issues:</p>
                                    <p class="text-yellow-100/80">Use await properly and mark methods as async Task</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-400">50+</div>
                            <div class="text-white/60 text-sm">API Methods</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-400">25+</div>
                            <div class="text-white/60 text-sm">Response Classes</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-400">100%</div>
                            <div class="text-white/60 text-sm">System.Text.Json</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-400">Async</div>
                            <div class="text-white/60 text-sm">Task-Based</div>
                        </div>
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
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Complete System.Text.Json-compatible API documentation</p>
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
                            <a href="#http-communication" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">HTTP Communication</a>
                            <a href="#response-classes" class="block text-white/70 hover:text-white hover:bg-white/10 px-3 py-2 rounded-lg transition">Response Classes</a>
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
                        <p class="text-white/80 mb-6">
                            Main SDK class for interacting with the MICHITAI Game API. Handles authentication, player management, game rooms, matchmaking, and actions using modern async/await patterns with System.Text.Json serialization.
                        </p>
                        
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
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerRegisterResponse&gt; RegisterPlayer(
    string name, 
    object playerData
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Registers a new player and returns their ID and private key for authentication.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">AuthenticatePlayer</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerAuthResponse&gt; AuthenticatePlayer(
    string playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Authenticates a player using their private token and returns player information.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">GetAllPlayers</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerListResponse&gt; GetAllPlayers()</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players (requires private API token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">SendPlayerHeartbeatAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerHeartbeatResponse&gt; SendPlayerHeartbeatAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates player heartbeat to maintain online status.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-blue-400 font-mono text-sm mb-2">LogoutPlayerAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerLogoutResponse&gt; LogoutPlayerAsync(
    string gamePlayerToken
)</code></pre>
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
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;GameDataResponse&gt; GetGameData()</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves global game data with System.Text.Json compatible nested objects.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">UpdateGameData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;SuccessResponse&gt; UpdateGameData(
    object data
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates global game data (requires private API token).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">GetPlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;PlayerDataResponse&gt; GetPlayerData(
    string playerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a specific player's data using their authentication token.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-purple-400 font-mono text-sm mb-2">UpdatePlayerData</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;SuccessResponse&gt; UpdatePlayerData(
    string playerToken, 
    object data
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Updates a specific player's data like level, score, and inventory.</p>
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
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomCreateResponse&gt; CreateRoomAsync(
    string gamePlayerToken,
    string roomName,
    string? password = null,
    int maxPlayers = 4
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new game room for real-time multiplayer sessions.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">GetRoomsAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomListResponse&gt; GetRoomsAsync()</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all available game rooms.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">JoinRoomAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomJoinResponse&gt; JoinRoomAsync(
    string gamePlayerToken,
    string roomId,
    string? password = null
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins an existing game room with optional password.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">LeaveRoomAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomLeaveResponse&gt; LeaveRoomAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current game room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">GetRoomPlayersAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;RoomPlayersResponse&gt; GetRoomPlayersAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of all players in the current room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-cyan-400 font-mono text-sm mb-2">SendHeartbeatAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;HeartbeatResponse&gt; SendHeartbeatAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends heartbeat to maintain connection in game room.</p>
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
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionSubmitResponse&gt; SubmitActionAsync(
    string gamePlayerToken,
    string actionType,
    object requestData
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Submits a game action for processing by other players.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">PollActionsAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionPollResponse&gt; PollActionsAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for completed actions from other players.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">GetPendingActionsAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionPendingResponse&gt; GetPendingActionsAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Retrieves a list of pending actions that need to be processed.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-red-400 font-mono text-sm mb-2">CompleteActionAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;ActionCompleteResponse&gt; CompleteActionAsync(
    string actionId,
    string gamePlayerToken,
    ActionCompleteRequest request
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Marks an action as completed with response data.</p>
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
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">SendUpdateAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;UpdateSendResponse&gt; SendUpdateAsync(
    string gamePlayerToken,
    string targetPlayerIds,
    string type,
    object dataJson
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends updates to specific players or all players in the room.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-indigo-400 font-mono text-sm mb-2">PollUpdatesAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;UpdatePollResponse&gt; PollUpdatesAsync(
    string gamePlayerToken,
    string? lastUpdateId = null
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Polls for updates from other players with optional incremental polling.</p>
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
                                <h5 class="text-orange-400 font-mono text-sm mb-2">ListMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingListResponse&gt; ListMatchmakingAsync()</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Lists all available matchmaking lobbies.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">CreateMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingCreateResponse&gt; CreateMatchmakingAsync(
    string gamePlayerToken,
    int maxPlayers,
    bool strictFull,
    bool joinByRequests,
    object? extraJsonString = null
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Creates a new matchmaking lobby with configurable settings.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">RequestJoinMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingJoinRequestResponse&gt; RequestJoinMatchmakingAsync(
    string gamePlayerToken,
    string matchmakingId
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Requests to join a matchmaking lobby that requires host approval.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">RespondToJoinRequestAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingJoinRequestResponse&gt; RespondToJoinRequestAsync(
    string requestId,
    MatchmakingRequestAction action
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Host responds to a join request (approve or reject).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">CheckRequestStatusAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingJoinRequestStatusResponse&gt; CheckRequestStatusAsync(
    string requestId
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Checks the status of a join request.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">GetCurrentMatchmakingStatusAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingCurrentStatusResponse&gt; GetCurrentMatchmakingStatusAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets the current player's matchmaking status and lobby information.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">JoinMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingDirectJoinResponse&gt; JoinMatchmakingAsync(
    string gamePlayerToken,
    string matchmakingId
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Joins a matchmaking lobby directly (only works if lobby doesn't require approval).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">LeaveMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingLeaveResponse&gt; LeaveMatchmakingAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Leaves the current matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">GetMatchmakingPlayersAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingPlayersResponse&gt; GetMatchmakingPlayersAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets all players in the current matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">SendMatchmakingHeartbeatAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingHeartbeatResponse&gt; SendMatchmakingHeartbeatAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Sends heartbeat to maintain connection in matchmaking lobby.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">RemoveMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingRemoveResponse&gt; RemoveMatchmakingAsync(
    string gamePlayerToken
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Removes the matchmaking lobby (host only).</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-orange-400 font-mono text-sm mb-2">StartMatchmakingAsync</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;MatchmakingStartResponse&gt; StartMatchmakingAsync(
    string gamePlayerToken
)</code></pre>
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
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">public Task&lt;LeaderboardResponse&gt; GetLeaderboardAsync(
    string[] sortBy,
    int limit = 10
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Gets ranked leaderboard with configurable sorting and limit.</p>
                                <div class="mt-3 p-3 bg-yellow-900/20 rounded border border-yellow-700/50">
                                    <p class="text-yellow-200 text-xs font-medium mb-2">Example Usage:</p>
                                    <pre class="text-xs text-yellow-100 overflow-x-auto"><code class="language-csharp">// Sort by level, then score
var response = await sdk.GetLeaderboardAsync(
    new[] { "level", "score" }, 
    10
);

foreach (var entry in response.Leaderboard) {
    Console.WriteLine($"#{entry.Rank} - {entry.Player_name}");
}</code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- HTTP Communication -->
                    <div id="http-communication" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-600 to-slate-600 flex items-center justify-center mr-4">
                                <i class="fas fa-network-wired text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">HTTP Communication</h4>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-gray-400 font-mono text-sm mb-2">Send (Internal)</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">private async Task&lt;T&gt; Send&lt;T&gt;(
    HttpMethod method,
    string url,
    object? body = null
) where T : class</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Internal method for sending HTTP requests and deserializing System.Text.Json responses.</p>
                                <div class="mt-3 p-3 bg-gray-900/20 rounded border border-gray-700/50">
                                    <p class="text-gray-200 text-xs font-medium mb-2">Features:</p>
                                    <ul class="text-gray-100/80 text-xs space-y-1 list-disc list-inside">
                                        <li>Automatic authentication with API tokens</li>
                                        <li>System.Text.Json serialization/deserialization</li>
                                        <li>Comprehensive error handling and logging</li>
                                        <li>HTTP client management and timeout handling</li>
                                        <li>Response validation and ApiException throwing</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-gray-400 font-mono text-sm mb-2">Url (Internal)</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">private string Url(
    string endpoint,
    string extra = ""
)</code></pre>
                                <p class="text-gray-400 text-sm mt-2">Constructs URLs with authentication tokens and query parameters.</p>
                                <div class="mt-3 p-3 bg-gray-900/20 rounded border border-gray-700/50">
                                    <p class="text-gray-200 text-xs font-medium mb-2">Example:</p>
                                    <pre class="text-xs text-gray-100 overflow-x-auto"><code class="language-csharp">// Generates: https://api.michitai.com/api/game_players.php/list?api_token=TOKEN&private_token=PRIVATE_TOKEN
var url = Url("game_players.php/list", "&private_token={_apiPrivateToken}");</code></pre>
                                </div>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-gray-400 font-mono text-sm mb-2">JsonSerializerOptions (Internal)</h5>
                                <pre class="text-sm text-white overflow-x-auto"><code class="language-csharp">private readonly JsonSerializerOptions _jsonOptions = new JsonSerializerOptions
{
    PropertyNameCaseInsensitive = true,
    DefaultIgnoreCondition = JsonIgnoreCondition.WhenWritingNull
};</code></pre>
                                <p class="text-gray-400 text-sm mt-2">System.Text.Json configuration for case-insensitive deserialization and null handling.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Response Classes -->
                    <div id="response-classes" class="glass-effect rounded-xl p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-teal-600 to-cyan-600 flex items-center justify-center mr-4">
                                <i class="fas fa-shapes text-2xl text-white"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-white">Response Classes</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-teal-400 font-mono text-sm mb-2">IApiResponse</h5>
                                <p class="text-gray-400 text-sm">Base interface for all API responses with Success and Error properties.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-teal-400 font-mono text-sm mb-2">PlayerRegisterResponse</h5>
                                <p class="text-gray-400 text-sm">Contains player registration results with ID and private key.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-teal-400 font-mono text-sm mb-2">PlayerAuthResponse</h5>
                                <p class="text-gray-400 text-sm">Contains player authentication information and data.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-teal-400 font-mono text-sm mb-2">GameDataResponse</h5>
                                <p class="text-gray-400 text-sm">Contains global game data with nested objects.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-teal-400 font-mono text-sm mb-2">RoomCreateResponse</h5>
                                <p class="text-gray-400 text-sm">Contains room creation results with room ID.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-teal-400 font-mono text-sm mb-2">LeaderboardResponse</h5>
                                <p class="text-gray-400 text-sm">Contains ranked leaderboard data and statistics.</p>
                            </div>
                            
                            <div class="bg-black/50 rounded-lg p-4">
                                <h5 class="text-teal-400 font-mono text-sm mb-2">ApiException</h5>
                                <p class="text-gray-400 text-sm">Custom exception for API errors with response details.</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-teal-900/20 rounded-lg border border-teal-800/50">
                            <h5 class="text-teal-300 font-bold mb-2 flex items-center">
                                <i class="fas fa-info-circle mr-2"></i> System.Text.Json Compatibility
                            </h5>
                            <p class="text-teal-100/80 text-sm">
                                All response classes are designed for System.Text.Json serialization with proper property naming, 
                                nullable reference types, and case-insensitive deserialization for modern .NET applications.
                            </p>
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
