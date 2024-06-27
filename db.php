<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "picdb_system";

//Crear la conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

//Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
