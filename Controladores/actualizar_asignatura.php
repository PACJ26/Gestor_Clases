<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nombre = trim($_POST["nombre"]);

    $sql = "UPDATE asignaturas SET nombre = ? WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nombre, $id);

    if ($stmt->execute()) {
        $_SESSION["mensaje"] = [
            "tipo" => "success",
            "texto" => "Asignatura actualizada correctamente."
        ];
    } else {
        $_SESSION["mensaje"] = [
            "tipo" => "error",
            "texto" => "Error al actualizar la asignatura."
        ];
    }

    header("Location: ../Vista/registrar_asignaturas.php");
    exit();
}
?>
