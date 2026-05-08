<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

try {
    // Obtenemos todos los documentos marcados para la librería
    $query = "SELECT * FROM reference_docs WHERE category = 'library' ORDER BY id DESC";
    $stmt = $pdo->query($query);
    $docs = $stmt->fetchAll();
} catch (PDOException $e) {
    $docs = [];
    $error = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Shopify Mastery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f9fafb; }
        .text-shopify { color: #005c42; }
        .bg-shopify { background-color: #005c42; }
    </style>
</head>
<body class="antialiased">

<?php include __DIR__ . '/includes/header.php'; ?>
    <main class="max-w-6xl mx-auto px-6 py-16">
        
        <div class="text-center mb-20">
            <h1 class="text-5xl font-extrabold text-gray-900 tracking-tight">Documentation <span class="text-shopify">Library</span></h1>
            <p class="text-gray-500 mt-4 text-lg max-w-2xl mx-auto italic">
                Recursos técnicos, snippets de código y guías visuales para llevar tu tienda al siguiente nivel.
            </p>
        </div>

        <?php if (empty($docs)): ?>
            <div class="text-center py-32 bg-white border-2 border-dashed border-gray-200 rounded-[3rem]">
                <div class="text-6xl mb-4">📚</div>
                <p class="text-gray-400 text-xl font-medium">Aún no hay recursos en la biblioteca.</p>
                <p class="text-gray-300 text-sm mt-2">Usa el panel de administrador para subir contenido con la categoría "library".</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                <?php foreach ($docs as $doc): ?>
                <article class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 group flex flex-col">
    
    <a href="<?= htmlspecialchars($doc['video_url']) ?>" target="_blank" class="relative h-56 overflow-hidden bg-gray-200 block">
        <img src="<?= htmlspecialchars($doc['thumbnail'] ?: 'https://via.placeholder.com/500') ?>" 
             class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition flex items-center justify-center">
            <div class="bg-white text-shopify p-4 rounded-full shadow-2xl opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </div>
        </div>
    </a>

    <div class="p-8 flex-1 flex flex-col">
        <h3 class="text-2xl font-bold text-gray-900 mb-3 leading-tight">
            <?= htmlspecialchars($doc['title']) ?>
        </h3>
        
        <p class="text-gray-500 text-sm line-clamp-2 mb-8 flex-1">
            Recurso externo: Haz clic para abrir el enlace oficial de este tutorial o herramienta.
        </p>
        
        <div class="pt-6 border-t border-gray-50">
            <a href="<?= htmlspecialchars($doc['video_url']) ?>" target="_blank" class="flex justify-between items-center text-shopify font-bold text-sm group/btn">
                <span>Abrir Recurso Externo</span>
                <span class="transform group-hover/btn:translate-x-2 transition">↗</span>
            </a>
        </div>
    </div>
</article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer class="py-16 bg-white border-t border-gray-100 mt-24">
        <div class="text-center">
            <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.4em]">Shopify Mastery Reference Library</p>
        </div>
    </footer>

</body>
</html>