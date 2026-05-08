<?php
// 1. Configuración de autenticación y base de datos
require_once __DIR__ . '/../config/auth.php'; // Asegura que solo usuarios logueados entren
require_once __DIR__ . '/../config/db.php';   // Archivo que contiene la conexión $pdo

try {
    // 2. Obtenemos los documentos. Usamos LIMIT para no sobrecargar si hay muchos.
    $query = "SELECT * FROM reference_docs WHERE category = 'dashboard' ORDER BY id DESC LIMIT 13";
    $stmt = $pdo->query($query);
    $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 3. Lógica para separar el destacado (el primero) del resto
    // array_shift quita el primer elemento de $docs y lo guarda en $featuredDoc
    $featuredDoc = !empty($docs) ? array_shift($docs) : null;

} catch (PDOException $e) {
    $docs = [];
    $featuredDoc = null;
    $error = "Error de base de datos: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopify Mastery - Reference Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #fcfcfc; }
        .text-shopify { color: #005c42; }
        .bg-shopify { background-color: #005c42; }
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;  
            overflow: hidden;
        }
    </style>
</head>
<body class="antialiased text-gray-900">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="max-w-6xl mx-auto px-6 py-12">
        
        <header class="mb-12">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Reference Documentation</h1>
            <p class="text-lg text-gray-500 mt-2">Guías técnicas y recursos de apoyo para el desarrollo en Shopify.</p>
        </header>

        <?php if (isset($error)): ?>
            <div class="bg-red-50 p-4 rounded-xl text-red-700 mb-10 border border-red-100 flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($featuredDoc): ?>
        <section class="mb-16">
            <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col md:flex-row items-center gap-10 p-8 md:p-12 hover:shadow-md transition duration-300">
                <div class="w-full md:w-1/2 aspect-video rounded-3xl overflow-hidden bg-gray-100 shadow-inner">
                    <?php 
                        $thumb = !empty($featuredDoc['thumbnail']) ? $featuredDoc['thumbnail'] : 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800';
                    ?>
                    <img src="<?= htmlspecialchars($thumb) ?>" 
                         alt="<?= htmlspecialchars($featuredDoc['title']) ?>"
                         class="w-full h-full object-cover">
                </div>
                <div class="w-full md:w-1/2">
                    <span class="text-xs font-bold text-shopify uppercase tracking-widest mb-3 block">New Resource</span>
                    <h2 class="text-3xl font-bold mb-4 leading-tight text-gray-900"><?= htmlspecialchars($featuredDoc['title']) ?></h2>
                    <p class="text-gray-500 mb-8 line-clamp-3">
                        <?= htmlspecialchars($featuredDoc['description'] ?? 'Documento de consulta técnica. Accede al contenido completo para ver especificaciones y ejemplos de código.') ?>
                    </p>
                    <a href="view.php?id=<?= (int)$featuredDoc['id'] ?>" class="bg-shopify text-white px-8 py-4 rounded-2xl font-bold inline-flex items-center gap-2 hover:opacity-90 transition shadow-lg shadow-green-900/10">
                        Open Document <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <section>
            <h3 class="text-2xl font-bold mb-8 flex items-center gap-3">
                <i class="fas fa-bolt text-yellow-500 text-xl"></i> Popular References
            </h3>
            
            <?php if (empty($docs) && !$featuredDoc): ?>
                <div class="text-center py-20 border-2 border-dashed border-gray-100 rounded-[2.5rem]">
                    <i class="fas fa-folder-open text-gray-200 text-5xl mb-4"></i>
                    <p class="text-gray-400 font-medium italic">No hay documentos publicados en el Dashboard todavía.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php foreach ($docs as $doc): ?>
                    <article class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <div class="h-48 overflow-hidden bg-gray-200">
                            <?php $thumbCard = !empty($doc['thumbnail']) ? $doc['thumbnail'] : 'https://via.placeholder.com/400x250?text=Shopify+Mastery'; ?>
                            <img src="<?= htmlspecialchars($thumbCard) ?>" 
                                 alt="<?= htmlspecialchars($doc['title']) ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        </div>
                        <div class="p-6">
                            <h4 class="font-bold text-lg mb-4 h-14 line-clamp-2 leading-tight text-gray-800">
                                <?= htmlspecialchars($doc['title']) ?>
                            </h4>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reference Guide</span>
                                <a href="view.php?id=<?= (int)$doc['id'] ?>" class="text-shopify font-bold text-sm hover:underline flex items-center gap-1">
                                    Read Now <i class="fas fa-chevron-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <footer class="py-12 border-t border-gray-100 mt-20 text-center">
        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-[0.3em]">&copy; <?= date('Y') ?> Shopify Mastery Hub</p>
    </footer>

</body>
</html>