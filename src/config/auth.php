<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdmin() {
    // Primero verificamos que 'username' esté definido en la sesión
    if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') {
        return true;
    }
    return false;
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: login.php");
        exit();
    }
}