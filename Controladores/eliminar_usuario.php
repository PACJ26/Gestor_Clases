<?php
session_start();
include("../Conexion/conexion.php");

if (!isset($_GET["id"])) {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "ID de usuario no válido."
    ];
    header("Location: ../Vista/usuarios_registrados.php");
    exit();
}

$id = $_GET["id"];

// Verificar si el usuario es profesor en la tabla clases
$sql_verificar_profesor = "SELECT COUNT(*) AS total FROM clases WHERE profesor_id = ?";
$stmt_profesor = $conexion->prepare($sql_verificar_profesor);
$stmt_profesor->bind_param("i", $id);
$stmt_profesor->execute();
$resultado_profesor = $stmt_profesor->get_result()->fetch_assoc();
$stmt_profesor->close();

if ($resultado_profesor["total"] > 0) {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "No se puede eliminar este usuario porque está asignado como profesor en una o más clases."
    ];
    header("Location: ../Vista/usuarios_registrados.php");
    exit();
}

// Verificar si el usuario es estudiante en la tabla inscripciones
$sql_verificar_estudiante = "SELECT COUNT(*) AS total FROM inscripciones WHERE estudiante_id = ?";
$stmt_estudiante = $conexion->prepare($sql_verificar_estudiante);
$stmt_estudiante->bind_param("i", $id);
$stmt_estudiante->execute();
$resultado_estudiante = $stmt_estudiante->get_result()->fetch_assoc();
$stmt_estudiante->close();

if ($resultado_estudiante["total"] > 0) {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "No se puede eliminar este usuario porque está inscrito en una o más clases como estudiante."
    ];
    header("Location: ../Vista/usuarios_registrados.php");
    exit();
}

// Si no es profesor ni estudiante, proceder con la eliminación
$sql = "DELETE FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $_SESSION["mensaje"] = [
        "tipo" => "success",
        "texto" => "Usuario eliminado correctamente."
    ];
} else {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "Error al eliminar el usuario."
    ];
}

$stmt->close();
$conexion->close();

header("Location: ../Vista/usuarios_registrados.php");
exit();
?>