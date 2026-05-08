<?php
require_once __DIR__ . '/../config/db.php';

// Validar que exista el ID en la URL
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM reference_docs WHERE id = ?");
    $stmt->execute([$id]);
    $doc = $stmt->fetch();

    if (!$doc) {
        die("El documento no existe.");
    }
} catch (PDOException $e) {
    die("Error al cargar el documento: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($doc['title']) ?> - Shopify Mastery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; }
        .text-shopify { color: #005c42; }
    </style>
</head>
<body class="antialiased">

    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-8 py-4 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="index.php" class="flex items-center gap-2 text-gray-500 hover:text-black transition font-medium">
                <span>←</span> Volver al Home
            </a>
            <div class="flex items-center gap-2">
                <div class="bg-[#005c42] w-6 h-6 rounded flex items-center justify-center text-white text-[10px] font-bold">S</div>
                <span class="text-sm font-bold tracking-tight">Shopify Mastery</span>
            </div>
        </div>
    </nav>

    <main class="max-w-3xl mx-auto px-6 py-16">
        
        <header class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                <span class="bg-green-50 text-shopify text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full border border-green-100">
                    <?= strtoupper($doc['category']) ?>
                </span>
                <span class="text-gray-300 text-xs">•</span>
                <span class="text-gray-400 text-xs"><?= date('d M, Y', strtotime($doc['created_at'])) ?></span>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                <?= htmlspecialchars($doc['title']) ?>
            </h1>

            <?php if ($doc['thumbnail']): ?>
            <div class="rounded-[2rem] overflow-hidden shadow-2xl shadow-gray-200 mb-12 aspect-video">
                <img src="<?= htmlspecialchars($doc['thumbnail']) ?>" class="w-full h-full object-cover">
            </div>
            <?php endif; ?>
        </header>

        <article class="prose prose-slate prose-lg max-w-none prose-headings:font-bold prose-a:text-green-700 prose-pre:bg-gray-900 prose-pre:rounded-2xl prose-img:rounded-3xl">
            <?= $doc['content_html'] ?>
        </article>

        <footer class="mt-20 pt-8 border-t border-gray-100 flex flex-col items-center gap-6">
            <p class="text-gray-400 text-sm">¿Te ha sido útil esta documentación de referencia?</p>
            <div class="flex gap-4">
                <a href="library.php" class="bg-gray-50 text-gray-600 px-6 py-3 rounded-full font-bold text-sm hover:bg-gray-100 transition">Explorar Librería</a>
                <button onclick="window.print()" class="bg-[#005c42] text-white px-6 py-3 rounded-full font-bold text-sm hover:opacity-90 transition">Guardar PDF</button>
            </div>
        </footer>

    </main>

</body>
</html>