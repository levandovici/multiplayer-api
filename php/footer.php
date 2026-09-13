<?php
/**
 * Shared site footer.
 * Included by public pages. Uses root-absolute links so it works
 * both from the site root and from subdirectory pages.
 */
?>
<!-- Footer -->
<footer class="glass-effect border-t border-white/10 mt-16">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-10">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    <img src="/michitai.png" alt="Multiplayer API Logo" class="w-9 h-9 rounded-lg object-contain">
                    <span class="text-white font-bold">Multiplayer API</span>
                </div>
                <p class="text-white/60 text-sm leading-relaxed">
                    Cross-platform multiplayer backend for game developers. Open source under the MIT No Attribution (MIT-0) license.
                </p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Product</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/unity/index.php" class="text-white/60 hover:text-white transition-colors">Unity SDK</a></li>
                    <li><a href="/java/index.php" class="text-white/60 hover:text-white transition-colors">Java SDK</a></li>
                    <li><a href="/dotnet/index.php" class="text-white/60 hover:text-white transition-colors">.NET SDK</a></li>
                    <li><a href="/cpp/index.php" class="text-white/60 hover:text-white transition-colors">C++ SDK</a></li>
                    <li><a href="/api/index.php" class="text-white/60 hover:text-white transition-colors">REST API</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Resources</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/servers/" class="text-white/60 hover:text-white transition-colors">Servers</a></li>
                    <li><a href="/roadmap.php" class="text-white/60 hover:text-white transition-colors">Roadmap</a></li>
                    <li><a href="/faq.php" class="text-white/60 hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="/about.php" class="text-white/60 hover:text-white transition-colors">About</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3 text-sm uppercase tracking-wider">Legal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/terms.php" class="text-white/60 hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="/privacy.php" class="text-white/60 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="/LICENSE" class="text-white/60 hover:text-white transition-colors">License (MIT-0)</a></li>
                </ul>
            </div>
        </div>
        <div class="pt-6 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-3">
            <div class="text-white/60 text-sm">
                &copy; 2026 Nichita Levandovici. All rights reserved.
            </div>
            <div class="text-white/50 text-xs">
                Source code licensed under <a href="/LICENSE" class="text-blue-400 hover:text-blue-300">MIT No Attribution</a>
            </div>
        </div>
    </div>
</footer>
