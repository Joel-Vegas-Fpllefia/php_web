<?php 
// Solo lo incluimos para saber si hay una sesión abierta, 
// NO usamos requireAdmin() aquí porque queremos que sea público.
require_once __DIR__ . '/../../config/auth.php';
?>

<header class="sticky top-0 z-50 bg-white/70 backdrop-blur-lg border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
        
        <a href="index.php" class="flex items-center gap-3 group">
            <div class="bg-[#005c42] w-10 h-10 rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:rotate-6 transition-transform">
                <span class="font-black text-xl">S</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-gray-900 leading-none text-lg">Shopify Mastery</span>
                <span class="text-[10px] text-green-600 font-bold uppercase tracking-tighter">Ecosystem Admin</span>
            </div>
        </a>

        <nav class="flex items-center gap-8">
            <a href="index.php" class="text-sm font-medium text-gray-500 hover:text-black transition">Home</a>
            <a href="library.php" class="text-sm font-medium text-gray-500 hover:text-black transition">Library</a>
            
            <div class="w-px h-4 bg-gray-200"></div>

            <?php if (isAdmin()): ?>
                <div class="flex items-center gap-4 bg-gray-50 pl-4 pr-1 py-1 rounded-full border border-gray-100">
                    <span class="text-xs font-bold text-gray-600">Hola, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="create-tutorial.php" class="bg-[#008060] text-white px-4 py-2 rounded-full text-xs font-bold hover:bg-[#005c42] transition shadow-sm">
                        Admin Panel
                    </a>
                    <a href="logout.php" class="pr-2 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"></path></svg>
                    </a>
                </div>
            <?php else: ?>
                <a href="login.php" class="text-sm font-bold text-gray-400 hover:text-[#008060] transition flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-gray-200"></div>
                    Acceso Staff
                </a>
            <?php endif; ?>
        </nav>

    </div>
</header>