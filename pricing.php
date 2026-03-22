<?php
session_start();
require_once 'php/config.php';

// Set page-specific meta tag variables
$title = "Pricing Plans – Multiplayer API";
$description = "Flexible pricing plans for every game development need. From indie developers to enterprise studios.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/logo.png";
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="icon" type="image/png" href="logo.png">
    
    <?php require_once 'php/meta-tags.php'; ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --starter-gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --pro-gradient: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            --enterprise-gradient: linear-gradient(135deg, #1e293b 0%, #334155 100%);
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
        
        .pricing-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }
        
        .pricing-card.featured {
            border-color: #667eea;
            transform: scale(1.05);
        }
        
        .pricing-card.featured:hover {
            transform: scale(1.08) translateY(-8px);
        }
        
        .btn-starter {
            background: var(--starter-gradient);
        }
        
        .btn-pro {
            background: var(--pro-gradient);
        }
        
        .btn-enterprise {
            background: var(--enterprise-gradient);
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
        
        .feature-check {
            color: #22c55e;
        }
        
        .feature-cross {
            color: #ef4444;
        }
        
        .price-tag {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1;
        }
        
        .currency {
            font-size: 1.5rem;
            vertical-align: super;
        }
        
        .period {
            font-size: 1rem;
            color: #6b7280;
        }
        
        @media (max-width: 768px) {
            .pricing-card.featured {
                transform: none;
            }
            
            .pricing-card.featured:hover {
                transform: translateY(-4px);
            }
        }
    </style>
</head>
<body class="min-h-screen animated-bg">
    <!-- Header -->
    <header class="glass-effect border-b border-white/20 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="logo.png" alt="Multiplayer API Logo" class="w-10 h-10 rounded-xl object-contain">
                    <div>
                        <h1 class="text-lg font-bold text-white">Multiplayer API</h1>
                        <p class="text-xs text-white/70">Core Cells</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
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
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-black text-white mb-6 leading-tight">
                Choose Your<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-500">Game Development Plan</span>
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto mb-8">
                Scalable pricing for multiplayer games of any size. From indie prototypes to enterprise gaming platforms.
            </p>
            
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <div class="glass-effect px-6 py-3 rounded-full">
                    <i class="fas fa-check-circle text-green-400 mr-2"></i>
                    <span class="text-white">No setup fees</span>
                </div>
                <div class="glass-effect px-6 py-3 rounded-full">
                    <i class="fas fa-bolt text-yellow-400 mr-2"></i>
                    <span class="text-white">Instant activation</span>
                </div>
                <div class="glass-effect px-6 py-3 rounded-full">
                    <i class="fas fa-shield-alt text-blue-400 mr-2"></i>
                    <span class="text-white">Secure & reliable</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Free Plan -->
                <div class="pricing-card glass-effect rounded-2xl p-8 text-white">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-gift text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Free</h3>
                        <p class="text-white/70 mb-6">Perfect for testing and small prototypes</p>
                        <div class="mb-6">
                            <span class="currency">$</span>
                            <span class="price-tag">0</span>
                            <span class="period">/month</span>
                        </div>
                        <a href="register.html" class="w-full py-3 rounded-lg font-semibold text-white block text-center bg-green-600 hover:bg-green-700 transition-colors">
                            Get Started Free
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Up to 10 Players</h4>
                                <p class="text-sm text-white/70">Concurrent players across all games</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">1 Game Project</h4>
                                <p class="text-sm text-white/70">Single API key for your game</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">100MB Game Data Storage</h4>
                                <p class="text-sm text-white/70">JSON game state and player data</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Basic Matchmaking</h4>
                                <p class="text-sm text-white/70">Simple room creation and joining</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Unity & .NET SDKs</h4>
                                <p class="text-sm text-white/70">Full SDK access with documentation</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Community Support</h4>
                                <p class="text-sm text-white/70">Discord community and forums</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-times-circle feature-cross mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1 text-white/50">Advanced Analytics</h4>
                                <p class="text-sm text-white/50">Not included in Free</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-times-circle feature-cross mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1 text-white/50">Priority Support</h4>
                                <p class="text-sm text-white/50">Not included in Free</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Starter Plan -->
                <div class="pricing-card glass-effect rounded-2xl p-8 text-white">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-rocket text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Starter</h3>
                        <p class="text-white/70 mb-6">Perfect for indie developers and small games</p>
                        <div class="mb-6">
                            <span class="currency">$</span>
                            <span class="price-tag">9</span>
                            <span class="period">/month</span>
                        </div>
                        <a href="register.html" class="btn-starter w-full py-3 rounded-lg font-semibold text-white block text-center hover:opacity-90 transition-opacity">
                            Get Started
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Up to 100 Players</h4>
                                <p class="text-sm text-white/70">Concurrent players across all games</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">3 Game Projects</h4>
                                <p class="text-sm text-white/70">Separate API keys for each game</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">1GB Game Data Storage</h4>
                                <p class="text-sm text-white/70">JSON game state and player data</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Basic Matchmaking</h4>
                                <p class="text-sm text-white/70">Simple room creation and joining</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Unity & .NET SDKs</h4>
                                <p class="text-sm text-white/70">Full SDK access with documentation</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Community Support</h4>
                                <p class="text-sm text-white/70">Discord community and forums</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-times-circle feature-cross mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1 text-white/50">Advanced Analytics</h4>
                                <p class="text-sm text-white/50">Not included in Starter</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-times-circle feature-cross mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1 text-white/50">Priority Support</h4>
                                <p class="text-sm text-white/50">Not included in Starter</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pro Plan (Featured) -->
                <div class="pricing-card featured glass-effect rounded-2xl p-8 text-white relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 px-6 py-2 rounded-full">
                            <span class="text-black font-bold text-sm">MOST POPULAR</span>
                        </div>
                    </div>
                    
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-star text-3xl text-white"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Pro</h3>
                        <p class="text-white/70 mb-6">Ideal for growing games and studios</p>
                        <div class="mb-6">
                            <span class="currency">$</span>
                            <span class="price-tag">49</span>
                            <span class="period">/month</span>
                        </div>
                        <a href="register.html" class="btn-pro w-full py-3 rounded-lg font-semibold text-white block text-center hover:opacity-90 transition-opacity">
                            Start Pro Trial
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Up to 2,000 Players</h4>
                                <p class="text-sm text-white/70">Concurrent players across all games</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">15 Game Projects</h4>
                                <p class="text-sm text-white/70">Separate API keys for each game</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">10GB Game Data Storage</h4>
                                <p class="text-sm text-white/70">JSON game state and player data</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Advanced Matchmaking</h4>
                                <p class="text-sm text-white/70">Custom criteria, host approval, ranking</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Real-time Analytics</h4>
                                <p class="text-sm text-white/70">Player metrics, game performance data</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Priority Support</h4>
                                <p class="text-sm text-white/70">24-hour response time</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-check-circle feature-check mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1">Custom Domains</h4>
                                <p class="text-sm text-white/70">Branded API endpoints</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <i class="fas fa-times-circle feature-cross mt-1 mr-3 flex-shrink-0"></i>
                            <div>
                                <h4 class="font-semibold mb-1 text-white/50">Dedicated Resources</h4>
                                <p class="text-sm text-white/50">Not included in Pro</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feature Comparison Table -->
            <div class="glass-effect rounded-2xl p-8 text-white">
                <h3 class="text-2xl font-bold mb-8 text-center">Complete Feature Comparison</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/20">
                                <th class="text-left py-4 px-4">Feature</th>
                                <th class="text-center py-4 px-4">Free</th>
                                <th class="text-center py-4 px-4">Starter</th>
                                <th class="text-center py-4 px-4">Pro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            <tr>
                                <td class="py-4 px-4">Concurrent Players</td>
                                <td class="text-center py-4 px-4">10</td>
                                <td class="text-center py-4 px-4">100</td>
                                <td class="text-center py-4 px-4">2,000</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">Game Projects</td>
                                <td class="text-center py-4 px-4">1</td>
                                <td class="text-center py-4 px-4">3</td>
                                <td class="text-center py-4 px-4">15</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">Data Storage</td>
                                <td class="text-center py-4 px-4">100MB</td>
                                <td class="text-center py-4 px-4">1GB</td>
                                <td class="text-center py-4 px-4">10GB</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">API Calls/month</td>
                                <td class="text-center py-4 px-4">10K</td>
                                <td class="text-center py-4 px-4">100K</td>
                                <td class="text-center py-4 px-4">5M</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">Matchmaking</td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">Real-time Sync</td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">Analytics Dashboard</td>
                                <td class="text-center py-4 px-4"><i class="fas fa-times text-red-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-times text-red-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">Priority Support</td>
                                <td class="text-center py-4 px-4"><i class="fas fa-times text-red-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-times text-red-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">Custom Domains</td>
                                <td class="text-center py-4 px-4"><i class="fas fa-times text-red-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-times text-red-400"></i></td>
                                <td class="text-center py-4 px-4"><i class="fas fa-check text-green-400"></i></td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4">SLA Guarantee</td>
                                <td class="text-center py-4 px-4">99.0%</td>
                                <td class="text-center py-4 px-4">99.5%</td>
                                <td class="text-center py-4 px-4">99.7%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h3 class="text-4xl font-black text-white mb-6">Frequently Asked Questions</h3>
                <p class="text-xl text-white/70">Everything you need to know about our pricing</p>
            </div>
            
            <div class="space-y-6">
                <div class="glass-effect rounded-xl p-6 text-white">
                    <h4 class="text-lg font-bold mb-3">Can I change plans anytime?</h4>
                    <p class="text-white/80">Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately, and we'll prorate any differences.</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-white">
                    <h4 class="text-lg font-bold mb-3">What happens if I exceed my limits?</h4>
                    <p class="text-white/80">We'll notify you when you're approaching your limits. You can upgrade your plan or purchase add-ons. We never cut off service unexpectedly.</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-white">
                    <h4 class="text-lg font-bold mb-3">Do you offer a free trial?</h4>
                    <p class="text-white/80">Yes! All new plans come with a 14-day free trial. No credit card required to start.</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-white">
                    <h4 class="text-lg font-bold mb-3">What payment methods do you accept?</h4>
                    <p class="text-white/80">We accept all major credit cards, PayPal, and wire transfers for Enterprise plans.</p>
                </div>
                
                <div class="glass-effect rounded-xl p-6 text-white">
                    <h4 class="text-lg font-bold mb-3">Can I cancel anytime?</h4>
                    <p class="text-white/80">Absolutely. No long-term contracts. You can cancel your subscription anytime with no penalties.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <div class="glass-effect rounded-2xl p-12 text-white">
                <h3 class="text-3xl font-bold mb-4">Ready to build amazing multiplayer games?</h3>
                <p class="text-xl text-white/80 mb-8">Join thousands of developers using Multiplayer API</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="register.html" class="btn-primary px-8 py-4 rounded-lg font-semibold text-white hover:opacity-90 transition-opacity">
                        Start Free Trial
                    </a>
                    <a href="index.php" class="btn-secondary px-8 py-4 rounded-lg font-semibold text-white hover:opacity-90 transition-opacity">
                        View Documentation
                    </a>
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
        
        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.pricing-card, .glass-effect').forEach((el) => {
            el.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            observer.observe(el);
        });
    </script>
    
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
</body>
</html>
