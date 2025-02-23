<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);

    // asignatura ya existe
    $verificar = $conexion->prepare("SELECT id FROM asignaturas WHERE nombre = ?");
    $verificar->bind_param("s", $nombre);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'La asignatura ya está registrada.'
        ];
    } else {
        // Insertar asignatura
        $sql = "INSERT INTO asignaturas (nombre) VALUES (?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => 'Asignatura registrada exitosamente.'
            ];
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Error al registrar la asignatura.'
            ];
        }
        $stmt->close();
    }
    $verificar->close();
    $conexion->close();

    // Redirigir a la vista de asignaturas
    header("Location: ../Vista/registrar_asignaturas.php");
    exit();
} else {
    header("Location: ../Vista/registrar_asignaturas.php");
    exit();
}
