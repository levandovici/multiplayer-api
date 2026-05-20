<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Cabinet - Multiplayer API</title>
    <link rel="icon" type="image/png" href="/michitai.png">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
        }
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .glass-effect {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
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
        
        .btn-secondary {
            background: var(--secondary-gradient);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(240, 147, 251, 0.4);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.6);
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
        
        .floating-card {
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .floating-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Mobile table styles */
        @media (max-width: 768px) {
            .mobile-table {
                display: none;
            }
            
            .mobile-cards {
                display: block;
            }
            
            .api-key-card {
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 12px;
                transition: all 0.3s ease;
            }
            
            .api-key-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }
        }
        
        @media (min-width: 769px) {
            .mobile-table {
                display: table;
            }
            
            .mobile-cards {
                display: none;
            }
        }
        
        /* Copy button styles */
        .copy-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .copy-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }
        
        .copy-btn.copied {
            background: rgba(34, 197, 94, 0.3);
            border-color: rgba(34, 197, 94, 0.5);
        }

        /* Dropdown styles */
        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        select option {
            background: #1a1a2e;
            color: white;
            padding: 8px 12px;
        }

        select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='white'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
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
                    <a href="index.php" class="text-white hover:text-gray-200 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <a href="php/logout.php" class="text-white hover:text-gray-200 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Log Out
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- User Cabinet -->
    <section class="min-h-[calc(100vh-4rem)] py-6 px-3 sm:px-4 lg:px-6">
        <div class="max-w-7xl mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl sm:text-4xl font-black text-white mb-3">User Cabinet</h1>
                <p class="text-lg sm:text-xl text-white/70">Manage your account and API keys</p>
            </div>
            
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
                <!-- User Info Card -->
                <div class="glass-effect rounded-2xl p-5 floating-card xl:col-span-1">
                    <div class="flex items-center mb-5">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white text-xl mr-3">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white">Account Details</h2>
                            <p class="text-white/70 text-xs sm:text-sm">Manage your profile information</p>
                        </div>
                    </div>
                    
                    <div id="user-info" class="space-y-3">
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-white/50 w-5 text-sm"></i>
                            <div class="ml-2">
                                <p class="text-xs text-white/70">Email</p>
                                <p id="email" class="text-white font-medium text-sm break-all">loading...</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-key text-white/50 w-5 text-sm"></i>
                            <div class="ml-2">
                                <p class="text-xs text-white/70">Account Status</p>
                                <p class="text-green-400 font-medium text-sm">Active</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- API Keys Section -->
                <div class="glass-effect rounded-2xl p-5 floating-card xl:col-span-3">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white">API Keys</h2>
                            <p class="text-white/70 text-sm">Manage your project API keys</p>
                        </div>
                        <button onclick="openNewKeyModal()" class="btn-primary text-white px-5 py-2 rounded-lg font-medium flex items-center justify-center mt-3 sm:mt-0 text-sm">
                            <i class="fas fa-plus mr-2"></i> New API Key
                        </button>
                    </div>
                    
                    <!-- New Key Form (Hidden by default) -->
                    <form id="new-key-form" action="php/generate_key.php" method="POST" class="hidden mb-8 glass-effect p-6 rounded-xl">
                        <h3 class="text-lg font-semibold text-white mb-4">Generate New API Key</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="project_name" class="block text-sm font-medium text-white/80 mb-2">Project Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-project-diagram text-white/50"></i>
                                    </div>
                                    <input type="text" id="project_name" name="project_name" required 
                                           class="w-full pl-10 pr-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                           placeholder="My Awesome Project">
                                </div>
                            </div>
                            <div class="flex justify-end space-x-3 pt-2">
                                <button type="button" id="cancel-key-btn" class="px-4 py-2 text-white/70 hover:text-white transition">
                                    Cancel
                                </button>
                                <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium flex items-center">
                                    <i class="fas fa-key mr-2"></i> Generate Key
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- API Keys List -->
                    <div class="rounded-xl">
                        <!-- Desktop Table View -->
                        <div class="mobile-table overflow-x-auto">
                            <table class="min-w-full divide-y divide-white/10">
                                <thead class="bg-white/5">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Project</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">API Key</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Private Key</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Created</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="api-keys" class="divide-y divide-white/5">
                                    <!-- API keys will be loaded here via JavaScript -->
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-center text-white/60">
                                            Loading your API keys...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Mobile Card View -->
                        <div id="mobile-api-keys" class="mobile-cards space-y-3">
                            <!-- Mobile cards will be loaded here via JavaScript -->
                            <div class="api-key-card text-center text-white/60">
                                Loading your API keys...
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 text-sm text-white/50 text-center">
                        <p>Need help with API integration? <a href="index.php/#sdk" class="text-purple-300 hover:text-white transition">View Documentation</a></p>
                    </div>
                </div>

                <!-- Players Management Section -->
                <div class="glass-effect rounded-2xl p-5 floating-card xl:col-span-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white">Players Management</h2>
                            <p class="text-white/70 text-sm">Manage players across your projects</p>
                        </div>
                    </div>

                    <!-- Project Selector for Players -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-white/80 mb-2">Select Project</label>
                        <select id="player-project-select" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Loading projects...</option>
                        </select>
                    </div>

                    <!-- Players List -->
                    <div class="rounded-xl overflow-hidden">
                        <div class="mobile-table overflow-x-auto">
                            <table class="min-w-full divide-y divide-white/10">
                                <thead class="bg-white/5">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Player Name</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Last Activity</th>
                                        <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="players-list" class="divide-y divide-white/5">
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-white/60">
                                            Select a project to view players
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Matchmaking Management Section -->
                <div class="glass-effect rounded-2xl p-5 floating-card xl:col-span-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white">Matchmaking Management</h2>
                            <p class="text-white/70 text-sm">Manage matchmaking lobbies across your projects</p>
                        </div>
                    </div>

                    <!-- Project Selector for Matchmaking -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-white/80 mb-2">Select Project</label>
                        <select id="matchmaking-project-select" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Loading projects...</option>
                        </select>
                    </div>

                    <!-- Matchmaking List -->
                    <div id="matchmaking-list-container" class="mb-4">
                        <div class="rounded-xl overflow-hidden">
                            <div class="mobile-table overflow-x-auto">
                                <table class="min-w-full divide-y divide-white/10">
                                    <thead class="bg-white/5">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Matchmaking Name</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Players</th>
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="matchmaking-list" class="divide-y divide-white/5">
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-center text-white/60">
                                                Select a project to view matchmaking lobbies
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Matchmaking Players List (Hidden by default) -->
                    <div id="matchmaking-players-container" class="hidden">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-white" id="selected-matchmaking-name">Matchmaking Players</h3>
                            <button onclick="closeMatchmakingPlayers()" class="text-white/70 hover:text-white text-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Back to List
                            </button>
                        </div>
                        <div class="rounded-xl overflow-hidden">
                            <div class="mobile-table overflow-x-auto">
                                <table class="min-w-full divide-y divide-white/10">
                                    <thead class="bg-white/5">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Player Name</th>
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="matchmaking-players-list" class="divide-y divide-white/5">
                                        <tr>
                                            <td colspan="2" class="px-4 py-4 text-center text-white/60">
                                                Loading players...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button onclick="stopMatchmaking()" class="btn-secondary text-white px-4 py-2 rounded-lg font-medium text-sm">
                                <i class="fas fa-stop mr-2"></i> Stop Matchmaking
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rooms Management Section -->
                <div class="glass-effect rounded-2xl p-5 floating-card xl:col-span-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-5">
                        <div>
                            <h2 class="text-xl sm:text-2xl font-bold text-white">Rooms Management</h2>
                            <p class="text-white/70 text-sm">Manage game rooms across your projects</p>
                        </div>
                    </div>

                    <!-- Project Selector for Rooms -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-white/80 mb-2">Select Project</label>
                        <select id="rooms-project-select" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Loading projects...</option>
                        </select>
                    </div>

                    <!-- Rooms List -->
                    <div id="rooms-list-container" class="mb-4">
                        <div class="rounded-xl overflow-hidden">
                            <div class="mobile-table overflow-x-auto">
                                <table class="min-w-full divide-y divide-white/10">
                                    <thead class="bg-white/5">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Room Name</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Players</th>
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rooms-list" class="divide-y divide-white/5">
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-center text-white/60">
                                                Select a project to view rooms
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Room Players List (Hidden by default) -->
                    <div id="room-players-container" class="hidden">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold text-white" id="selected-room-name">Room Players</h3>
                            <button onclick="closeRoomPlayers()" class="text-white/70 hover:text-white text-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Back to List
                            </button>
                        </div>
                        <div class="rounded-xl overflow-hidden">
                            <div class="mobile-table overflow-x-auto">
                                <table class="min-w-full divide-y divide-white/10">
                                    <thead class="bg-white/5">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-white/70 uppercase tracking-wider">Player Name</th>
                                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-white/70 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="room-players-list" class="divide-y divide-white/5">
                                        <tr>
                                            <td colspan="2" class="px-4 py-4 text-center text-white/60">
                                                Loading players...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button onclick="stopRoom()" class="btn-secondary text-white px-4 py-2 rounded-lg font-medium text-sm">
                                <i class="fas fa-stop mr-2"></i> Stop Room
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Rename Player Modal -->
    <div id="renamePlayerModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="glass-effect rounded-2xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Rename Player</h3>
                <button onclick="closeRenamePlayerModal()" class="text-white/50 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="renamePlayerForm" class="space-y-4">
                <input type="hidden" id="rename-player-id">
                <div>
                    <label for="new-player-name" class="block text-sm font-medium text-white/80 mb-1">New Name</label>
                    <input type="text" id="new-player-name" name="new_player_name" required
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Enter new player name">
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeRenamePlayerModal()" class="px-4 py-2 rounded-lg text-white/80 hover:text-white transition">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        Rename
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- New API Key Modal -->
    <div id="newKeyModal" class="hidden fixed inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="glass-effect rounded-2xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">Create New API Key</h3>
                <button onclick="closeNewKeyModal()" class="text-white/50 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="newKeyForm" class="space-y-4">
                <div>
                    <label for="project_name" class="block text-sm font-medium text-white/80 mb-1">Project Name</label>
                    <input type="text" id="project_name" name="project_name" required
                           class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="My Awesome Project">
                </div>
                <div class="flex justify-end space-x-3 pt-2">
                    <button type="button" onclick="closeNewKeyModal()" class="px-4 py-2 rounded-lg text-white/80 hover:text-white transition">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        Create Key
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Add click event to the button (as a fallback to the onclick attribute)
            const newKeyBtn = document.querySelector('button[onclick*="openNewKeyModal"]');
            if (newKeyBtn) {
                console.log('Found New API Key button, adding click event listener');
                newKeyBtn.addEventListener('click', openNewKeyModal);
            } else {
                console.error('Could not find New API Key button');
            }
        });

        // Modal functions
        function openNewKeyModal() {
            console.log('openNewKeyModal called');
            const modal = document.getElementById('newKeyModal');
            console.log('Modal element:', modal);
            if (modal) {
                console.log('Removing hidden class');
                modal.classList.remove('hidden');
                console.log('Modal classes after removal:', modal.className);
            } else {
                console.error('Could not find newKeyModal element');
            }
        }

        function closeNewKeyModal() {
            document.getElementById('newKeyModal').classList.add('hidden');
        }

        // Handle new key form submission
        document.getElementById('newKeyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('php/generate_key.php', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                } else {
                    closeNewKeyModal();
                    location.reload(); // Reload to show the new key
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the API key');
            });
        });

        // Load user data and API keys
        fetch('php/get_user_data.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    window.location.href = 'login.html?error=' + encodeURIComponent(data.error);
                    return;
                }
                document.getElementById('email').textContent = data.user.email;
                const tbody = document.getElementById('api-keys');
                const mobileContainer = document.getElementById('mobile-api-keys');
                
                // Clear existing content
                tbody.innerHTML = '';
                mobileContainer.innerHTML = '';
                
                if (data.api_keys.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-white/60">No API keys found. Create your first one!</td></tr>';
                    mobileContainer.innerHTML = '<div class="api-key-card text-center text-white/60">No API keys found. Create your first one!</div>';
                } else {
                    data.api_keys.forEach(key => {
                        // Desktop table row
                        const row = document.createElement('tr');
                        row.dataset.keyId = key.id;
                        row.innerHTML = `
                            <td class="px-4 py-3 font-medium">${key.project_name}</td>
                            <td class="px-4 py-3 font-mono text-sm">
                                <span class="inline-flex items-center gap-2">
                                    <span class="break-all max-w-[200px]">${key.api_key}</span>
                                    <button onclick="copyToClipboard('${key.api_key}', this)" class="copy-btn" title="Copy API key">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-sm api-private-key">
                                <span class="inline-flex items-center gap-2">
                                    <span class="break-all max-w-[200px]">${key.api_private_key}</span>
                                    <button onclick="copyToClipboard('${key.api_private_key}', this)" class="copy-btn" title="Copy private key">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">${new Date(key.created_at).toLocaleDateString()}</td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="resetApiKey('${key.id}')" class="btn-secondary text-white px-3 py-1.5 rounded text-xs font-medium mb-1 inline-block">
                                    <i class="fas fa-key mr-1"></i> Reset
                                </button>
                                <button onclick="removeApiKey('${key.id}')" class="btn-secondary text-white px-3 py-1.5 rounded text-xs font-medium inline-block">
                                    <i class="fas fa-trash-alt mr-1"></i> Remove
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                        
                        // Mobile card
                        const card = document.createElement('div');
                        card.className = 'api-key-card';
                        card.dataset.keyId = key.id;
                        card.innerHTML = `
                            <div class="mb-3">
                                <h4 class="text-white font-semibold text-lg mb-1">${key.project_name}</h4>
                                <p class="text-white/50 text-xs">Created: ${new Date(key.created_at).toLocaleDateString()}</p>
                            </div>
                            <div class="space-y-2 mb-4">
                                <div>
                                    <p class="text-white/70 text-xs mb-1">API Key:</p>
                                    <div class="flex items-start gap-2">
                                        <p class="font-mono text-sm text-white break-all flex-1">${key.api_key}</p>
                                        <button onclick="copyToClipboard('${key.api_key}', this)" class="copy-btn mt-1" title="Copy API key">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-white/70 text-xs mb-1">Private Key:</p>
                                    <div class="flex items-start gap-2">
                                        <p class="font-mono text-sm text-white break-all flex-1 api-private-key">${key.api_private_key}</p>
                                        <button onclick="copyToClipboard('${key.api_private_key}', this)" class="copy-btn mt-1" title="Copy private key">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="resetApiKey('${key.id}')" class="btn-secondary text-white px-4 py-2 rounded-lg text-sm font-medium flex-1">
                                    <i class="fas fa-key mr-2"></i> Reset
                                </button>
                                <button onclick="removeApiKey('${key.id}')" class="btn-secondary text-white px-4 py-2 rounded-lg text-sm font-medium flex-1">
                                    <i class="fas fa-trash-alt mr-2"></i> Remove
                                </button>
                            </div>
                        `;
                        mobileContainer.appendChild(card);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading user data:', error);
                window.location.href = 'login.html?error=' + encodeURIComponent('Failed to load user data: ' + error.message);
            });
        

        // Function to reset Private Key
        function resetApiKey(keyId) {
            if (!confirm('Are you sure you want to reset this API key? This action cannot be undone.')) {
                return;
            }

            console.log('Attempting to reset key ID:', keyId);
            
            fetch(`php/reset_key.php?id=${keyId}`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    console.error('Response not OK, status:', response.status);
                    return response.json().then(err => {
                        console.error('Error response:', err);
                        throw new Error(err.error || 'Failed to reset API key');
                    });
                }
                return response.json();
            })
            .then(data => {
                // Update desktop table
                const row = document.querySelector(`tr[data-key-id="${keyId}"]`);
                if (row) {
                    const privateCell = row.querySelector('.api-private-key');
                    const updatedCell = row.querySelector('td:nth-child(4)');
                    
                    const newKey = String(data.new_private_key).trim();
                    
                    if (privateCell) {
                        // Update with new layout including copy button
                        privateCell.innerHTML = `
                            <span class="inline-flex items-center gap-2">
                                <span class="break-all max-w-[200px]">${newKey}</span>
                                <button onclick="copyToClipboard('${newKey}', this)" class="copy-btn" title="Copy private key">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </span>
                        `;
                    }
                }
                
                // Update mobile card
                const card = document.querySelector(`.api-key-card[data-key-id="${keyId}"]`);
                if (card) {
                    const privateCell = card.querySelector('.api-private-key');
                    const dateCell = card.querySelector('.text-white/50.text-xs');
                    
                    const newKey = String(data.new_private_key).trim();
                    
                    if (privateCell) {
                        // Update with new layout including copy button
                        privateCell.parentElement.innerHTML = `
                            <div class="flex items-start gap-2">
                                <p class="font-mono text-sm text-white break-all flex-1 api-private-key">${newKey}</p>
                                <button onclick="copyToClipboard('${newKey}', this)" class="copy-btn mt-1" title="Copy private key">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        `;
                    }
                }

                alert('API key reset successfully');
            })
            .catch(error => {
                console.error('Error reset API key:', error);
                alert('Error: ' + (error.message || 'An error occurred while resetting the API key'));
            });
        }
        
        // Function to delete an API key
        function removeApiKey(keyId) {
            if (!confirm('Are you sure you want to delete this API key? This action cannot be undone.')) {
                return;
            }

            console.log('Attempting to delete key ID:', keyId);
            
            fetch(`php/delete_key.php?id=${keyId}`, {
                method: 'DELETE',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    console.error('Response not OK, status:', response.status);
                    return response.json().then(err => {
                        console.error('Error response:', err);
                        throw new Error(err.error || 'Failed to delete API key');
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Delete successful, response:', data);
                if (data.error) {
                    alert('Error: ' + data.error);
                } else {
                    // Remove from desktop table
                    const row = document.querySelector(`tr[data-key-id="${keyId}"]`);
                    if (row) {
                        row.remove();
                    }
                    
                    // Remove from mobile cards
                    const card = document.querySelector(`.api-key-card[data-key-id="${keyId}"]`);
                    if (card) {
                        card.remove();
                    }
                    
                    // Check if empty and show empty state
                    const tbody = document.getElementById('api-keys');
                    const mobileContainer = document.getElementById('mobile-api-keys');
                    
                    if (tbody.children.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-4 text-center text-white/60">No API keys found. Create your first one!</td></tr>';
                        mobileContainer.innerHTML = '<div class="api-key-card text-center text-white/60">No API keys found. Create your first one!</div>';
                    }
                    
                    alert('API key deleted successfully');
                }
            })
            .catch(error => {
                console.error('Error deleting API key:', error);
                alert('Error: ' + (error.message || 'An error occurred while deleting the API key'));
            });
        }
        
        // Copy to clipboard function
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check"></i>';
                button.classList.add('copied');
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('copied');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('Failed to copy to clipboard');
            });
        }

        // Global storage for API keys and project data
        let projectsData = [];
        let currentMatchmakingId = null;
        let currentRoomId = null;

        // Load projects into dropdowns after user data is loaded
        const originalFetch = fetch;
        fetch('php/get_user_data.php')
            .then(response => response.json())
            .then(data => {
                if (data.api_keys && data.api_keys.length > 0) {
                    projectsData = data.api_keys;
                    populateProjectDropdowns(data.api_keys);
                }
            })
            .catch(error => console.error('Error loading projects:', error));

        function populateProjectDropdowns(apiKeys) {
            const dropdowns = ['player-project-select', 'matchmaking-project-select', 'rooms-project-select'];
            dropdowns.forEach(id => {
                const select = document.getElementById(id);
                if (select) {
                    select.innerHTML = '<option value="">Select a project...</option>';
                    apiKeys.forEach(key => {
                        const option = document.createElement('option');
                        option.value = key.id;
                        option.textContent = key.project_name;
                        option.dataset.apiToken = key.api_key;
                        option.dataset.privateToken = key.api_private_key;
                        select.appendChild(option);
                    });
                }
            });
        }

        // Player Management Functions
        document.getElementById('player-project-select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                loadPlayers(selectedOption.dataset.apiToken, selectedOption.dataset.privateToken);
            } else {
                document.getElementById('players-list').innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-white/60">Select a project to view players</td></tr>';
            }
        });

        function loadPlayers(apiToken, privateToken) {
            fetch(`api/game_players.php/list?api_token=${apiToken}&private_token=${privateToken}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('players-list');
                    if (data.success && data.players) {
                        if (data.players.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-white/60">No players found</td></tr>';
                            return;
                        }
                        tbody.innerHTML = data.players.map(player => {
                            const lastActivity = getLastActivity(player);
                            const statusClass = player.is_online ? 'text-green-400' : 'text-gray-400';
                            const statusText = player.is_online ? 'Online' : 'Offline';
                            const isBanned = player.is_banned || false;
                            return `
                                <tr>
                                    <td class="px-4 py-3 font-medium">${player.player_name}</td>
                                    <td class="px-4 py-3 ${statusClass}">${statusText}</td>
                                    <td class="px-4 py-3 text-sm">${lastActivity}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button onclick="openRenamePlayerModal('${player.id}', '${player.player_name}', '${apiToken}')" class="btn-primary text-white px-3 py-1.5 rounded text-xs font-medium mr-1 inline-block">
                                            <i class="fas fa-edit mr-1"></i> Rename
                                        </button>
                                        ${isBanned ?
                                            `<button onclick="unbanPlayer('${player.id}', '${apiToken}', '${privateToken}')" class="btn-primary text-white px-3 py-1.5 rounded text-xs font-medium inline-block">
                                                <i class="fas fa-unlock mr-1"></i> Unban
                                            </button>` :
                                            `<button onclick="banPlayer('${player.id}', '${apiToken}', '${privateToken}')" class="btn-secondary text-white px-3 py-1.5 rounded text-xs font-medium inline-block">
                                                <i class="fas fa-ban mr-1"></i> Ban
                                            </button>`
                                        }
                                    </td>
                                </tr>
                            `;
                        }).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-white/60">Error loading players</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error loading players:', error);
                    document.getElementById('players-list').innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-center text-white/60">Error loading players</td></tr>';
                });
        }

        function getLastActivity(player) {
            if (player.is_online) return 'Online now';
            const login = player.last_login ? new Date(player.last_login) : null;
            const logout = player.last_logout ? new Date(player.last_logout) : null;
            const heartbeat = player.last_heartbeat ? new Date(player.last_heartbeat) : null;
            
            const timestamps = [login, logout, heartbeat].filter(t => t !== null);
            if (timestamps.length === 0) return 'Never';
            
            const latest = new Date(Math.max(...timestamps));
            return latest.toLocaleString();
        }

        // Rename Player Modal
        function openRenamePlayerModal(playerId, currentName, apiToken) {
            document.getElementById('rename-player-id').value = playerId;
            document.getElementById('rename-player-id').dataset.apiToken = apiToken;
            document.getElementById('new-player-name').value = currentName;
            document.getElementById('renamePlayerModal').classList.remove('hidden');
        }

        function closeRenamePlayerModal() {
            document.getElementById('renamePlayerModal').classList.add('hidden');
        }

        document.getElementById('renamePlayerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const playerId = document.getElementById('rename-player-id').value;
            const apiToken = document.getElementById('rename-player-id').dataset.apiToken;
            const newName = document.getElementById('new-player-name').value;

            // Note: Rename requires player_token, not api_token
            // For admin panel, we need to handle this differently
            alert('Rename functionality requires player token. This feature needs to be implemented with player authentication.');
            closeRenamePlayerModal();
        });

        function banPlayer(playerId, apiToken, privateToken) {
            const reason = prompt('Enter ban reason:');
            if (!reason) return;
            
            const duration = prompt('Enter ban duration (hour, day, week, month, forever):', 'day');
            if (!duration) return;

            fetch(`api/game_players.php/ban?api_token=${apiToken}&private_token=${privateToken}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    player_id: playerId,
                    ban_duration: duration,
                    ban_reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Player banned successfully');
                    // Reload players
                    const select = document.getElementById('player-project-select');
                    const selectedOption = select.options[select.selectedIndex];
                    loadPlayers(selectedOption.dataset.apiToken, selectedOption.dataset.privateToken);
                } else {
                    alert('Error: ' + (data.error || 'Failed to ban player'));
                }
            })
            .catch(error => {
                console.error('Error banning player:', error);
                alert('Error banning player');
            });
        }

        function unbanPlayer(playerId, apiToken, privateToken) {
            fetch(`api/game_players.php/unban?api_token=${apiToken}&private_token=${privateToken}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ player_id: playerId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Player unbanned successfully');
                    // Reload players
                    const select = document.getElementById('player-project-select');
                    const selectedOption = select.options[select.selectedIndex];
                    loadPlayers(selectedOption.dataset.apiToken, selectedOption.dataset.privateToken);
                } else {
                    alert('Error: ' + (data.error || 'Failed to unban player'));
                }
            })
            .catch(error => {
                console.error('Error unbanning player:', error);
                alert('Error unbanning player');
            });
        }

        // Matchmaking Management Functions
        document.getElementById('matchmaking-project-select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                loadMatchmaking(selectedOption.dataset.apiToken);
            } else {
                document.getElementById('matchmaking-list').innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">Select a project to view matchmaking lobbies</td></tr>';
            }
        });

        function loadMatchmaking(apiToken) {
            fetch(`api/matchmaking.php/list?api_token=${apiToken}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ search: '', limit: 50 })
            })
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('matchmaking-list');
                if (data.success && data.lobbies) {
                    if (data.lobbies.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">No matchmaking lobbies found</td></tr>';
                        return;
                    }
                    tbody.innerHTML = data.lobbies.map(lobby => `
                        <tr>
                            <td class="px-4 py-3 font-medium">${lobby.matchmaking_name}</td>
                            <td class="px-4 py-3 text-sm">${lobby.current_players}/${lobby.max_players}</td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="viewMatchmakingPlayers('${lobby.matchmaking_id}', '${lobby.matchmaking_name}', '${apiToken}')" class="btn-primary text-white px-3 py-1.5 rounded text-xs font-medium">
                                    <i class="fas fa-users mr-1"></i> View Players
                                </button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">Error loading matchmaking lobbies</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error loading matchmaking:', error);
                document.getElementById('matchmaking-list').innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">Error loading matchmaking lobbies</td></tr>';
            });
        }

        function viewMatchmakingPlayers(matchmakingId, matchmakingName, apiToken) {
            currentMatchmakingId = matchmakingId;
            document.getElementById('selected-matchmaking-name').textContent = matchmakingName + ' - Players';
            document.getElementById('matchmaking-list-container').classList.add('hidden');
            document.getElementById('matchmaking-players-container').classList.remove('hidden');

            // Note: This endpoint requires player_token, which we don't have in admin panel
            // For now, show a message
            document.getElementById('matchmaking-players-list').innerHTML = `
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-white/60">
                        Player list requires player token authentication. This feature needs a player to be in the matchmaking to view.
                    </td>
                </tr>
            `;
        }

        function closeMatchmakingPlayers() {
            document.getElementById('matchmaking-list-container').classList.remove('hidden');
            document.getElementById('matchmaking-players-container').classList.add('hidden');
            currentMatchmakingId = null;
        }

        function kickFromMatchmaking(playerId) {
            if (!confirm('Are you sure you want to kick this player from matchmaking?')) return;
            
            // Note: Requires player_token
            alert('Kick functionality requires player token. This feature needs to be implemented with player authentication.');
        }

        function stopMatchmaking() {
            if (!confirm('Are you sure you want to stop this matchmaking lobby?')) return;
            
            // Note: Requires player_token
            alert('Stop functionality requires player token. This feature needs to be implemented with player authentication.');
        }

        // Rooms Management Functions
        document.getElementById('rooms-project-select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                loadRooms(selectedOption.dataset.apiToken);
            } else {
                document.getElementById('rooms-list').innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">Select a project to view rooms</td></tr>';
            }
        });

        function loadRooms(apiToken) {
            fetch(`api/game_room.php/list?api_token=${apiToken}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ search: '', limit: 50 })
            })
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('rooms-list');
                if (data.success && data.rooms) {
                    if (data.rooms.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">No rooms found</td></tr>';
                        return;
                    }
                    tbody.innerHTML = data.rooms.map(room => `
                        <tr>
                            <td class="px-4 py-3 font-medium">${room.room_name}</td>
                            <td class="px-4 py-3 text-sm">${room.current_players}/${room.max_players}</td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="viewRoomPlayers('${room.room_id}', '${room.room_name}', '${apiToken}')" class="btn-primary text-white px-3 py-1.5 rounded text-xs font-medium">
                                    <i class="fas fa-users mr-1"></i> View Players
                                </button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">Error loading rooms</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error loading rooms:', error);
                document.getElementById('rooms-list').innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-white/60">Error loading rooms</td></tr>';
            });
        }

        function viewRoomPlayers(roomId, roomName, apiToken) {
            currentRoomId = roomId;
            document.getElementById('selected-room-name').textContent = roomName + ' - Players';
            document.getElementById('rooms-list-container').classList.add('hidden');
            document.getElementById('room-players-container').classList.remove('hidden');

            // Note: This endpoint requires player_token, which we don't have in admin panel
            // For now, show a message
            document.getElementById('room-players-list').innerHTML = `
                <tr>
                    <td colspan="2" class="px-4 py-4 text-center text-white/60">
                        Player list requires player token authentication. This feature needs a player to be in the room to view.
                    </td>
                </tr>
            `;
        }

        function closeRoomPlayers() {
            document.getElementById('rooms-list-container').classList.remove('hidden');
            document.getElementById('room-players-container').classList.add('hidden');
            currentRoomId = null;
        }

        function kickFromRoom(playerId) {
            if (!confirm('Are you sure you want to kick this player from the room?')) return;
            
            // Note: Requires player_token
            alert('Kick functionality requires player token. This feature needs to be implemented with player authentication.');
        }

        function stopRoom() {
            if (!confirm('Are you sure you want to stop this room?')) return;
            
            // Note: Requires player_token
            alert('Stop functionality requires player token. This feature needs to be implemented with player authentication.');
        }
    </script>
</body>
</html>
