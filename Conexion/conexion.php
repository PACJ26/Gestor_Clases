<?php

$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "gestor_clases";

$conexion = new mysqli($servername, $username, $password, $dbname);
$conexion->set_charset("utf8");

if ($conexion->connect_error) {

    die("Error de conexion" . $conexion->connect_error);
}

?>
