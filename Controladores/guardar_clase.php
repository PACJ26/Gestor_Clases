<?php
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $asignatura_id = $_POST['asignatura'];
    $profesor_id = $_POST['profesor'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $hora_fin = $_POST['hora_fin'];

    $sql = "INSERT INTO clases (asignatura_id, profesor_id, fecha, hora, hora_fin) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iisss", $asignatura_id, $profesor_id, $fecha, $hora, $hora_fin);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>
