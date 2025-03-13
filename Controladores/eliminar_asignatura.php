<?php
session_start();
include("../Conexion/conexion.php");

if (!isset($_GET["id"])) {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "ID de asignatura no válido."
    ];
    header("Location: ../Vista/registrar_asignaturas.php");
    exit();
}

$id = $_GET["id"];

// Verificar si la asignatura está en uso en la tabla clases
$sql_verificar = "SELECT COUNT(*) AS total FROM clases WHERE asignatura_id = ?";
$stmt_verificar = $conexion->prepare($sql_verificar);
$stmt_verificar->bind_param("i", $id);
$stmt_verificar->execute();
$resultado_verificar = $stmt_verificar->get_result()->fetch_assoc();
$stmt_verificar->close();

if ($resultado_verificar["total"] > 0) {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "No se puede eliminar esta asignatura porque está asociada a una o más clases."
    ];
    header("Location: ../Vista/registrar_asignaturas.php");
    exit();
}

// si no esta no hay clases se alimina
$sql = "DELETE FROM asignaturas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION["mensaje"] = [
        "tipo" => "success",
        "texto" => "Asignatura eliminada correctamente."
    ];
} else {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "Error al eliminar la asignatura."
    ];
}

$stmt->close();
$conexion->close();

header("Location: ../Vista/registrar_asignaturas.php");
exit();
?>
