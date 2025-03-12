<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inscripcion_id = $_POST["inscripcion_id"];

    // Eliminar la inscripción de un estudiante en una clase específica
    $sql_delete = "DELETE FROM inscripciones WHERE id = ?";
    $stmt = $conexion->prepare($sql_delete);
    $stmt->bind_param("i", $inscripcion_id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = [
            'tipo' => 'success',
            'texto' => 'El estudiante ha sido eliminado de la clase.'
        ];
    } else {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'No se pudo eliminar al estudiante.'
        ];
    }

    session_write_close();
    header("Location: ../Vista/asignar_estudiantes.php");
    exit();
}
?>
