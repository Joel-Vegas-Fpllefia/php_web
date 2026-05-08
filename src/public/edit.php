<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
requireAdmin();
$id = $_GET['id'] ?? null;
if (!$id) { header("Location: create-tutorial.php"); exit(); }

// 1. Cargar datos actuales
$stmt = $pdo->prepare("SELECT * FROM reference_docs WHERE id = ?");
$stmt->execute([$id]);
$doc = $stmt->fetch();

if (!$doc) { die("Contenido no encontrado."); }

// 2. Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $type = $_POST['type'];
    $thumb = $_POST['thumbnail'];
    $html = $_POST['content_html'] ?? null;
    $video_url = $_POST['video_url'] ?? null;
    
    // Mantenemos la lógica automática de categorías
    $category = ($type === 'article') ? 'dashboard' : 'library';

    $update = $pdo->prepare("UPDATE reference_docs SET title=?, type=?, thumbnail=?, content_html=?, video_url=?, category=? WHERE id=?");
    $update->execute([$title, $type, $thumb, $html, $video_url, $category, $id]);
    
    header("Location: create-tutorial.php?success=Actualizado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar - <?= htmlspecialchars($doc['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f6f6f7; }
        .s-card { background: white; border-radius: 12px; border: 1px solid #e1e3e5; padding: 2rem; }
        .s-input { border: 1px solid #8c9196; border-radius: 8px; padding: 8px 12px; width: 100%; outline: none; }
    </style>
</head>
<body class="p-10">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Editar Contenido</h1>
            <a href="create-tutorial.php" class="text-gray-500 hover:underline">Cancelar y volver</a>
        </div>

        <div class="s-card">
            <form method="POST" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Título</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($doc['title']) ?>" required class="s-input">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Formato</label>
                        <select name="type" id="typeSelector" class="s-input bg-gray-50">
                            <option value="article" <?= $doc['type'] == 'article' ? 'selected' : '' ?>>📝 Artículo</option>
                            <option value="video" <?= $doc['type'] == 'video' ? 'selected' : '' ?>>🎥 Video</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-medium">Miniatura (URL)</label>
                        <input type="text" name="thumbnail" value="<?= htmlspecialchars($doc['thumbnail']) ?>" class="s-input">
                    </div>

                    <div id="videoField" class="<?= $doc['type'] == 'video' ? '' : 'hidden' ?> md:col-span-2 space-y-2">
                        <label class="text-sm font-medium text-red-600">URL del Video</label>
                        <input type="url" name="video_url" value="<?= htmlspecialchars($doc['video_url']) ?>" class="s-input">
                    </div>

                    <div id="articleField" class="<?= $doc['type'] == 'article' ? '' : 'hidden' ?> md:col-span-2 space-y-2">
                        <label class="text-sm font-medium text-green-700">Contenido HTML</label>
                        <textarea name="content_html" rows="10" class="s-input font-mono text-sm"><?= htmlspecialchars($doc['content_html']) ?></textarea>
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#008060] text-white py-3 rounded-lg font-bold hover:bg-[#006e52]">
                    Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <script>
        const selector = document.getElementById('typeSelector');
        const vField = document.getElementById('videoField');
        const aField = document.getElementById('articleField');
        selector.addEventListener('change', () => {
            if (selector.value === 'video') { vField.classList.remove('hidden'); aField.classList.add('hidden'); }
            else { vField.classList.add('hidden'); aField.classList.remove('hidden'); }
        });
    </script>
</body>
</html>