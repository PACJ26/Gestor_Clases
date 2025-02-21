<?php

session_start();

include("../Conexion/conexion.php");

$sql = "SELECT u.id, u.nombres, u.apellidos, t.tipo AS tipo_documento, u.documento, u.telefono, u.correo, r.nombre AS rol 
        FROM usuarios u
        JOIN tipos_documento t ON u.tipo_documento_id = t.id
        JOIN roles r ON u.rol_id = r.id";
$resultado = $conexion->query($sql);

?>