<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
requireAdmin();
// 1. Lógica de Borrado
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM reference_docs WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: create-tutorial.php?msg=deleted");
    exit();
}

// 2. Lógica de Creación (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type']; 
    $html = $_POST['content_html'] ?? null;
    $video_url = $_POST['video_url'] ?? null;
    $thumb = $_POST['thumbnail'];

    // Los artículos van al Dashboard, los videos a la Library automáticamente
    $category = ($type === 'article') ? 'dashboard' : 'library';

    $stmt = $pdo->prepare("INSERT INTO reference_docs (title, category, type, content_html, video_url, thumbnail) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $category, $type, $html, $video_url, $thumb]);
    $success = "¡Publicado con éxito!";
}

// 3. Obtener listado para la tabla
$docs = $pdo->query("SELECT * FROM reference_docs ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor de Documentación - Shopify Mastery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f6f6f7; color: #202223; }
        .s-card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e1e3e5; }
        .s-input { border: 1px solid #8c9196; border-radius: 8px; padding: 10px; width: 100%; outline: none; }
        .s-input:focus { border-color: #008060; box-shadow: 0 0 0 2px rgba(0,128,96,0.1); }
    </style>
</head>
<body class="antialiased">

    <?php include __DIR__ . '/includes/header.php'; ?>

    <main class="max-w-5xl mx-auto px-6 py-10">
        
        <div class="mb-10">
            <h1 class="text-2xl font-bold mb-6">Crear Nuevo Recurso</h1>
            <div class="s-card p-8">
                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase text-gray-400 italic">Título</label>
                            <input type="text" name="title" required class="s-input" placeholder="Ej: Guía de Checkout">
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase text-gray-400 italic">Tipo de Contenido</label>
                            <select name="type" id="typeSelector" class="s-input bg-gray-50 font-semibold text-green-800">
                                <option value="article">📝 Artículo (Va a Home)</option>
                                <option value="video">🎥 Video (Va a Library)</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 space-y-1">
                            <label class="text-xs font-bold uppercase text-gray-400 italic">URL Imagen Miniatura</label>
                            <input type="text" name="thumbnail" class="s-input" placeholder="https://images.unsplash.com/...">
                        </div>
                        <div id="videoField" class="hidden md:col-span-2 space-y-1">
                            <label class="text-xs font-bold uppercase text-gray-400 italic text-red-500">URL del Video</label>
                            <input type="url" name="video_url" class="s-input border-red-100" placeholder="https://youtube.com/...">
                        </div>
                        <div id="articleField" class="md:col-span-2 space-y-1">
                            <label class="text-xs font-bold uppercase text-gray-400 italic text-green-700">Contenido HTML</label>
                            <textarea name="content_html" rows="6" class="s-input font-mono text-sm" placeholder="<p>Escribe tu contenido aquí...</p>"></textarea>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-gray-50 flex justify-end">
                        <button type="submit" class="bg-[#008060] text-white px-8 py-3 rounded-xl font-bold hover:bg-[#006e52] transition shadow-md">Publicar Recurso</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="s-card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-700 uppercase text-xs tracking-widest">Contenidos Existentes</h2>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[11px] uppercase text-gray-400 font-bold border-b border-gray-100">
                        <th class="px-6 py-4">Título</th>
                        <th class="px-6 py-4">Ubicación</th>
                        <th class="px-6 py-4">Formato</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php foreach($docs as $d): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-sm text-gray-800"><?= htmlspecialchars($d['title']) ?></td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold px-2 py-1 rounded bg-gray-100 text-gray-500 uppercase">
                                <?= $d['category'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold px-2 py-1 rounded <?= $d['type'] == 'video' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-700' ?> uppercase">
                                <?= $d['type'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right flex justify-end gap-4">
                            <a href="edit.php?id=<?= $d['id'] ?>" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase tracking-tighter">Editar</a>
                            <span class="text-gray-200">|</span>
                            <a href="?delete=<?= $d['id'] ?>" onclick="return confirm('¿Seguro que quieres borrarlo?')" class="text-red-400 hover:text-red-600 font-bold text-xs uppercase tracking-tighter">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        const selector = document.getElementById('typeSelector');
        const vField = document.getElementById('videoField');
        const aField = document.getElementById('articleField');

        selector.addEventListener('change', () => {
            if (selector.value === 'video') {
                vField.classList.remove('hidden');
                aField.classList.add('hidden');
            } else {
                vField.classList.add('hidden');
                articleField.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>