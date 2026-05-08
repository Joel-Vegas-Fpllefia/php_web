<?php
require_once __DIR__ . '/../config/db.php';
$id = $_GET['id'] ?? 1; // ID de la lección
$lesson = $pdo->prepare("SELECT * FROM lessons WHERE id = ?");
$lesson->execute([$id]);
$data = $lesson->fetch();
?>
<body class="bg-white">
    <div class="max-w-4xl mx-auto p-8">
        <div class="aspect-video bg-black rounded-2xl mb-8 flex items-center justify-center">
            <button class="w-16 h-16 bg-teal-600 rounded-full text-white text-2xl">▶</button>
        </div>

        <div class="flex justify-between items-start mb-8">
            <div>
                <p class="text-xs text-gray-400">Module 2 • Lesson 3</p>
                <h1 class="text-3xl font-bold"><?= $data['title'] ?></h1>
            </div>
            <button onclick="markComplete(<?= $id ?>)" class="bg-[#005c42] text-white px-4 py-2 rounded-lg flex items-center gap-2">
                ✓ Mark as Complete
            </button>
        </div>

        <div class="grid grid-cols-3 gap-8">
            <div class="col-span-2">
                <h3 class="font-bold mb-4">Step-by-Step Instructions</h3>
                <div class="space-y-4">
                    <div class="border p-4 rounded-xl">
                        <p class="font-bold">1. Navigate to Settings</p>
                        <p class="text-gray-500 text-sm">Find the settings gear icon in the bottom-left corner.</p>
                    </div>
                    <div class="prose mt-6">
                        <?= $data['content'] ?>
                    </div>
                </div>
            </div>
            
            <div class="col-span-1">
                <div class="bg-gray-50 p-6 rounded-xl border">
                    <h4 class="text-xs font-bold uppercase mb-4 text-gray-400">Lesson Resources</h4>
                    <ul class="text-sm space-y-3 text-teal-700">
                        <li>📄 Tax Setup Checklist (PDF)</li>
                        <li>🔗 State Nexus Guide</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
    function markComplete(id) {
        // Aquí llamarías a una API en PHP para guardar el progreso
        fetch(`update_progress.php?lesson_id=${id}&status=completed`)
            .then(() => alert('¡Progreso guardado!'));
    }
    </script>
</body>