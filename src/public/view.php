<?php
require_once __DIR__ . '/../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Buscamos los datos de la noticia
$stmt = $pdo->prepare("SELECT * FROM reference_docs WHERE id = ?");
$stmt->execute([$id]);
$doc = $stmt->fetch();

if (!$doc) {
    die("Error: Noticia no encontrada.");
}

// Obtenemos la ruta del PDF. 
// Importante: El valor en la BD debe ser algo como 'uploads/pdf/archivo.pdf'
$pdfPath = $doc['pdf_url']; 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($doc['title']) ?> - Shopify Mastery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 antialiased">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="max-w-5xl mx-auto px-6 py-12">
        <div class="mb-10">
            <a href="index.php" class="text-shopify font-bold text-sm hover:underline mb-4 inline-block">
                <i class="fas fa-chevron-left mr-2"></i> Volver al Hub
            </a>
            <h1 class="text-4xl font-black text-gray-900 mt-2"><?= htmlspecialchars($doc['title']) ?></h1>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100">
            
            <?php if (!empty($pdfPath) && file_exists(__DIR__ . '/' . $pdfPath)): ?>
                
                <div class="p-6 bg-gray-50 border-b flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <p class="text-gray-900 font-bold">Recurso disponible: PDF</p>
                        <p class="text-gray-500 text-xs italic">Puedes leerlo aquí mismo o descargarlo para después.</p>
                    </div>
                    <a href="<?= htmlspecialchars($pdfPath) ?>" 
                       download="<?= htmlspecialchars($doc['title']) ?>.pdf" 
                       class="bg-[#005c42] text-white px-8 py-3 rounded-2xl font-bold hover:opacity-90 transition flex items-center gap-2 shadow-lg shadow-green-900/20">
                        <i class="fas fa-cloud-download-alt"></i> Descargar Documento
                    </a>
                </div>

                <div class="w-full aspect-[3/4] md:aspect-video">
                    <iframe 
                        src="<?= htmlspecialchars($pdfPath) ?>#toolbar=0" 
                        class="w-full h-full border-none">
                    </iframe>
                </div>

            <?php else: ?>
                
                <div class="p-20 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-pdf text-gray-200 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Contenido en preparación</h3>
                    <p class="text-gray-500 mt-2 max-w-sm mx-auto">
                        Estamos vinculando el PDF de <strong><?= htmlspecialchars($doc['title']) ?></strong>. 
                        Vuelve a consultar en unos minutos.
                    </p>
                    <?php if (empty($pdfPath)): ?>
                        <span class="mt-4 inline-block text-[10px] bg-red-50 text-red-400 px-3 py-1 rounded-full font-mono">
                            Admin: La columna 'pdf_url' está vacía para este ID
                        </span>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </div>
    </main>

</body>
</html>