<?php
session_start();
include("../Conexion/conexion.php");

function generarColorDesdeHora($hora) {
    $hash = md5($hora);
    $r = hexdec(substr($hash, 0, 2)) % 156 + 100; 
    $g = hexdec(substr($hash, 2, 2)) % 156 + 100;
    $b = hexdec(substr($hash, 4, 2)) % 156 + 100;
    return "rgb($r, $g, $b)";
}

//datos del usuario autenticado
$usuario_id = $_SESSION['usuario']['id']; 
$rol = $_SESSION['usuario']['rol'];

$sql = "SELECT c.id, a.nombre as asignatura, c.fecha, c.hora, c.hora_fin, c.estado, u.nombres as profesor 
        FROM clases c
        INNER JOIN asignaturas a ON c.asignatura_id = a.id 
        INNER JOIN usuarios u ON c.profesor_id = u.id";

//segun rol mostrar clases
if ($rol == 2) { 
    $sql .= " WHERE c.profesor_id = $usuario_id";
} elseif ($rol == 3) { 
    $sql .= " INNER JOIN inscripciones i ON c.id = i.clase_id WHERE i.estudiante_id = $usuario_id";
}

$resultado = $conexion->query($sql);

if (!$resultado) {
    die("Error en la consulta SQL: " . $conexion->error);
}

$clases = [];
while ($fila = $resultado->fetch_assoc()) {
    $horaInicio = date("H:i:s", strtotime($fila["hora"]));
    $horaFin = date("H:i:s", strtotime($fila["hora_fin"]));
    $fecha = date("Y-m-d", strtotime($fila["fecha"]));

    $colorFondo = generarColorDesdeHora($horaInicio);

    $clases[] = [
        "id" => $fila["id"],
        "title" => $fila["asignatura"] . " - " . $fila["profesor"] . " (" . $fila["estado"] . ")",
        "start" => "{$fecha}T{$horaInicio}",
        "end" => "{$fecha}T{$horaFin}",
        "backgroundColor" => $colorFondo,
        "borderColor" => $colorFondo,
        "textColor" => "#000000",
    ];
}

header('Content-Type: application/json');
echo json_encode($clases, JSON_PRETTY_PRINT);
exit();
?>
