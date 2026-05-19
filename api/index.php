<?php
session_start();
require_once '../php/config.php';

// Set page-specific meta tag variables
$title = "Multiplayer API – REST API";
$description = "Complete REST API documentation for multiplayer games. JSON responses, authentication, and other features.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/michitai.png";
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Platform-specific meta tags for better social sharing
$platform_name = "REST API";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-json.min.js"></script>
    
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
        
        /* Responsive tables for API endpoints */
        @media (max-width: 768px) {
            .grid.grid-cols-12 {
                grid-template-columns: 1fr;
                gap: 0.5rem;
            }
            
            .grid.grid-cols-12 > div {
                grid-column: 1 / -1;
                padding: 0.5rem 0;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            
            .grid.grid-cols-12 > div:last-child {
                border-bottom: none;
            }
            
            /* Adjust code blocks for mobile - no scrollbars */
            pre {
                padding: 0.75rem;
                font-size: 0.75em;
                margin: 0.75rem -0.5rem;
                border-radius: 0;
                max-width: 100%;
                overflow: visible;
            }
            
            /* Make sure long URLs and code don't break layout */
            .font-mono, code, pre {
                word-break: break-word;
                white-space: pre-wrap;
                word-wrap: break-word;
            }
            
            /* Adjust spacing in API documentation */
            .space-y-4 > div {
                margin-bottom: 1rem;
            }
            
            /* Make sure buttons are tap-friendly on mobile */
            button, .btn-primary, .btn-secondary {
                min-height: 44px;
                padding: 0.5rem 1rem;
            }
        }
        
        /* Very important: let Prism do its job */
        pre code {
            background: transparent;
            padding: 0;
            margin: 0;
            white-space: pre-wrap;             /* wrap instead of scroll */
            word-break: break-word;
            word-wrap: break-word;
        }
        
        /* Extra safety for JSON (prevents collapse on long lines) */
        pre code.language-json {
            white-space: pre-wrap;
            word-break: break-word;
            display: block;
            overflow: visible;
        }
        
        /* Make sure Prism classes are respected */
        .token {
            background: none !important;  /* prevent unwanted overrides */
        }
        
        /* Code block styling – Modern, Prism-friendly version */
        pre {
            background: var(--code-bg);
            color: var(--code-text);
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 1rem 0;
            font-family: 'Fira Code', 'Consolas', 'Monaco', 'Andale Mono', monospace;
            font-size: 0.8em;
            line-height: 1.5;
            tab-size: 2;
            overflow: visible;
            max-width: 100%;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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
                REST API for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-blue-500">Multiplayer Games</span>
            </h1>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="#docs" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-code mr-2"></i>View Endpoints
                </a>
                <a href="#authentication" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-key mr-2"></i>Authentication
                </a>
                <a href="#examples" class="glass-effect text-white px-8 py-4 rounded-xl text-lg font-semibold hover:bg-white/20 transition">
                    <i class="fas fa-flask mr-2"></i>REST API
                </a>
            </div>
        </div>
    </section>

    <!-- API Endpoints -->
    <section id="docs" class="py-16 bg-gradient-to-b from-black/20 to-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">API Endpoints</h3>
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
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/register</div>
                            <div class="col-span-6">Register player (requires API TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/login</div>
                            <div class="col-span-6">Authenticate player (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/heartbeat</div>
                            <div class="col-span-6">Update player heartbeat (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/logout</div>
                            <div class="col-span-6">Logout player (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/rename</div>
                            <div class="col-span-6">Rename player (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/list</div>
                            <div class="col-span-6">List all players (requires API TOKEN, PRIVATE TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/ban</div>
                            <div class="col-span-6">Ban player (requires API TOKEN, PRIVATE TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_players.php/unban</div>
                            <div class="col-span-6">Unban player (requires API TOKEN, PRIVATE TOKEN)</div>
                        </div>
                    </div>
                </div>

                <!-- Game Data -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Game Data</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_data.php/game/get</div>
                            <div class="col-span-6">Get game data (requires API TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_data.php/game/update</div>
                            <div class="col-span-6">Update game data (requires API TOKEN, PRIVATE TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_data.php/player/get</div>
                            <div class="col-span-6">Get player data (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-purple-500/20 text-purple-400 text-xs px-2 py-1 rounded">PUT</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_data.php/player/update</div>
                            <div class="col-span-6">Update player data (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                    </div>
                </div>

                <!-- Leaderboard -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Leaderboard</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/leaderboard.php</div>
                            <div class="col-span-6">Get ranked leaderboard (requires API TOKEN)</div>
                        </div>
                    </div>
                </div>

                <!-- Server Data -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Server Data</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/time.php</div>
                            <div class="col-span-6">Get server time (requires API TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/time.php?utc=+1</div>
                            <div class="col-span-6">Get server time +1 hour offset (requires API TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/time.php?utc=-2</div>
                            <div class="col-span-6">Get server time -2 hours offset (requires API TOKEN)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Matchmaking -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Matchmaking</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/list</div>
                            <div class="col-span-6">List all available matchmaking lobbies (requires API TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/create</div>
                            <div class="col-span-6">Create matchmaking (requires API TOKEN, PRIVATE TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/{ID}/request</div>
                            <div class="col-span-6">Request to join matchmaking (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/{ID}/response</div>
                            <div class="col-span-6">Respond to join request (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/{ID}/status</div>
                            <div class="col-span-6">Check join request status (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/current</div>
                            <div class="col-span-6">Get current matchmaking status (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/{ID}/join</div>
                            <div class="col-span-6">Join matchmaking directly (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/leave</div>
                            <div class="col-span-6">Leave current matchmaking (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/players</div>
                            <div class="col-span-6">List all players in current matchmaking (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/heartbeat</div>
                            <div class="col-span-6">Send matchmaking heartbeat (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/remove</div>
                            <div class="col-span-6">Remove matchmaking lobby (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/start</div>
                            <div class="col-span-6">Start game from matchmaking (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/matchmaking.php/stop</div>
                            <div class="col-span-6">Stop matchmaking lobby (Host Only) (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Game Rooms -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Game Rooms</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/create</div>
                            <div class="col-span-6">Create game room (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/list</div>
                            <div class="col-span-6">List all game rooms (requires API TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/{ID}/join</div>
                            <div class="col-span-6">Join existing room (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/players</div>
                            <div class="col-span-6">List all players in current room (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/leave</div>
                            <div class="col-span-6">Leave current game room (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/heartbeat</div>
                            <div class="col-span-6">Send room heartbeat (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/actions</div>
                            <div class="col-span-6">Submit game action (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/actions/poll</div>
                            <div class="col-span-6">Get actions result (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/actions/pending</div>
                            <div class="col-span-6">View all pending actions (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/actions/{ID}/complete</div>
                            <div class="col-span-6">Complete or reject action (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/updates</div>
                            <div class="col-span-6">Send updates to players (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/updates/poll</div>
                            <div class="col-span-6">Poll for updates (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-blue-500/20 text-blue-400 text-xs px-2 py-1 rounded">GET</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/current</div>
                            <div class="col-span-6">Get current room status (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/game_room.php/stop</div>
                            <div class="col-span-6">Stop game room (Host Only) (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                    </div>
                </div>

                <!-- Realtime -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">Realtime</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-green-500/20 text-green-400 text-xs px-2 py-1 rounded">POST</span></div>
                            <div class="col-span-4 font-mono text-white/90">/api/realtime.php/token</div>
                            <div class="col-span-6">Generate REALTIME TOKEN (requires API TOKEN, PLAYER TOKEN)</div>
                        </div>
                    </div>
                </div>

                <!-- WebSocket -->
                <div class="p-4 border-t border-white/10">
                    <h4 class="text-white/60 text-sm font-semibold mb-3">WebSocket</h4>
                    <div class="space-y-4">
                        <div class="grid grid-cols-12 items-center">
                            <div class="col-span-2"><span class="inline-block bg-orange-500/20 text-orange-400 text-xs px-2 py-1 rounded">WSS</span></div>
                            <div class="col-span-4 font-mono text-white/90">wss://realtime.michitai.com</div>
                            <div class="col-span-6">Realtime WebSocket server (requires REALTIME TOKEN)</div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>

    <!-- Authentication Section -->
    <section id="authentication" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Authentication</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Secure API requests</p>
            </div>
            
            <div class="max-w-7xl mx-auto space-y-8">
                <!-- API Authentication -->
                <div class="glass-effect p-8 rounded-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center mr-4">
                            <i class="fas fa-key text-2xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white">API Authentication</h4>
                    </div>
                    <p class="text-white/80 mb-6">
                        All API requests require authentication using API tokens and player private keys.
                        This ensures secure access to game data and player information.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="bg-black/50 p-4 rounded-lg">
                            <h5 class="text-green-300 font-medium mb-2">API Token Authentication</h5>
                            <p class="text-white/70 text-sm mb-2">Include your API token in query parameters:</p>
                            <pre class="text-sm text-gray-300 overflow-x-auto"><code>?api_token=API_TOKEN</code></pre>
                        </div>
                        
                        <div class="bg-black/50 p-4 rounded-lg">
                            <h5 class="text-blue-300 font-medium mb-2">Player Authentication</h5>
                            <p class="text-white/70 text-sm mb-2">Include player token for player-specific operations:</p>
                            <pre class="text-sm text-gray-300 overflow-x-auto"><code>?player_token=PLAYER_TOKEN</code></pre>
                        </div>
                        
                        <div class="bg-black/50 p-4 rounded-lg">
                            <h5 class="text-purple-300 font-medium mb-2">Admin Operations</h5>
                            <p class="text-white/70 text-sm mb-2">Some operations require your private API token:</p>
                            <pre class="text-sm text-gray-300 overflow-x-auto"><code>?private_token=PRIVATE_TOKEN</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- REST API Endpoints -->
    <section id="examples" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="glass-effect p-8 rounded-2xl">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-blue-600 flex items-center justify-center mr-4">
                        <i class="fas fa-code text-2xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white">REST API <span class="font-light">– Complete Documentation</span></h1>
                </div>
                <p class="text-white/80 mb-6">
                    Use our REST API directly from any platform or language. 
                    All endpoints return JSON responses with consistent error handling.
                </p>

                <div class="space-y-4">
                    <!-- 1. Register Player -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_players.php/register?api_token=API_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_players.php/register?api_token=API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "player_name": "TestPlayer",
    "player_data": {
      "level": 1,
      "score": 0,
      "inventory": ["sword", "shield"]
    }
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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
                            <span class="font-mono">/api/game_players.php/login?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X PUT "/api/game_players.php/login?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "player": {
    "id": 3,
    "game_id": 2,
    "player_name": "TestPlayer",
    "player_data": {
      "level": 1,
      "score": 0,
      "inventory": ["sword", "shield"]
    },
    "is_online": true,
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
                            <span class="font-mono">/api/game_players.php/heartbeat?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_players.php/heartbeat?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Heartbeat updated",
  "last_heartbeat": "2026-03-13 09:46:13"
}</code></pre>
                    </div>

                    <!-- 4. Player Logout -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_players.php/logout?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_players.php/logout?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Player logged out successfully",
  "last_logout": "2026-03-13 09:46:37"
}</code></pre>
                    </div>

                    <!-- 5. Rename Player -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/api/game_players.php/rename?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X PUT "/api/game_players.php/rename?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "new_name": "NewPlayerName"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Player name updated successfully",
  "new_name": "NewPlayerName",
  "player_id": 3
}</code></pre>
                    </div>

                    <!-- 6. List Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_players.php/list?api_token=API_TOKEN&private_token=PRIVATE_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/game_players.php/list?api_token=API_TOKEN&private_token=PRIVATE_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "count": 3,
  "players": [
    {
      "id": 1,
      "player_name": "TestPlayer",
      "is_online": true,
      "last_login": null,
      "last_logout": null,
      "last_heartbeat": null,
      "created_at": "2026-03-13 09:37:47"
    },
    {
      "id": 3,
      "player_name": "TestPlayer",
      "is_online": true,
      "last_login": "2026-03-13 09:40:35",
      "last_logout": null,
      "last_heartbeat": null,
      "created_at": "2026-03-13 09:39:06"
    },
    {
      "id": 2,
      "player_name": "TestPlayer",
      "is_online": true,
      "last_login": null,
      "last_logout": null,
      "last_heartbeat": null,
      "created_at": "2026-03-13 09:38:01"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 6. Get Game Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_data.php/game/get?api_token=API_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/game_data.php/game/get?api_token=API_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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
                            <span class="font-mono">/api/game_data.php/game/update?api_token=API_TOKEN&private_token=PRIVATE_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X PUT "/api/game_data.php/game/update?api_token=API_TOKEN&private_token=PRIVATE_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "game_settings": {
      "difficulty": "hard",
      "max_players": 10
    },
    "last_updated": "2025-01-13T12:00:00Z"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Game data updated successfully",
  "updated_at": "2026-01-13 14:24:23"
}</code></pre>
                    </div>

                    <!-- 8. Get Player Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_data.php/player/get?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/game_data.php/player/get?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "type": "player",
  "player_id": 7,
  "player_name": "TestPlayer",
  "data": {
    "level": 1,
    "score": 0,
    "inventory": ["sword", "shield"]
  }
}</code></pre>
                    </div>

                    <!-- 9. Update Player Data -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-purple-400 mb-2">
                            <span class="font-mono bg-purple-900/50 px-2 py-1 rounded mr-2">PUT</span>
                            <span class="font-mono">/api/game_data.php/player/update?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X PUT "/api/game_data.php/player/update?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "level": 2,
    "score": 100,
    "inventory": ["sword", "shield", "potion"],
    "last_played": "2025-01-13T12:30:00Z"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Player data updated successfully",
  "updated_at": "2026-01-13 14:27:10"
}</code></pre>
                    </div>

                    <!-- 10. Get Leaderboard (by level) -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/leaderboard.php?api_token=API_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/leaderboard.php?api_token=API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "sort_by": ["level"],
    "limit": 10
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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
    },
    {
      "rank": 3,
      "player_id": 34,
      "player_name": "Player1",
      "player_data": {
        "level": 8,
        "rank": "silver",
        "role": "player"
      }
    },
    {
      "rank": 4,
      "player_id": 36,
      "player_name": "Player3",
      "player_data": {
        "level": 6,
        "rank": "bronze",
        "role": "player"
      }
    }
  ],
  "total": 4,
  "sort_by": ["level"],
  "limit": 10
}</code></pre>
                    </div>

                        <!-- 11. Get Leaderboard (by level and score) -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/leaderboard.php?api_token=API_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/leaderboard.php?api_token=API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "sort_by": ["level", "score"],
    "limit": 10
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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
    },
    {
      "rank": 3,
      "player_id": 38,
      "player_name": "TestPlayer2",
      "player_data": {
        "level": 15,
        "score": 2000,
        "inventory": ["sword", "shield"]
      }
    },
    {
      "rank": 4,
      "player_id": 41,
      "player_name": "TestPlayer5",
      "player_data": {
        "level": 12,
        "inventory": ["staff"]
      }
    },
    {
      "rank": 5,
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
  "sort_by": ["level", "score"],
  "limit": 10
}</code></pre>
                    </div>

                    <!-- 12. Get Leaderboard (by score and level) -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/leaderboard.php?api_token=API_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/leaderboard.php?api_token=API_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "sort_by": ["score", "level"],
    "limit": 10
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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
    },
    {
      "rank": 3,
      "player_id": 38,
      "player_name": "TestPlayer2",
      "player_data": {
        "level": 15,
        "score": 2000,
        "inventory": ["sword", "shield"]
      }
    },
    {
      "rank": 4,
      "player_id": 40,
      "player_name": "TestPlayer4",
      "player_data": {
        "level": 20,
        "score": 1000,
        "inventory": []
      }
    },
    {
      "rank": 5,
      "player_id": 41,
      "player_name": "TestPlayer5",
      "player_data": {
        "level": 12,
        "inventory": ["staff"]
      }
    }
  ],
  "total": 5,
  "sort_by": ["score", "level"],
  "limit": 10
}</code></pre>
                    </div>

                    <!-- 13. Get Server Time -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/time.php?api_token=API_KEY</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/time.php?api_token=API_KEY"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "utc": "2026-03-13T14:01:49+00:00",
  "timestamp": 1773410509,
  "readable": "2026-03-13 14:01:49 UTC"
}</code></pre>
                    </div>

                    <!-- 14. Get Server Time +1 Hour -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/time.php?api_token=API_KEY&utc=+1</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/time.php?api_token=API_KEY&utc=+1"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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

                    <!-- 15. Get Server Time -2 Hours -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/time.php?api_token=API_KEY&utc=-2</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/time.php?api_token=API_KEY&utc=-2"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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

                    <!-- 16. List Matchmaking Lobbies -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/matchmaking.php/list?api_token=API_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl "/api/matchmaking.php/list?api_token=API_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "lobbies": [
    {
      "matchmaking_id": "15b2b6e5f0ba44b5eef77705d120861f",
      "host_player_id": 62,
      "max_players": 4,
      "strict_full": 1,
      "rules": {
        "min_level": 10,
        "rank": "gold"
      },
      "created_at": "2026-03-10 15:16:58",
      "last_heartbeat": "2026-03-10 15:16:58",
      "current_players": 1,
      "host_name": "TestPlayer"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 17. Create Matchmaking Lobby -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/create?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/create?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "matchmaking_name": "My Matchmaking",
    "maxPlayers": 4,
    "strictFull": true,
    "join_by_requests": true,
    "host_switch": false,
    "can_leave_room": true,
    "rules": {
      "min_level": 10,
      "rank": "gold"
    },
    "player_data": {
      "level": 20,
      "class": "paladin",
      "inventory": ["sword", "shield", "armor"]
    }
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "matchmaking_id": "636b3ffc9b30dc9c918d8a49661df078",
  "max_players": 4,
  "strict_full": true,
  "join_by_requests": true,
  "is_host": true
}</code></pre>
                    </div>

                    <!-- 18. Request to Join Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/MATCHMAKING_ID/request?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/15b2b6e5f0ba44b5eef77705d120861f/request?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "request_id": "82334acd88f0af6a1f4747bbe755263a",
  "message": "Join request sent to host"
}</code></pre>
                    </div>

            <!-- 19. Respond to Join Request (Approve) -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/REQUEST_ID/response?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/f4d90025b5de54e6b1a83940cffb4490/response?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "action": "approve"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Join request approved successfully",
  "request_id": "f4d90025b5de54e6b1a83940cffb4490",
  "action": "approve"
}</code></pre>
                    </div>

                    <!-- 20. Respond to Join Request (Reject) -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/REQUEST_ID/response?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/f4d90025b5de54e6b1a83940cffb4490/response?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "action": "reject"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Join request rejected successfully",
  "request_id": "f4d90025b5de54e6b1a83940cffb4490",
  "action": "reject"
}</code></pre>
                    </div>

                    <!-- 21. Check Join Request Status -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/matchmaking.php/REQUEST_ID/status?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl "/api/matchmaking.php/f4d90025b5de54e6b1a83940cffb4490/status?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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

                    <!-- 22. Get Current Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/matchmaking.php/current?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl "/api/matchmaking.php/current?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "in_matchmaking": true,
  "matchmaking": {
    "matchmaking_id": "636b3ffc9b30dc9c918d8a49661df078",
    "is_host": true,
    "max_players": 4,
    "current_players": 1,
    "strict_full": true,
    "join_by_requests": false,
    "rules": {
      "min_level": 10,
      "rank": "gold"
    },
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

                    <!-- 23. Join Matchmaking Lobby -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/MATCHMAKING_ID/join?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/15b2b6e5f0ba44b5eef77705d120861f/join?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "matchmaking_id": "15b2b6e5f0ba44b5eef77705d120861f",
  "message": "Successfully joined matchmaking lobby"
}</code></pre>
                    </div>
    
    <!-- 24. Leave Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/leave?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/leave?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Successfully left matchmaking lobby"
}</code></pre>
                    </div>

                    <!-- 25. List Matchmaking Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/matchmaking.php/players?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl "/api/matchmaking.php/players?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "players": [
    {
      "player_id": 47,
      "joined_at": "2026-03-06 17:23:53",
      "last_heartbeat": "2026-03-06 17:23:53",
      "status": "active",
      "player_name": "TestPlayer",
      "seconds_since_heartbeat": 726,
      "is_host": 1,
      "is_local": true
    },
    {
      "player_id": 46,
      "joined_at": "2026-03-06 17:35:01",
      "last_heartbeat": "2026-03-06 17:35:01",
      "status": "active",
      "player_name": "TestPlayer",
      "seconds_since_heartbeat": 58,
      "is_host": 0,
      "is_local": false
    }
  ],
  "last_updated": "2026-03-06T17:35:59+00:00"
}</code></pre>
                    </div>

                    <!-- 26. Matchmaking Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/heartbeat?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/heartbeat?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "status": "ok"
}</code></pre>
                    </div>

                    <!-- 27. Remove Matchmaking Lobby -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/remove?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/remove?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Matchmaking lobby removed successfully"
}</code></pre>
                    </div>

                    <!-- 28. Start Game from Matchmaking -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/matchmaking.php/start?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/matchmaking.php/start?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "room_id": "c899e32506d44823d486585b247eafe5",
  "room_name": "Game from Matchmaking 15b2b6",
  "players_transferred": 2,
  "message": "Game started successfully"
}</code></pre>
                    </div>

                    <!-- 29. Create Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/create?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/create?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "room_name": "My Game Room",
    "max_players": 4,
    "password": "secret123",
    "host_switch": false,
    "can_leave_room": true,
    "player_data": {
      "level": 15,
      "class": "warrior",
      "inventory": ["sword", "shield"]
    }
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "room_id": "dc3723848639139113ca240958ba0bf8",
  "room_name": "My Game Room",
  "is_host": true
}</code></pre>
                    </div>

                    <!-- 30. List Rooms -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_room.php/list?api_token=API_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/game_room.php/list?api_token=API_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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

                    <!-- 31. Join Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/ROOM_ID/join?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/dc3723848639139113ca240958ba0bf8/join?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "password": "secret123"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "room_id": "dc3723848639139113ca240958ba0bf8",
  "message": "Successfully joined the room"
}</code></pre>
                    </div>

                    <!-- 32. List Room Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_room.php/players?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X GET "/api/game_room.php/players?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "players": [
    {
      "player_id": "48",
      "player_name": "TestPlayer",
      "is_host": 1,
      "is_online": 1,
      "is_local": true,
      "last_heartbeat": "2026-03-09 09:39:34"
    },
    {
      "player_id": "49",
      "player_name": "TestPlayer",
      "is_host": 0,
      "is_online": 1,
      "is_local": false,
      "last_heartbeat": "2026-03-09 09:44:39"
    }
  ],
  "last_updated": "2026-03-09T09:56:21+00:00"
}</code></pre>
                    </div>

                    <!-- 33. Leave Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/leave?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/leave?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Successfully left the room"
}</code></pre>
                    </div>

                    <!-- 34. Room Heartbeat -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/heartbeat?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/heartbeat?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "status": "ok"
}</code></pre>
                    </div>

                    <!-- 35. Submit Action -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/actions?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request (send to host):</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/actions?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "target_players": "host",
    "action_type": "move",
    "request_data": {
      "x": 10,
      "y": 20
    }
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "actions_sent": 1,
  "action_ids": ["1c2bbd859e36dc7d7e5e9b4f263c88ce"],
  "target_players_ids": [48]
}</code></pre>
                    </div>

                    <!-- 36. Poll Actions -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_room.php/actions/poll?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl "/api/game_room.php/actions/poll?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "actions": [
    {
      "action_id": "efc5ea74e3a37e41a4f57d948cfb2538",
      "action_type": "move",
      "response_data": {
        "success": true,
        "message": "Moved successfully"
      },
      "status": "completed",
      "target_id": 48,
      "is_host": true,
      "processed_at": "2026-03-13T10:15:30+00:00"
    }
  ]
}</code></pre>
                    </div>

                    <!-- 37. Get Pending Actions -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_room.php/actions/pending?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl "/api/game_room.php/actions/pending?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "actions": [
    {
      "action_id": "efc5ea74e3a37e41a4f57d948cfb2538",
      "player_id": 49,
      "target_id": 48,
      "action_type": "move",
      "request_data": {
        "x": 10,
        "y": 20
      },
      "created_at": "2026-03-09 10:10:10",
      "player_name": "TestPlayer",
      "is_host": false
    }
  ]
}</code></pre>
                    </div>

                    <!-- 38. Complete Action -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/actions/ACTION_ID/complete?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/actions/1c2bbd859e36dc7d7e5e9b4f263c88ce/complete?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "completed",
    "response_data": {
      "success": true,
      "message": "Moved successfully"
    }
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "message": "Action completed"
}</code></pre>
                    </div>

                    <!-- 39. Send Update to All Players -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/updates?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/updates?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "target_players": "all",
    "type": "play_animation",
    "data": {
      "animation": "victory",
      "duration": 2.0
    }
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "updates_sent": 1,
  "update_ids": ["ddb19c9d8722073762f5db33ff13712a"],
  "target_players_ids": [47]
}</code></pre>
                    </div>

                    <!-- 40. Send Update to Specific Player -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/updates?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/updates?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "target_players": "specific",
    "target_players_ids": [47],
    "type": "spawn_effect",
    "data": {
      "effect": "explosion",
      "position": {
        "x": 10,
        "y": 20
      }
    }
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "updates_sent": 1,
  "update_ids": ["377bfa1d4c56c3f72d9c87b0c081e6e8"],
  "target_players_ids": [47]
}</code></pre>
                    </div>

                    <!-- 41. Poll Updates -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/updates/poll?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/updates/poll?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "from_players": "host",
    "last_update": null
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "updates": [
    {
      "update_id": "a28388775fcf9478c6926cbe44f9d3ed",
      "from_player_id": "48",
      "type": "play_animation",
      "data": {
        "animation": "victory",
        "duration": 2
      },
      "created_at": "2026-03-09 10:51:40"
    },
    {
      "update_id": "f26cbcdab3939b968f148edf68a9fe54",
      "from_player_id": "48",
      "type": "play_animation",
      "data": {
        "animation": "victory",
        "duration": 2
      },
      "created_at": "2026-03-09 10:53:58"
    },
    {
      "update_id": "374ad8d18f1a1fddf09a856d61787c5c",
      "from_player_id": "48",
      "type": "play_animation",
      "data": {
        "animation": "victory",
        "duration": 2
      },
      "created_at": "2026-03-09 10:54:16"
    }
  ],
  "last_update": "374ad8d18f1a1fddf09a856d61787c5c"
}</code></pre>
                    </div>

            <!-- 42. Poll Updates with Last Update ID -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-green-400 mb-2">
                            <span class="font-mono bg-green-900/50 px-2 py-1 rounded mr-2">POST</span>
                            <span class="font-mono">/api/game_room.php/updates/poll?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_room.php/updates/poll?api_token=API_TOKEN&player_token=PLAYER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "from_players": "host",
    "last_update": "a28388775fcf9478c6926cbe44f9d3ed"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": true,
  "updates": [
    {
      "update_id": "f26cbcdab3939b968f148edf68a9fe54",
      "from_player_id": "48",
      "type": "play_animation",
      "data": {
        "animation": "victory",
        "duration": 2
      },
      "created_at": "2026-03-09 10:53:58"
    }
  ],
  "last_update": "f26cbcdab3939b968f148edf68a9fe54"
}</code></pre>
                    </div>

                    <!-- 43. Get Current Room -->
                    <div class="bg-black/50 p-4 rounded-lg">
                        <div class="flex items-center text-sm text-blue-400 mb-2">
                            <span class="font-mono bg-blue-900/50 px-2 py-1 rounded mr-2">GET</span>
                            <span class="font-mono">/api/game_room.php/current?api_token=API_TOKEN&player_token=PLAYER_TOKEN</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl "/api/game_room.php/current?api_token=API_TOKEN&player_token=PLAYER_TOKEN"</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
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

                    <!-- Error Example -->
                    <div class="bg-black/50 p-4 rounded-lg border border-red-500/30">
                        <div class="flex items-center text-sm text-red-400 mb-2">
                            <span class="font-mono bg-red-900/50 px-2 py-1 rounded mr-2">ERROR</span>
                            <span class="font-mono">Invalid API Token</span>
                        </div>
                        <div class="text-xs text-gray-400 mb-2">Request:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-bash">$ curl -X POST "/api/game_players.php/register?api_token=INVALID_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "player_name": "TestPlayer"
  }'</code></pre>
                        <div class="text-xs text-gray-400 mb-2">Response:</div>
                        <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap break-all"><code class="language-json">{
  "success": false,
  "error": "Invalid API token",
  "message": "Authentication failed"
}</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- API Error Responses -->
    <section id="errors" class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">API Error Responses</h3>
                <p class="text-xl text-white/70 max-w-3xl mx-auto">Complete list of error responses for all API endpoints</p>
            </div>
            
            <div class="glass-effect p-8 rounded-2xl">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-red-600 to-orange-600 flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-white"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white">Error Responses <span class="font-light">– Complete Reference</span></h1>
                </div>
                <p class="text-white/80 mb-6">
                    All API endpoints return consistent error responses in JSON format. Each error includes an error message and success status.
                </p>

                <div class="space-y-6">
                    <!-- game_data.php -->
                    <div class="bg-black/50 p-6 rounded-lg">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-file-code mr-2 text-blue-400"></i>
                            game_data.php
                        </h4>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: game/get</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: player/get</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Game player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid game player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player does not belong to this game", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: game/update</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API private token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token or private token", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: player/update</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Game player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player or does not belong to game", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: default</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid endpoint", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Method not allowed", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Database error", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"An unexpected error occurred", "success": false}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- game_players.php -->
                    <div class="bg-black/50 p-6 rounded-lg">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-users mr-2 text-purple-400"></i>
                            game_players.php
                        </h4>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: register</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player name is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to register player", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: login</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token and game player token are required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid game player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: heartbeat</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token and game player token are required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to update heartbeat", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: logout</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token and game player token are required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: rename</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token and game player token are required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"New name is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player name must be between 2 and 50 characters", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to update player name", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: list</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token and private token are required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API credentials", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: ban</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token and private token are required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API credentials", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"player_id is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"ban_duration is required (hour, day, week, month, quarter, year, forever)", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid ban_duration. Must be one of: hour, day, week, month, quarter, year, forever", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player not found or does not belong to this game", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: unban</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token and private token are required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API credentials", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"player_id is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player not found or does not belong to this game", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: default</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Method not allowed", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid endpoint", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Internal server error", "success": false}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- game_room.php -->
                    <div class="bg-black/50 p-6 rounded-lg">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-door-open mr-2 text-green-400"></i>
                            game_room.php
                        </h4>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: create</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are already in a game room. Leave current room first.", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You cannot create a game room while in a matchmaking lobby.", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to create room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: list</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to list rooms", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: join</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are already in another room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Room not found", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Room inactive", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Room is full", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Incorrect password", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: players</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: leave</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in this room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Room not found", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Players are not allowed to leave this room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: heartbeat</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player is not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to update heartbeat", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: actions</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing action_type", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"request_data_json must be a string", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player is not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: actions/poll</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player is not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: actions/pending</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: actions/complete</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Status is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"response_data_json must be a string", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"response_data is not valid JSON", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Action not found or already processed", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: updates</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player is not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required field: target_players", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required field: type", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required field: target_players_ids", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid target players ids", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"No valid target players found", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid target players", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to send updates", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: updates/poll</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player is not in any room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"No valid source players found", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid from players", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: current</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: stop</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in any game room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Only host can stop game room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to stop game room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: default</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid endpoint", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Internal server error", "success": false}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- leaderboard.php -->
                    <div class="bg-black/50 p-6 rounded-lg">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-trophy mr-2 text-yellow-400"></i>
                            leaderboard.php
                        </h4>
                        
                        <div>
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: default</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Method not allowed. Use POST.", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"api_token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid or expired api_token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid JSON body", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"sort_by must be a non-empty array of field names", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"limit must be between 1 and 1000", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"No valid sort fields provided after sanitization", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Database error", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Server error", "success": false}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- matchmaking.php -->
                    <div class="bg-black/50 p-6 rounded-lg">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-users-cog mr-2 text-cyan-400"></i>
                            matchmaking.php
                        </h4>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: list</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: create</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are already in a matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You cannot create matchmaking while in a game room. Leave room first.", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required field: max_players", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to create matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: request</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required parameter: matchmakingId", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are already in a matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You already have a pending request to this matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Matchmaking lobby not found or already started", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Matchmaking lobby is full", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: join</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required parameter: matchmakingId", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are already in a matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Matchmaking lobby not found or already started", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"This matchmaking lobby requires host approval. Use /request endpoint instead.", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Matchmaking lobby is full", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: leave</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in any matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Players are not allowed to leave this matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: players</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in any matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to get players", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: heartbeat</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in any matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to update heartbeat", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: remove</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in a matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Only host can remove matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to remove matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: current</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to get matchmaking status", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: status</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required parameter: requestId", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Request not found or you are not the requester", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: response</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Missing required fields: requestId and action", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Action must be \"approve\" or \"reject\"", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Request not found or already processed", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Only the host can respond to join requests", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Matchmaking lobby not found or already started", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Matchmaking lobby is full", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: start</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in a matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Only host can start matchmaking", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Matchmaking lobby not found or already started", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Lobby must be full to start (strict_full enabled)", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: stop</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are not in a matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Only host can stop matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Cannot stop matchmaking lobby after it has been started", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to stop matchmaking lobby", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: default</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid endpoint", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Internal server error", "success": false}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- realtime.php -->
                    <div class="bg-black/50 p-6 rounded-lg">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-bolt mr-2 text-orange-400"></i>
                            realtime.php
                        </h4>
                        
                        <div class="mb-4">
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: token</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid player token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player token is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Player is not in a realtime-enabled room", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Failed to generate realtime token", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"You are banned", "success": false}</code></pre>
                            </div>
                        </div>
                        
                        <div>
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: default</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid endpoint", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Internal server error", "success": false}</code></pre>
                            </div>
                        </div>
                    </div>

                    <!-- time.php -->
                    <div class="bg-black/50 p-6 rounded-lg">
                        <h4 class="text-white font-semibold mb-4 flex items-center">
                            <i class="fas fa-clock mr-2 text-indigo-400"></i>
                            time.php
                        </h4>
                        
                        <div>
                            <h5 class="text-green-400 text-sm font-medium mb-2">endpoint: default</h5>
                            <div class="space-y-2">
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Method not allowed", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"API key is required", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Invalid API key", "success": false}</code></pre>
                                <pre class="text-xs text-gray-300 overflow-x-auto whitespace-pre-wrap"><code class="language-json">{"error":"Internal server error", "success": false}</code></pre>
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
    </script>
</body>
</html>
