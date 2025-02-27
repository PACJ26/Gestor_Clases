<?php
include("../Conexion/conexion.php");

$sql = "SELECT c.id, a.nombre as asignatura, c.fecha, c.hora, c.hora_fin, u.nombres as profesor 
        FROM clases c
        INNER JOIN asignaturas a ON c.asignatura_id = a.id 
        INNER JOIN usuarios u ON c.profesor_id = u.id";

$resultado = $conexion->query($sql);

$clases = [];
while ($fila = $resultado->fetch_assoc()) {
    $clases[] = [
        "id" =>  $fila["id"],
        "title" => $fila["asignatura"] . " - " . $fila["profesor"],
        "start" => $fila["fecha"] . "T" . $fila["hora"],
        "end" => $fila["fecha"] . "T" . $fila["hora_fin"], 
    ];
}

header('Content-Type: application/json');
echo json_encode($clases, JSON_PRETTY_PRINT); 
exit();

?>
