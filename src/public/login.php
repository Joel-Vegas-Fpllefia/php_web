<?php
require_once __DIR__ . '/../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: create-tutorial.php");
        exit();
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - Shopify Mastery</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f6f6f7] flex items-center justify-center h-screen">
    <form method="POST" class="bg-white p-10 rounded-[2rem] shadow-xl w-full max-w-md border border-gray-100">
        <div class="text-center mb-8">
            <div class="bg-[#005c42] w-12 h-12 rounded-2xl mx-auto mb-4 flex items-center justify-center text-white font-bold text-xl">S</div>
            <h1 class="text-2xl font-bold">Panel Administrativo</h1>
        </div>
        
        <?php if(isset($error)): ?>
            <p class="text-red-500 text-sm mb-4 text-center"><?= $error ?></p>
        <?php endif; ?>

        <div class="space-y-4">
            <input type="text" name="username" placeholder="Usuario" class="w-full p-4 bg-gray-50 border rounded-2xl outline-none focus:border-[#008060]">
            <input type="password" name="password" placeholder="Contraseña" class="w-full p-4 bg-gray-50 border rounded-2xl outline-none focus:border-[#008060]">
            <button class="w-full bg-[#005c42] text-white py-4 rounded-2xl font-bold hover:opacity-90 transition">Entrar</button>
        </div>
    </form>
</body>
</html>