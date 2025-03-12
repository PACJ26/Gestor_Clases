<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clase_id = $_POST["clase_id"];

    // Eliminar todas las inscripciones de la clase seleccionada
    $sql_delete = "DELETE FROM inscripciones WHERE clase_id = ?";
    $stmt = $conexion->prepare($sql_delete);
    $stmt->bind_param("i", $clase_id);
    if ($stmt->execute()) {
        $_SESSION['mensaje'] = [
            'tipo' => 'success',
            'texto' => 'Se han eliminado todos los estudiantes de la clase.'
        ];
    } else {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'No se pudo eliminar a los estudiantes.'
        ];
    }

    session_write_close();
    header("Location: ../Vista/asignar_estudiantes.php");
    exit();
}
?>
