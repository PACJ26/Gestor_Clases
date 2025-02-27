<?php
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];
    $hora_fin = $_POST["hora_fin"];

    $sql = "UPDATE clases SET fecha = ?, hora = ?, hora_fin = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssi", $fecha, $hora, $hora_fin, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }
}
?>
