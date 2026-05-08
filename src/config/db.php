<?php
// src/config/db.php
$host = 'db'; // Nombre del servicio en el docker-compose
$port = '5432';
$dbname = 'mydatabase';
$user = 'myuser';
$password = 'mypassword';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}