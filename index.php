<?php
session_start();
include('db.php');

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>El Pingüino Maracucho</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Bienvenido a Heladería: El Pingüino Maracucho</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="register.php">Registrarse</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div>
            <center>
            <h2>Productos Disponibles</h2>
            <div class="product-list">
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" class="product-image">
                    <h3><?php echo $row['name']; ?></h3>
                    <p><?php echo $row['description']; ?></p>
                    <p>Precio: $<?php echo $row['price']; ?></p>
                    <p>Disponible: <?php echo $row['stock']; ?> unidades</p>
                </div>
                <?php endwhile; ?>
            </div>
            </center>
        </div>
    </main>
</body>
</html>
