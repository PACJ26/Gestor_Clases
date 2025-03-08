<?php
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    // Cambiar el estado de la clase a "Cancelada"
    $sql_cancelar = "UPDATE clases SET estado = 'Cancelada' WHERE id = ?";
    $stmt_cancelar = $conexion->prepare($sql_cancelar);
    $stmt_cancelar->bind_param("i", $id);

    if ($stmt_cancelar->execute()) {
        echo json_encode(["status" => "success", "message" => "Clase cancelada correctamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al cancelar la clase."]);
    }
}
?>
