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
                    <img src="../logo.png" alt="Multiplayer API Logo" class="w-10 h-10 rounded-xl object-contain">
                    <div>
                        <h1 class="text-lg font-bold text-white">.NET Multiplayer API</h1>
                        <p class="text-xs text-white/70">System.Text.Json</p>
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
                <!-- .NET SDK -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center mr-4">
                            <i class="fab fa-microsoft text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">.NET SDK</h4>
                    </div>
                    <p class="text-white/80 mb-6">
                        Modern .NET SDK with System.Text.Json support. 
                        Handles async/await, authentication, and full serialization capabilities.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-end mb-4 gap-3 sm:gap-4">
                        <button id="downloadDotnetSdk" class="w-full sm:w-auto bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium py-3 sm:py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-download mr-2"></i> Download .NET SDK
                        </button>
                        <button id="downloadDotnetExample" class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white font-medium py-3 sm:py-2 px-6 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center">
                            <i class="fas fa-code mr-2"></i> Download .NET Example
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
                    
                    document.getElementById('downloadDotnetSdk').addEventListener('click', (event) => {
                        triggerDownload('SDK.cs', 'NET-SDK.cs');
                    });
                    
                    document.getElementById('downloadDotnetExample').addEventListener('click', (event) => {
                        triggerDownload('Game.cs', 'NET-Game.cs');
                    });
                    </script>
                </div>
                
                <!-- Example Usage -->
                <div class="glass-effect p-8 rounded-2xl mt-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-600 flex items-center justify-center mr-4">
                            <i class="fas fa-code-branch text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">.NET Example Usage</h4>
                    </div>
                    <p class="text-white/80 mb-6">
                        Here's a complete example of how to use the .NET SDK in your C# application.
                        This example demonstrates async/await patterns, System.Text.Json usage, and proper error handling.
                    </p>
                    
                    <div class="relative mt-8">
                        <pre><code class="language-csharp"><?php echo htmlspecialchars(file_get_contents('Game.cs')); ?></code></pre>
                    </div>
                    
                    <div class="mt-6 p-4 bg-green-900/20 rounded-lg border border-green-800/50">
                        <h5 class="text-green-300 font-medium mb-2 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> How to use in .NET
                        </h5>
                        <ol class="text-green-100/80 text-sm space-y-2 list-decimal list-inside">
                            <li>Create a new C# Console Application in Visual Studio</li>
                            <li>Add the downloaded <code class="bg-green-900/50 px-1 py-0.5 rounded">NET-SDK.cs</code> file to your project</li>
                            <li>Copy the example code into your <code class="bg-green-900/50 px-1.5 py-0.5 rounded">Program.cs</code> file</li>
                            <li>Replace <code class="bg-green-900/50 px-1.5 py-0.5 rounded">YOUR_API_TOKEN</code> with your actual API key</li>
                            <li>Run the application to see the SDK in action</li>
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
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Complete .NET-compatible API documentation</p>
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
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_players.php/register</div>
                            <div class="col-span-6">Register player (System.Text.Json responses)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_players.php/login</div>
                            <div class="col-span-6">Authenticate player (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_players.php/heartbeat</div>
                            <div class="col-span-6">Update player heartbeat (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_players.php/logout</div>
                            <div class="col-span-6">Logout player (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_players.php/list</div>
                            <div class="col-span-6">List all players (System.Text.Json)</div>
                        </div>
                    </div>
                </div>

                <!-- Game Data -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Game Data</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_data.php/game/get</div>
                            <div class="col-span-6">Get game data (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_data.php/game/update</div>
                            <div class="col-span-6">Update game data (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_data.php/player/get</div>
                            <div class="col-span-6">Get player data (returns dynamic player_data object)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_data.php/player/update</div>
                            <div class="col-span-6">Update player data (System.Text.Json)</div>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Leaderboard</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/leaderboard.php</div>
                            <div class="col-span-6">Get ranked leaderboard (System.Text.Json responses)</div>
                        </div>
                    </div>
                </div>

                <!-- Server Data -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Server Data</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/time.php</div>
                            <div class="col-span-6">Get server time (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/time.php?utc=+1</div>
                            <div class="col-span-6">Get server time +1 hour offset (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/time.php?utc=-2</div>
                            <div class="col-span-6">Get server time -2 hours offset (System.Text.Json)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Matchmaking -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Matchmaking</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/list</div>
                            <div class="col-span-6">List all available matchmaking lobbies (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/create</div>
                            <div class="col-span-6">Create matchmaking (extra_json_string for dynamic data)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/{ID}/request</div>
                            <div class="col-span-6">Request to join matchmaking (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/{ID}/response</div>
                            <div class="col-span-6">Respond to join request (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/{ID}/status</div>
                            <div class="col-span-6">Check join request status (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/current</div>
                            <div class="col-span-6">Get current matchmaking status (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/{ID}/join</div>
                            <div class="col-span-6">Join matchmaking directly (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/leave</div>
                            <div class="col-span-6">Leave current matchmaking (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/players</div>
                            <div class="col-span-6">List all players in current matchmaking (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/heartbeat</div>
                            <div class="col-span-6">Send matchmaking heartbeat (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/remove</div>
                            <div class="col-span-6">Remove matchmaking lobby (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/matchmaking.php/start</div>
                            <div class="col-span-6">Start game from matchmaking (System.Text.Json)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Game Rooms -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Game Rooms</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/rooms</div>
                            <div class="col-span-6">Create game room (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/rooms</div>
                            <div class="col-span-6">List all game rooms (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/rooms/{ID}/join</div>
                            <div class="col-span-6">Join existing room (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/rooms/players</div>
                            <div class="col-span-6">List all players in current room (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/rooms/leave</div>
                            <div class="col-span-6">Leave current game room (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/heartbeat</div>
                            <div class="col-span-6">Send room heartbeat (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/actions</div>
                            <div class="col-span-6">Submit game action (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/actions/poll</div>
                            <div class="col-span-6">Get actions result (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/actions/pending</div>
                            <div class="col-span-6">View all pending actions (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/actions/{ID}/complete</div>
                            <div class="col-span-6">Complete or reject action (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/updates</div>
                            <div class="col-span-6">Send updates to players (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/updates/poll</div>
                            <div class="col-span-6">Poll for updates (System.Text.Json)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/dotnet/game_room.php/current</div>
                            <div class="col-span-6">Get current room status (System.Text.Json)</div>
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
                            <span class="font-mono">/dotnet/game_players.php/register?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Creates a new player in game. Returns a <code>player_id</code> and <code>private_key</code> needed for future requests.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "player_name": "TestPlayer",
  "player_data": {
    "level": 1,
    "score": 0,
    "inventory": ["sword","shield"]
  }
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "player_id": "3",
  "private_key": "cf24626c48177c7459b7bbcaa86538a93913",
  "player_name": "TestPlayer",
  "game_id": 2
}</code></pre>
                    </div>

                    <!-- 2. Login Player -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/dotnet/game_players.php/login?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Authenticates player and returns player information with System.Text.Json compatible data.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "player": {
    "id": 3,
    "game_id": 2,
    "player_name": "TestPlayer",
    "player_data": {
      "level": 1,
      "score": 0,
      "inventory": ["sword","shield"]
    },
    "is_active": 1,
    "last_login": null,
    "last_heartbeat": null,
    "last_logout": null,
    "created_at": "2026-03-13 09:39:06",
    "updated_at": "2026-03-13 09:39:06"
  }
}</code></pre>
                    </div>

                    <!-- 3. Player Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_players.php/heartbeat?api_token=YOUR_API_TOKEN&game_player_token=YOUR_PLAYER_TOKEN</span>
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
                            <span class="font-mono">/dotnet/game_players.php/logout?api_token=YOUR_API_TOKEN&game_player_token=YOUR_PLAYER_TOKEN</span>
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
                            <span class="font-mono">/dotnet/game_players.php/list?api_token=YOUR_API_TOKEN&api_private_token=YOUR_API_PRIVATE_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves a list of all players in game. Useful for admin dashboards or multiplayer matchmaking.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "count": 3,
  "players": [
    {
      "id": 1,
      "player_name": "TestPlayer",
      "is_active": 1,
      "last_login": null,
      "last_logout": null,
      "last_heartbeat": null,
      "created_at": "2026-03-13 09:37:47"
    },
    {
      "id": 3,
      "player_name": "TestPlayer",
      "is_active": 1,
      "last_login": "2026-03-13 09:40:35",
      "last_logout": null,
      "last_heartbeat": null,
      "created_at": "2026-03-13 09:39:06"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 6. Get Game Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_data.php/game/get?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves global game data with System.Text.Json compatible nested objects.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "type": "game",
  "game_id": 4,
  "data": {
    "text": "hello world",
    "game_settings": {
      "difficulty": "hard",
      "max_players": 10
    },
    "last_updated": "2025-01-13T12:00:00Z"
  }
}</code></pre>
                    </div>

                    <!-- 7. Update Game Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/dotnet/game_data.php/game/update?api_token=YOUR_API_TOKEN&api_private_token=YOUR_API_PRIVATE_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Updates global game data. For example, changing settings or max players. Requires API key authentication.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "game_settings": {
    "difficulty": "hard",
    "max_players": 10
  },
  "last_updated": "2025-01-13T12:00:00Z"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Game data updated successfully",
  "updated_at": "2026-01-13 14:24:23"
}</code></pre>
                    </div>

                    <!-- 8. Get Player Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_data.php/player/get?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves a specific player's data using their <code>private_key</code>. Includes level, score, and inventory.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "type": "player",
  "player_id": 7,
  "player_name": "TestPlayer",
  "data": {
    "level": 1,
    "score": 0,
    "inventory": ["sword","shield"]
  }
}</code></pre>
                    </div>

                    <!-- 9. Update Player Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/dotnet/game_data.php/player/update?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Updates a specific player's data like level, score, inventory, and last played timestamp.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "level": 2,
  "score": 100,
  "inventory": ["sword","shield","potion"],
  "last_played": "2025-01-13T12:30:00Z"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Player data updated successfully",
  "updated_at": "2026-01-13 14:27:10"
}</code></pre>
                    </div>

                    <!-- 10. Get Server Time -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/time.php?api_token=YOUR_API_KEY</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves current server time in multiple formats including UTC timestamp and human-readable format.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "utc": "2026-03-13T14:01:49+00:00",
  "timestamp": 1773410509,
  "readable": "2026-03-13 14:01:49 UTC"
}</code></pre>
                    </div>

                    <!-- 11. Get Server Time with +1 Hour Offset -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/time.php?api_token=YOUR_API_KEY&utc=+1</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves server time with +1 hour offset adjustment.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "utc": "2026-03-13T15:04:17+00:00",
  "timestamp": 1773414257,
  "readable": "2026-03-13 15:04:17 UTC",
  "offset": {
    "offset_hours": 1,
    "offset_string": "+1",
    "original_utc": "2026-03-13T14:04:17+00:00",
    "original_timestamp": 1773410657
  }
}</code></pre>
                    </div>

                    <!-- 12. Get Server Time with -2 Hours Offset -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/time.php?api_token=YOUR_API_KEY&utc=-2</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Retrieves server time with -2 hours offset adjustment.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "utc": "2026-03-13T12:05:13+00:00",
  "timestamp": 1773403513,
  "readable": "2026-03-13 12:05:13 UTC",
  "offset": {
    "offset_hours": -2,
    "offset_string": "-2",
    "original_utc": "2026-03-13T14:05:13+00:00",
    "original_timestamp": 1773410713
  }
}</code></pre>
                    </div>

                    <!-- 13. Create Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_room.php/rooms?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Creates a new game room for real-time multiplayer sessions.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "room_name": "My Game Room",
  "password": "secret123",
  "max_players": 4
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "room_id": "dc3723848639139113ca240958ba0bf8",
  "room_name": "My Game Room",
  "is_host": true
}</code></pre>
                    </div>

                    <!-- 14. List Rooms -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_room.php/rooms?api_token=YOUR_API_TOKEN</span>
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
      "room_id": "dc3723848639139113ca240958ba0bf8",
      "room_name": "My Game Room",
      "max_players": 4,
      "current_players": 1,
      "has_password": 1
    }
  ]
}</code></pre>
                    </div>

                    <!-- 15. Join Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_room.php/rooms/ROOM_ID/join?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
  "room_id": "dc3723848639139113ca240958ba0bf8",
  "message": "Successfully joined the room"
}</code></pre>
                    </div>

                    <!-- 16. List Room Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_room.php/players?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
      "player_id": "48",
      "player_name": "TestPlayer",
      "is_host": 1,
      "is_online": 1,
      "last_heartbeat": "2026-03-09 09:39:34"
    },
    {
      "player_id": "49",
      "player_name": "TestPlayer",
      "is_host": 0,
      "is_online": 1,
      "last_heartbeat": "2026-03-09 09:44:39"
    }
  ],
  "last_updated": "2026-03-09T09:56:21+00:00"
}</code></pre>
                    </div>

                    <!-- 17. Leave Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_room.php/rooms/leave?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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

                    <!-- 18. Room Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_room.php/heartbeat?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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

                    <!-- 19. Submit Action -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_room.php/actions?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Submits a game action for processing by other players.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "action_type": "move",
  "request_data": {
    "x": 10,
    "y": 20
  }
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "action_id": "1c2bbd859e36dc7d7e5e9b4f263c88ce",
  "status": "pending"
}</code></pre>
                    </div>

                    <!-- 20. Poll Actions -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_room.php/actions/poll?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
      "action_id": "efc5ea74e3a37e41a4f57d948cfb2538",
      "action_type": "move",
      "response_data": {
        "success": true,
        "message": "Moved successfully"
      },
      "status": "completed"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 21. Get Pending Actions -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_room.php/actions/pending?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
      "action_id": "efc5ea74e3a37e41a4f57d948cfb2538",
      "player_id": "49",
      "action_type": "move",
      "request_data": {
        "x": 10,
        "y": 20
      },
      "created_at": "2026-03-09 10:10:10",
      "player_name": "TestPlayer"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 22. Complete Action -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_room.php/actions/ACTION_ID/complete?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Marks an action as completed with response data.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "status": "completed",
  "response_data": {
    "success": true,
    "message": "Moved successfully"
  }
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
                            <span class="font-mono">/dotnet/game_room.php/updates?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Sends update to all players in the room.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "targetPlayerIds": "all",
  "type": "play_animation",
  "dataJson": {
    "animation": "victory",
    "duration": 2.0
  }
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "updates_sent": 1,
  "update_ids": ["ddb19c9d8722073762f5db33ff13712a"],
  "target_players": ["47"]
}</code></pre>
                    </div>

                    <!-- 24. Send Update to Specific Player -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/game_room.php/updates?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Sends update to specific players in the room.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "targetPlayerIds": ["47"],
  "type": "spawn_effect",
  "dataJson": {
    "effect": "explosion",
    "position": {
      "x": 10,
      "y": 20
    }
  }
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "updates_sent": 1,
  "update_ids": ["377bfa1d4c56c3f72d9c87b0c081e6e8"],
  "target_players": ["47"]
}</code></pre>
                    </div>

                    <!-- 25. Poll Updates -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_room.php/updates/poll?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
      "update_id": "a28388775fcf9478c6926cbe44f9d3ed",
      "from_player_id": "48",
      "type": "play_animation",
      "data_json": {
        "animation": "victory",
        "duration": 2
      },
      "created_at": "2026-03-09 10:51:40"
    }
  ],
  "last_update_id": "a28388775fcf9478c6926cbe44f9d3ed"
}</code></pre>
                    </div>

                    <!-- 26. Poll Updates with Last ID -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_room.php/updates/poll?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN&lastUpdateId=UPDATE_ID</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Polls for updates after a specific update ID.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "updates": [
    {
      "update_id": "f26cbcdab3939b968f148edf68a9fe54",
      "from_player_id": "48",
      "type": "play_animation",
      "data_json": {
        "animation": "victory",
        "duration": 2
      },
      "created_at": "2026-03-09 10:53:58"
    }
  ],
  "last_update_id": "f26cbcdab3939b968f148edf68a9fe54"
}</code></pre>
                    </div>

                    <!-- 27. Get Current Room Status -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/game_room.php/current?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
    "room_id": "05f157893b1237f7699e548d045904ab",
    "room_name": "Game from Matchmaking 636b3f",
    "is_host": false,
    "is_online": true,
    "max_players": 4,
    "current_players": 2,
    "has_password": false,
    "is_active": true,
    "player_name": "TestPlayer",
    "joined_at": "2026-03-06 17:50:35",
    "last_heartbeat": "2026-03-06 18:02:35",
    "room_created_at": "2026-03-06 17:50:35",
    "room_last_activity": "2026-03-06 18:04:58"
  },
  "pending_actions": [],
  "pending_updates": []
}</code></pre>
                    </div>

                    <!-- 28. List Matchmaking Lobbies -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/matchmaking.php/list?api_token=YOUR_API_TOKEN</span>
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
      "matchmaking_id": "15b2b6e5f0ba44b5eef77705d120861f",
      "host_player_id": 62,
      "max_players": 4,
      "strict_full": 1,
      "extra_json_string": {"minLevel":10,"rank":"gold"},
      "created_at": "2026-03-10 15:16:58",
      "last_heartbeat": "2026-03-10 15:16:58",
      "current_players": 1,
      "host_name": "TestPlayer"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 29. Create Matchmaking Lobby -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/create?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Creates a new matchmaking lobby. The creating player automatically becomes the host.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "maxPlayers": 4,
  "strictFull": true,
  "joinByRequests": true,
  "extraJsonString": {"minLevel":10,"rank":"gold"}
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "matchmaking_id": "636b3ffc9b30dc9c918d8a49661df078",
  "max_players": 4,
  "strict_full": true,
  "join_by_requests": true,
  "is_host": true
}</code></pre>
                    </div>

                    <!-- 30. Request to Join Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/MM_ID/request?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Requests to join a matchmaking lobby that requires host approval.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "request_id": "82334acd88f0af6a1f4747bbe755263a",
  "message": "Join request sent to host"
}</code></pre>
                    </div>

                    <!-- 31. Approve Join Request -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/REQ_ID/response?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Host responds to a join request (approve).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "action": "approve"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Join request approved successfully",
  "request_id": "f4d90025b5de54e6b1a83940cffb4490",
  "action": "approve"
}</code></pre>
                    </div>

                    <!-- 32. Reject Join Request -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/REQ_ID/response?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Host responds to a join request (reject).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "action": "reject"
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "message": "Join request rejected successfully",
  "request_id": "f4d90025b5de54e6b1a83940cffb4490",
  "action": "reject"
}</code></pre>
                    </div>

                    <!-- 33. Check Join Request Status -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/matchmaking.php/REQ_ID/status?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
    "request_id": "f4d90025b5de54e6b1a83940cffb4490",
    "matchmaking_id": "15b2b6e5f0ba44b5eef77705d120861f",
    "status": "approved",
    "requested_at": "2026-03-10 16:10:23",
    "responded_at": "2026-03-10 16:18:43",
    "responded_by": 62,
    "responder_name": "TestPlayer",
    "join_by_requests": true
  }
}</code></pre>
                    </div>

                    <!-- 34. Get Current Matchmaking Status -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/matchmaking.php/current?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
    "matchmaking_id": "636b3ffc9b30dc9c918d8a49661df078",
    "is_host": true,
    "max_players": 4,
    "current_players": 1,
    "strict_full": true,
    "join_by_requests": false,
    "extra_json_string": {"minLevel":10,"rank":"gold"},
    "joined_at": "2026-03-06 17:23:53",
    "player_status": "active",
    "last_heartbeat": "2026-03-06 17:23:53",
    "lobby_heartbeat": "2026-03-06 17:24:37",
    "is_started": false,
    "started_at": null
  },
  "pending_requests": []
}</code></pre>
                    </div>

                    <!-- 35. Join Matchmaking Directly -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/MM_ID/join?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Joins a matchmaking lobby directly (only works if lobby doesn't require approval).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "matchmaking_id": "15b2b6e5f0ba44b5eef77705d120861f",
  "message": "Successfully joined matchmaking lobby"
}</code></pre>
                    </div>

                    <!-- 36. Leave Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/leave?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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

                    <!-- 37. Get Matchmaking Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/dotnet/matchmaking.php/players?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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
      "player_id": 47,
      "joined_at": "2026-03-06 17:23:53",
      "last_heartbeat": "2026-03-06 17:23:53",
      "status": "active",
      "player_name": "TestPlayer",
      "seconds_since_heartbeat": 726,
      "is_host": 1
    },
    {
      "player_id": 46,
      "joined_at": "2026-03-06 17:35:01",
      "last_heartbeat": "2026-03-06 17:35:01",
      "status": "active",
      "player_name": "TestPlayer",
      "seconds_since_heartbeat": 58,
      "is_host": 0
    }
  ],
  "last_updated": "2026-03-06T17:35:59+00:00"
}</code></pre>
                    </div>

                    <!-- 38. Matchmaking Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/heartbeat?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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

                    <!-- 39. Remove Matchmaking Lobby -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/remove?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
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

                    <!-- 40. Start Game from Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/matchmaking.php/start?api_token=YOUR_API_TOKEN&game_player_token=PLAYER_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Starts a game from matchmaking lobby (host only).
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4 overflow-x-auto bg-gray-950/70 p-3 rounded"><code class="language-json">{}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "room_id": "c899e32506d44823d486585b247eafe5",
  "room_name": "Game from Matchmaking 15b2b6",
  "players_transferred": 2,
  "message": "Game started successfully"
}</code></pre>
                    </div>

                    <!-- 41. Get Leaderboard by Level -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/leaderboard.php?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets ranked leaderboard sorted by level.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "sortBy": ["level"],
  "limit": 10
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "leaderboard": [
    {
      "rank": 1,
      "player_id": 33,
      "player_name": "GameHost",
      "player_data": {
        "level": 15,
        "rank": "platinum",
        "role": "host",
        "last_played": "2026-03-13T14:28:06.9900113Z",
        "matchmaking_status": "ready"
      }
    },
    {
      "rank": 2,
      "player_id": 35,
      "player_name": "Player2",
      "player_data": {
        "level": 12,
        "rank": "gold",
        "role": "player"
      }
    }
  ],
  "total": 4,
  "sort_by": ["level"],
  "limit": 10
}</code></pre>
                    </div>

                    <!-- 42. Get Leaderboard by Level and Score -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/leaderboard.php?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets ranked leaderboard sorted by level then score.
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
      "player_id": 40,
      "player_name": "TestPlayer4",
      "player_data": {
        "level": 20,
        "score": 1000,
        "inventory": []
      }
    },
    {
      "rank": 2,
      "player_id": 37,
      "player_name": "TestPlayer1",
      "player_data": {
        "level": 15,
        "score": 4500,
        "inventory": ["sword"]
      }
    }
  ],
  "total": 5,
  "sort_by": ["level","score"],
  "limit": 10
}</code></pre>
                    </div>

                    <!-- 43. Get Leaderboard by Score and Level -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/dotnet/leaderboard.php?api_token=YOUR_API_TOKEN</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
                            <strong>Description:</strong> Gets ranked leaderboard sorted by score then level.
                        </p>
                        <div class="text-xs text-gray-300 font-medium mb-2">Request Body:</div>
                        <pre class="text-sm mb-4"><code class="language-json">{
  "sortBy": ["score","level"],
  "limit": 10
}</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto"><code class="language-json">{
  "success": true,
  "leaderboard": [
    {
      "rank": 1,
      "player_id": 37,
      "player_name": "TestPlayer1",
      "player_data": {
        "level": 15,
        "score": 4500,
        "inventory": ["sword"]
      }
    },
    {
      "rank": 2,
      "player_id": 39,
      "player_name": "TestPlayer3",
      "player_data": {
        "level": 8,
        "score": 3000,
        "inventory": ["bow"]
      }
    }
  ],
  "total": 5,
  "sort_by": ["score","level"],
  "limit": 10
}</code></pre>
                    </div>

                    <!-- 44. Error Response Example -->
                    <div class="bg-black/50 p-4 rounded-lg border border-red-900/50">
                        <div class="flex items-center text-sm text-red-400 mb-2">
                            <span class="font-mono bg-red-900/50 px-2 py-1 rounded mr-2">ERROR</span>
                            <span class="font-mono">Any endpoint with invalid/missing API key</span>
                        </div>
                        <p class="text-xs text-gray-400 mb-2">
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
