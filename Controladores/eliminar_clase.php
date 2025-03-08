<?php
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    // Verificar si la clase tiene estudiantes asignados
    $sql_verificar = "SELECT COUNT(*) as total FROM inscripciones WHERE clase_id = ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $id);
    $stmt_verificar->execute();
    $resultado = $stmt_verificar->get_result()->fetch_assoc();

    if ($resultado['total'] > 0) {
        echo json_encode(["status" => "error", "message" => "No se puede eliminar la clase porque tiene " . $resultado['total'] . " estudiantes inscritos."]);
        exit();
    }

    // Si no hay estudiantes inscritos, eliminar la clase
    $sql = "DELETE FROM clases WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al eliminar la clase."]);
    }
}
?>
