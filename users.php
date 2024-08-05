<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$search = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $password, $email, $role);
    $stmt->execute();
    header("Location: users.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_user'])) {
    $search = $_POST['search'];
    $sql = "SELECT * FROM users WHERE username LIKE ? OR email LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchParam = "%" . $search . "%";
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $result = $conn->query("SELECT * FROM users");
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gestión de Usuarios</h1>
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="dashboard.php">Panel de Control</a></li>
                <li><a href="users.php">Usuarios</a></li>
                <li><a href="products.php">Productos</a></li>
                <li><a href="logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Usuarios del Sistema</h2>
        <form action="users.php" method="post">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email">
            <label for="role">Rol:</label>
            <select id="role" name="role">
                <option value="user">Usuario</option>
                <option value="admin">Administrador</option>
            </select>
            <button type="submit" name="add_user">Agregar Usuario</button>
        </form>
        <form method="POST" action="users.php">
            <input type="text" name="search" placeholder="Buscar usuario" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" name="search_user">Buscar</button>
            <a href="users.php"><button>Reset</button></a>
        </form>
        <table>
            <tr>
                <th>Usuario</th>
                <th>Correo Electrónico</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>">Editar</a>
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>
