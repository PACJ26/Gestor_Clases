<?php
session_start();
include("../Conexion/conexion.php");

if (isset($_GET["id"])) {
    $id = $_GET["id"];

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
            "texto" => "No se puede eliminar esta asignatura porque está en uso."
        ];
    }

    header("Location: ../Vista/registrar_asignaturas.php");
    exit();
}
?>
