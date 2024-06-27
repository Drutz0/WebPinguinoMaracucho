<?php
ob_start();
session_start();
include('db.php');
require_once 'dompdf/autoload.inc.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
$filas = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos</title>
    <style>
        table, th, td{
            border-collapse: collapse;
            border: 1px black solid;
        }
    </style>
</head>
<body>
    <h1>Reporte de Productos</h1>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $num = 1;
                foreach($filas as $fila) {
                    $imagePath = 'images/' . $fila['image'];
            ?>
            <tr>
                <td><?php echo $num++; ?></td>
                <td><?php echo $fila['name']; ?></td>
                <td><?php echo $fila['description']; ?></td>
                <td><?php echo $fila['price']; ?></td>
                <td><?php echo $fila['stock']; ?></td>
                <td>
                    <?php if (file_exists($imagePath)) { ?>
                        <img src="<?php echo $imagePath; ?>" width="50">
                    <?php } else { ?>
                        Imagen no disponible
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>

<?php
$html = ob_get_clean();

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);  
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("reporte_productos.pdf", array("Attachment" => false));
?>