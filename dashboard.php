<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Panel de Control</h1>
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="dashboard.php">Panel de Control</a></li>
                <?php if ($role == 'admin'): ?>
                <li><a href="users.php">Usuarios</a></li>
                <li><a href="products.php">Productos</a></li>
                <?php endif; ?>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Bienvenido, <?php echo htmlspecialchars($username); ?></h2>
        <p>Esta es tu área de administración.</p>
    </main>
</body>
</html>
