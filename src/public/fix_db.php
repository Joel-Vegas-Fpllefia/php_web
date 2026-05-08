<?php
require_once __DIR__ . '/../config/db.php';

echo "<h2>Martillo de Reparación de DB</h2>";

try {
    // 1. Ver en qué base de datos estamos realmente
    $db_name = $pdo->query("SELECT current_database()")->fetchColumn();
    echo "Conectado a la base de datos: <b>$db_name</b><br>";

    // 2. Crear la tabla a la fuerza en la base de datos donde esté conectado PHP
    $sql = "CREATE TABLE IF NOT EXISTS public.tutorials (
        id SERIAL PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        type VARCHAR(50),
        location VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );";

    $pdo->exec($sql);
    echo "✅ Tabla 'public.tutorials' asegurada (creada o ya existía).<br>";

    // 3. Verificar si ahora la vemos
    $query = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public'");
    $tablas = $query->fetchAll(PDO::FETCH_COLUMN);
    echo "Tablas actuales en la DB <b>$db_name</b>: " . implode(", ", $tablas);

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}