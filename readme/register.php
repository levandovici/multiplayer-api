<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Temporarily Unavailable - Multiplayer API</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
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
                    <a href="login.php" class="text-white hover:text-gray-200 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Log In
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Beta Testing Notice -->
    <section class="min-h-[calc(100vh-4rem)] flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <div class="glass-effect rounded-2xl p-8 shadow-xl text-center">
                <div class="mb-8">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 flex items-center justify-center mx-auto mb-6 pulse-animation">
                        <i class="fas fa-flask text-4xl text-white"></i>
                    </div>
                    <h2 class="text-4xl font-bold text-white mb-4">Beta Testing in Progress</h2>
                    <div class="inline-block bg-yellow-500/20 border border-yellow-400/30 rounded-full px-6 py-2 mb-6">
                        <span class="text-yellow-300 font-semibold">
                            <i class="fas fa-tools mr-2"></i>Under Maintenance
                        </span>
                    </div>
                </div>

                <div class="bg-white/5 rounded-xl p-6 mb-8">
                    <p class="text-xl text-white/90 mb-4">
                        We are currently testing our <strong class="text-purple-300">Multiplayer API</strong>.
                    </p>
                    <p class="text-white/70 leading-relaxed">
                        New user registrations are temporarily unavailable while we improve our services. 
                        We'll be back online soon with enhanced features and performance.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="index.php" class="btn-primary text-white py-3 px-8 rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-home mr-2"></i> Return Home
                    </a>
                    <a href="login.php" class="bg-white/10 hover:bg-white/20 text-white py-3 px-8 rounded-lg font-medium flex items-center justify-center transition border border-white/20">
                        <i class="fas fa-sign-in-alt mr-2"></i> Existing Users Login
                    </a>
                </div>

                <div class="mt-8 pt-6 border-t border-white/10">
                    <p class="text-white/50 text-sm">
                        <i class="fas fa-clock mr-2"></i>Check back soon for updates
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
