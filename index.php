<?php
session_start();
require_once 'php/config.php';

// Function to get roadmap from webhook-updated cache
function getTelegramRoadmap() {
    $logFile = __DIR__ . '/index.log';
    
    // Log function start
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] [INFO] getTelegramRoadmap() called - reading from webhook cache" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    $cacheFile = __DIR__ . '/cached.json';
    
    if (!file_exists($cacheFile)) {
        $logMessage = "[{$timestamp}] [ERROR] Cache file not found: {$cacheFile}" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        return null;
    }
    
    $cacheData = json_decode(file_get_contents($cacheFile), true);
    
    // Log cache state for debugging
    $updateSource = $cacheData['update_source'] ?? 'unknown';
    $logMessage = "[{$timestamp}] [DEBUG] Cache state - Update source: {$updateSource}, Chat ID: {$cacheData['chat_id']}, Message: " . (empty($cacheData['telegram_message']) ? 'EMPTY' : 'SET') . ", Last updated: {$cacheData['last_updated']}" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    if (!$cacheData['telegram_message']) {
        $logMessage = "[{$timestamp}] [WARNING] No telegram_message found in cache - webhook may not be set up yet" . PHP_EOL;
        file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
        return null;
    }
    
    $logMessage = "[{$timestamp}] [INFO] Parsing telegram message, length: " . strlen($cacheData['telegram_message']) . " characters" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    // Parse the message into sections
    $sections = [];
    $lines = explode("\n", $cacheData['telegram_message']);
    $currentSection = null;
    $sectionCount = 0;
    $taskCount = 0;
    
    foreach ($lines as $lineIndex => $line) {
        $line = trim($line);
        
        if (empty($line) || $line === '---------------------------------------------------------------') {
            continue;
        }
        
        // Check if line is a section header (ends with ':')
        if (preg_match('/^(.+):$/', $line, $matches)) {
            $sectionTitle = $matches[1];
            
            $currentSection = [
                'title' => $sectionTitle,
                'items' => []
            ];
            $sections[] = $currentSection;
            $sectionCount++;
            continue;
        }
        
        // Check if line is a delimiter (wrapped in angle brackets)
        if (preg_match('/^<(.+)>$/', $line, $matches)) {
            $delimiterTitle = $matches[1];
            
            // Add delimiter as a section for UI display
            $currentSection = [
                'title' => $delimiterTitle,
                'items' => []
            ];
            $sections[] = $currentSection;
            $sectionCount++;
            continue;
        }
        
        // Check if line is a task item (starts with star or escaped star)
        $isTask = false;
        
        // Check for various star patterns
        if (preg_match('/^[\x{2605}\x{2606}\x{2726}\x{2727}STARstar*]/u', $line)) {
            $isTask = true;
        }
        
        if ($isTask && $currentSection) {
            $task = trim($line);
            // Remove star and any extra whitespace
            $task = preg_replace('/^[\x{2605}\x{2606}\x{2726}\x{2727}STARstar*]\s*/u', '', $task);
            
            // Find the current section index and update it directly
            $currentSectionIndex = count($sections) - 1;
            if ($currentSectionIndex >= 0) {
                $sections[$currentSectionIndex]['items'][] = $task;
                $taskCount++;
            }
        }
    }
    
    $logMessage = "[{$timestamp}] [INFO] Parsed {$sectionCount} sections with {$taskCount} total tasks" . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
    
    return $sections;
}

// Set page-specific meta tag variables
$title = "Multiplayer API – Cross-platform";
$description = "A powerful multiplayer API for game developers. Build multiplayer games with ease using our comprehensive SDK and API.";
$image = "https://" . $_SERVER['HTTP_HOST'] . "/logo.png";
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
    <link rel="icon" type="image/png" href="/logo.png">
    
    <?php require_once 'php/meta-tags.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-csharp.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --code-bg: #1e1e2d;
            --code-text: #ffffff;
            --code-comment: #6c757d;
            --code-keyword: #569cd6;
            --code-string: #ce9178;
            --code-number: #b5cea8;
            --code-type: #4ec9b0;
            --code-function: #dcdcaa;
            --code-operator: #d4d4d4;
        }
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .glass-effect {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
        }
        
