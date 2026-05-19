<?php
session_start();
require_once 'php/config.php';

// Set page-specific meta tag variables
$title = "Multiplayer API – Servers";
$description = "View our global server infrastructure across Europe, North America, Asia, and India.";
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
    
    <?php require_once 'php/meta-tags.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
        
        .server-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
        }
        
        .server-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            border-color: rgba(255,255,255,0.2);
        }
        
        .region-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .server-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.2s ease;
        }
        
        .server-item:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(79, 172, 254, 0.3);
            transform: translateX(5px);
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
                    <a href="index.php" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-home mr-2"></i>Home
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="cabinet.html" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                            <i class="fas fa-user-circle mr-2"></i>Cabinet
                        </a>
                    <?php else: ?>
                        <a href="login.html" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-24 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                Global Server
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-500">Infrastructure</span>
            </h1>
            <p class="text-xl text-white/80 mb-8 max-w-2xl mx-auto">
                Low-latency servers strategically located across Europe, North America, Asia, and India for optimal performance worldwide.
            </p>
        </div>
    </section>

    <!-- Servers Section -->
    <section class="relative py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <div class="space-y-12">
                
                <!-- Europe -->
                <div class="server-card p-8 rounded-2xl">
                    <h3 class="text-3xl font-bold text-white mb-6 flex items-center">
                        <div class="region-icon mr-4">
                            <i class="fas fa-globe-europe text-2xl text-blue-400"></i>
                        </div>
                        Europe
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">fra.michitai.com</div>
                                <div class="text-white/60 text-sm">Frankfurt, Germany</div>
                            </div>
                        </div>
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">vil.michitai.com</div>
                                <div class="text-white/60 text-sm">Vilnius, Lithuania</div>
                            </div>
                        </div>
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">lon.michitai.com</div>
                                <div class="text-white/60 text-sm">London, United Kingdom</div>
                            </div>
                        </div>
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">par.michitai.com</div>
                                <div class="text-white/60 text-sm">Paris, France</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- North America -->
                <div class="server-card p-8 rounded-2xl">
                    <h3 class="text-3xl font-bold text-white mb-6 flex items-center">
                        <div class="region-icon mr-4">
                            <i class="fas fa-globe-americas text-2xl text-green-400"></i>
                        </div>
                        North America
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">bos.michitai.com</div>
                                <div class="text-white/60 text-sm">Boston</div>
                            </div>
                        </div>
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">phx.michitai.com</div>
                                <div class="text-white/60 text-sm">Phoenix</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Asia -->
                <div class="server-card p-8 rounded-2xl">
                    <h3 class="text-3xl font-bold text-white mb-6 flex items-center">
                        <div class="region-icon mr-4">
                            <i class="fas fa-globe-asia text-2xl text-orange-400"></i>
                        </div>
                        Asia
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">jkt.michitai.com</div>
                                <div class="text-white/60 text-sm">Jakarta, Indonesia</div>
                            </div>
                        </div>
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">kul.michitai.com</div>
                                <div class="text-white/60 text-sm">Kuala Lumpur, Malaysia</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- India -->
                <div class="server-card p-8 rounded-2xl">
                    <h3 class="text-3xl font-bold text-white mb-6 flex items-center">
                        <div class="region-icon mr-4">
                            <i class="fas fa-globe text-2xl text-purple-400"></i>
                        </div>
                        India
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="server-item flex items-center space-x-4 p-4 rounded-lg">
                            <i class="fas fa-server text-cyan-400 text-xl"></i>
                            <div>
                                <div class="text-white font-semibold">bom.michitai.com</div>
                                <div class="text-white/60 text-sm">Mumbai, India</div>
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
</body>
</html>
