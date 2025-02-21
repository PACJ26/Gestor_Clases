<?php
    include("../Conexion/conexion.php");

function obtenerTiposDocumento() {
    global $conexion;
    $consulta = "SELECT id, tipo FROM tipos_documento";
    return $conexion->query($consulta);
}


function obtenerRoles() {
    global $conexion;
    $consulta = "SELECT id, nombre FROM roles";
    return $conexion->query($consulta);
}
?>