/* Code block styling – Modern, Prism-friendly version */
pre {
    background: var(--code-bg);
    color: var(--code-text);
    border-radius: 0.5rem;
    padding: 1rem;
    margin: 1rem 0;
    font-family: 'Fira Code', 'Consolas', 'Monaco', 'Andale Mono', monospace;
    font-size: 0.85em;
    line-height: 1.5;
    tab-size: 4;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    max-width: 100%;
    max-height: 100vh;
    border: 1px solid rgba(255,255,255,0.08);
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
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
    
    /* Adjust code blocks for mobile */
    pre {
        padding: 0.75rem;
        font-size: 0.8em;
        margin: 0.75rem -0.5rem;
        border-radius: 0;
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
    white-space: pre;             /* most natural behavior for code */
    word-break: normal;
    word-wrap: normal;
}

/* Extra safety for JSON (prevents collapse on long lines) */
pre code.language-json {
    white-space: pre-wrap;
    word-break: break-word;
    display: block;
    overflow-x: auto;
}

/* Make sure Prism classes are respected */
.token {
    background: none !important;  /* prevent unwanted overrides */
}

/* Optional: nicer scrollbar (modern browsers) */
pre::-webkit-scrollbar {
    height: 8px;
}
pre::-webkit-scrollbar-track {
    background: rgba(255,255,255,0.03);
}
pre::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.18);
    border-radius: 4px;
}
pre::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.3);
}
        
        /* Syntax highlighting */
        .token.comment,
        .token.prolog,
        .token.doctype,
        .token.cdata {
            color: var(--code-comment) !important;
        }
        
        .token.keyword,
        .token.operator,
        .token.boolean,
        .token.selector {
            color: var(--code-keyword) !important;
        }
        
        .token.string,
        .token.attr-value,
        .token.char,
        .token.builtin {
            color: var(--code-string) !important;
        }
        
        .token.number,
        .token.constant,
        .token.symbol {
            color: var(--code-number) !important;
        }
        
        .token.class-name,
        .token.type-definition {
            color: var(--code-type) !important;
        }
        
        .token.function,
        .token.maybe-class-name {
            color: var(--code-function) !important;
        }
        
        /* Remove gradient overlays */
        .relative.before\:content-\[\'\'\]:before,
        .relative.after\:content-\[\'\'\]:after {
            content: none !important;
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
        
        .feature-icon {
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .stats-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Roadmap card styling */
        .roadmap-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.02) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .roadmap-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
            transition: left 0.5s ease;
        }
        
        .roadmap-card:hover::before {
            left: 100%;
        }
        
        .roadmap-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
            border-color: rgba(255,255,255,0.2);
        }
        
        .task-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.2s ease;
            position: relative;
        }
        
        .task-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(135deg, #00f2fe, #4facfe);
            transform: scaleY(0);
            transition: transform 0.2s ease;
        }
        
        .task-item:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(79, 172, 254, 0.3);
            transform: translateX(5px);
        }
        
        .task-item:hover::after {
            transform: scaleY(1);
        }
        
        .section-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.1);
            margin-right: 12px;
            transition: all 0.3s ease;
        }
        
        .section-icon:hover {
            transform: rotate(360deg) scale(1.1);
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.08) 100%);
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .refreshing {
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        /* Loading spinner */
        .fa-spinner.fa-spin {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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
                Multiplayer API for<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-pink-500">Game Developers</span>
            </h1>
            
            <!-- Platform Selection -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-white mb-6 text-center">Cross-Platform</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
                    <!-- Unity Platform -->
                    <a href="unity/index.php" class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-all duration-300 group">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-purple-600 to-blue-600 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                            <i class="fab fa-unity text-3xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3 text-center">Unity</h4>
                        <p class="text-white/80 text-center mb-4">SDK for JsonUtility</p>
                        <div class="flex items-center justify-center space-x-4">
                            <span class="text-green-400 text-sm"><i class="fas fa-check-circle mr-1"></i>JsonUtility</span>
                            <span class="text-blue-400 text-sm"><i class="fas fa-code mr-1"></i>C#</span>
                        </div>
                    </a>
                    
                    <!-- .NET Platform -->
                    <a href="dotnet/index.php" class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-all duration-300 group">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-green-600 to-emerald-600 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                            <i class="fab fa-microsoft text-3xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3 text-center">.NET</h4>
                        <p class="text-white/80 text-center mb-4">SDK for System.Text.Json</p>
                        <div class="flex items-center justify-center space-x-4">
                            <span class="text-green-400 text-sm"><i class="fas fa-check-circle mr-1"></i>Modern</span>
                            <span class="text-blue-400 text-sm"><i class="fas fa-code mr-1"></i>C#</span>
                        </div>
                    </a>
                    
                    <!-- REST API Platform -->
                    <a href="api/index.php" class="glass-effect p-8 rounded-2xl hover:transform hover:scale-105 transition-all duration-300 group">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-r from-orange-600 to-red-600 flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform">
                            <i class="fas fa-plug text-3xl text-white"></i>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3 text-center">REST API</h4>
                        <p class="text-white/80 text-center mb-4">Direct REST API</p>
                        <div class="flex items-center justify-center space-x-4">
                            <span class="text-green-400 text-sm"><i class="fas fa-check-circle mr-1"></i>Universal</span>
                            <span class="text-blue-400 text-sm"><i class="fas fa-code mr-1"></i>JSON</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Telegram Roadmap Section -->
    <section class="relative py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-12 text-center">
                Development Roadmap
            </h2>
            
            <div id="roadmap-container" class="space-y-8">
                <?php
                $roadmap = getTelegramRoadmap();
                // Debug: Log what we received
                error_log("DEBUG: Roadmap data received: " . print_r($roadmap, true));
                if ($roadmap && !empty($roadmap)):
                ?>
                    <?php foreach ($roadmap as $index => $section): ?>
                        <?php if (in_array($section['title'], ['Current', 'Planning'])): ?>
                            <div class="text-center mb-8">
                                <h3 class="text-3xl font-bold text-white/90 uppercase tracking-wider">
                                    <?php echo htmlspecialchars($section['title']); ?>
                                </h3>
                            </div>
                        <?php else: ?>
                            <div class="glass-effect p-6 rounded-2xl floating-card">
                            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <?php
                                $iconColor = 'text-purple-400';
                                $icon = 'fas fa-star';
                                if (strpos(strtolower($section['title']), 'upcoming') !== false) {
                                    $iconColor = 'text-yellow-400';
                                    $icon = 'fas fa-rocket';
                                } elseif (strpos(strtolower($section['title']), 'doing') !== false) {
                                    $iconColor = 'text-blue-400';
                                    $icon = 'fas fa-spinner';
                                } elseif (strpos(strtolower($section['title']), 'done') !== false) {
                                    $iconColor = 'text-green-400';
                                    $icon = 'fas fa-check-circle';
                                } elseif (strpos(strtolower($section['title']), 'backlog') !== false) {
                                    $iconColor = 'text-gray-400';
                                    $icon = 'fas fa-archive';
                                } elseif (strpos(strtolower($section['title']), 'to do') !== false) {
                                    $iconColor = 'text-orange-400';
                                    $icon = 'fas fa-tasks';
                                }
                                ?>
                                <i class="<?php echo $icon; ?> <?php echo $iconColor; ?> mr-3"></i>
                                <?php echo htmlspecialchars($section['title']); ?> (<?php echo count($section['items']); ?> items)
                            </h3>
                            
                            <?php if (!empty($section['items'])): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <?php foreach ($section['items'] as $item): ?>
                                        <div class="task-item flex items-start space-x-3 p-3 rounded-lg bg-white/5 hover:bg-white/10 transition-colors">
                                            <i class="fas fa-chevron-right text-cyan-400 mt-1 text-sm"></i>
                                            <span class="text-white/90 text-sm"><?php echo htmlspecialchars($item); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-white/50 italic">No tasks found in this section.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    
                <?php else: ?>
                    <div class="glass-effect p-8 rounded-2xl text-center">
                        <i class="fas fa-telegram text-6xl text-blue-400 mb-4"></i>
                        <h3 class="text-2xl font-bold text-white mb-4">Roadmap Not Available</h3>
                        <p class="text-white/70 mb-6">The development roadmap is currently being set up. Please check back later.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Development Analytics Section -->
    <section class="relative py-16 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-12 text-center">
                Development Analytics
            </h2>
            
            <div class="glass-effect p-8 rounded-2xl mb-8">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <i class="fas fa-chart-line text-green-400 mr-3"></i>
                    Lines of Code Over Time
                </h3>
                
                <div class="bg-white/5 rounded-lg p-6 mb-6">
                    <canvas id="locChart" width="400" height="200"></canvas>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-3xl font-bold text-green-400" id="totalCommits">0</div>
                        <div class="text-white/70 text-sm">Total Commits</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-3xl font-bold text-blue-400" id="totalLOC">0</div>
                        <div class="text-white/70 text-sm">Lines of Code</div>
                    </div>
                    <div class="bg-white/5 rounded-lg p-4">
                        <div class="text-3xl font-bold text-purple-400" id="projectAge">0</div>
                        <div class="text-white/70 text-sm">Days Active</div>
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
        
        document.querySelectorAll('.floating-card, .glass-effect').forEach((el) => {
            el.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            observer.observe(el);
        });

        // Load and display lines of code graph
        async function loadLOCGraph() {
            try {
                // Embed graph data directly to avoid JSON file access issues
                const data = <?php
                    $graphFile = __DIR__ . '/graph_data.json';
                    if (file_exists($graphFile)) {
                        echo file_get_contents($graphFile);
                    } else {
                        echo '{"metadata":{"total_commits":0,"total_loc":0},"data":[]}';
                    }
                ?>;
                
                // Update statistics
                document.getElementById('totalCommits').textContent = data.metadata.total_commits.toLocaleString();
                document.getElementById('totalLOC').textContent = data.metadata.total_loc.toLocaleString();
                
                // Calculate project age (days from first commit to now)
                const firstCommit = new Date(data.data[0].date);
                const now = new Date();
                const daysActive = Math.floor((now - firstCommit) / (1000 * 60 * 60 * 24));
                document.getElementById('projectAge').textContent = daysActive.toLocaleString();
                
                // Prepare chart data
                const chartData = data.data.map(point => ({
                    x: point.x, // Use the timestamp directly
                    y: point.y
                }));
                
                const ctx = document.getElementById('locChart').getContext('2d');
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        datasets: [{
                            label: 'Lines of Code',
                            data: chartData,
                            borderColor: 'rgb(75, 192, 192)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        return new Date(context[0].parsed.x).toLocaleDateString();
                                    },
                                    label: function(context) {
                                        return `Lines of Code: ${context.parsed.y.toLocaleString()}`;
                                    }
                                }
                            },
                            datetime: {
                                displayFormats: {
                                    month: 'MMM yyyy'
                                }
                            }
                        },
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'month'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                },
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.1)'
                                },
                                ticks: {
                                    color: 'rgba(255, 255, 255, 0.7)',
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }
                        }
                    }
                });
                
            } catch (error) {
                console.error('Error loading LOC graph data:', error);
                document.getElementById('locChart').parentElement.innerHTML = 
                    '<div class="text-center text-white/50 p-8">Unable to load development analytics data</div>';
            }
        }
        
        // Load the graph when the page loads
        loadLOCGraph();
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
        
        /* Syntax highlighting for code blocks */
        pre code.hljs {
            background: #1a1a2e;
            border-radius: 0.5rem;
            padding: 1.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</body>
</html>