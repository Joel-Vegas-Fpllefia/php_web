<?php
// process-pdf.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';

// Asegurar que solo administradores accedan
requireAdmin();

// 1. Verificar que la petición sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: upload-pdf.php");
    exit();
}

// 2. Validar que el archivo llegó correctamente
if (!isset($_FILES['pdf_document']) || $_FILES['pdf_document']['error'] !== UPLOAD_ERR_OK) {
    die("Error: No se recibió el archivo o hubo un problema en la subida.");
}

$file = $_FILES['pdf_document'];
$tmpPath = $file['tmp_name'];

// 3. Extraer texto usando pdftotext (Poppler-utils en Docker)
// El "2>&1" nos permite capturar errores si la herramienta falla
$command = "pdftotext -layout " . escapeshellarg($tmpPath) . " - 2>&1";
$text = shell_exec($command);

if (empty($text)) {
    die("Error: No se pudo extraer texto. Asegúrate de que el PDF no sea una imagen y que poppler-utils esté instalado.");
}

// 4. Limpieza básica del texto para la base de datos
$text = preg_replace('/\s+/', ' ', $text);
$text = trim($text);
try {
    $text = preg_replace('/\s+/', ' ', $text);
    
    // 1. Preparamos el INSERT
    $stmt = $pdo->prepare("INSERT INTO tutorials (title, content, type, location) VALUES (?, ?, ?, ?)");
    
    // 2. VALORES CLAVE:
    // Aquí es donde suele estar el fallo. Cámbialos según lo que use tu Home:
    $titulo = "NUEVO: " . htmlspecialchars($file['name']);
    $tipo = "noticia";      // <--- PRUEBA CAMBIANDO ESTO (ej: 'post', 'noticia', 'tutorial')
    $ubicacion = "home";    // <--- PRUEBA CAMBIANDO ESTO (ej: 'index', 'publico', 'home')

    $stmt->execute([
        $titulo,
        $text,
        $tipo,
        $ubicacion
    ]);

    // 3. Redirección
    header("Location: index.php?upload=success");
    exit();

} catch (Exception $e) {
    die("Error SQL: " . $e->getMessage());
}