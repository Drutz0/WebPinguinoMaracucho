<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['search'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $sql_search = "SELECT * FROM products WHERE (name LIKE '%$search%') OR (description LIKE '%$search%')";
    $stmt = $conn->query($sql_search);
    $results = $stmt->fetch_all(MYSQLI_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $image = $_FILES['image']['name'];
        $target = "images/" . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdis", $name, $description, $price, $stock, $image);
            $stmt->execute();
        } else {
            echo "Error al subir la imagen.";
        }
    } elseif (isset($_POST['delete_product'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

$result = $conn->query("SELECT * FROM products");

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestión de Productos</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="dashboard.php">Panel de Control</a></li>
                <li><a href="users.php">Usuarios</a></li>
                <li><a href="products.php">Productos</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Agregar Producto</h2>
        <form action="products.php" method="post" enctype="multipart/form-data">
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
            <label for="description">Descripción:</label>
            <textarea id="description" name="description" required></textarea>
            <label for="price">Precio:</label>
            <input type="number" id="price" name="price" step="0.01" required>
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
            <label for="image">Imagen:</label>
            <input type="file" id="image" name="image" required>
            <button type="submit" name="add_product">Agregar Producto</button>
        </form>

        <h2>Lista de Productos</h2>
        <form action="products.php" method="post">
            <input type="text" name="search" placeholder="Buscar producto">
            <button type="submit" class="btn btn-success">Buscar</button>
            <a href="products.php"><button>Reset</button></a>
        </form>
        <table>
            <tbody>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
                <?php
                $products = isset($results) ? $results : $result->fetch_all(MYSQLI_ASSOC);
                $num = 1;
                foreach($products as $product){
                    $imagePath = 'images/' . $product['image'];
                ?>
                <tr>
                    <td><?php echo $num++; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['description']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <?php if (file_exists($imagePath)) { ?>
                            <img src="<?php echo $imagePath; ?>" width="50">
                        <?php } else { ?>
                            Imagen no disponible
                        <?php } ?>
                    </td>
                    <td>
                        <form action="products.php" method="post" style="display:inline-block;">
                            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete_product">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <form action="generate_pdf.php" method="post" target="_blank">
            <button type="submit">Generar Reporte en PDF</button>
        </form>
    </main>
</body>
</html>