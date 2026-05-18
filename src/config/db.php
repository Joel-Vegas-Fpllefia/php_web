<?php
// src/config/db.php

// 1. Intentamos leer la URL automática que te da Render
$databaseUrl = getenv('DATABASE_URL');

try {
    if ($databaseUrl) {
        // --- CONFIGURACIÓN PARA RENDER (PRODUCCIÓN) ---
        // Descomponemos la URL larga (postgres://user:pass@host:port/dbname)
        $dbconn = parse_url($databaseUrl);
        
        $host     = $dbconn['host'];
        $port     = $dbconn['port'] ?? 5432;
        $user     = $dbconn['user'];
        $password = $dbconn['pass'];
        $dbname   = ltrim($dbconn['path'], '/');
    } else {
        // --- CONFIGURACIÓN PARA TU PC (LOCAL CON DOCKER) ---
        $host     = 'db'; 
        $port     = '5432';
        $dbname   = 'mydatabase';
        $user     = 'myuser';
        $password = 'mypassword';
    }

    // 2. Conexión final usando las variables calculadas
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}