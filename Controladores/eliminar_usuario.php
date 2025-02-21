<?php
session_start();
include("../Conexion/conexion.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = [
            'tipo' => 'success',
            'texto' => 'Usuario eliminado correctamente.'
        ];
    } else {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'Error al eliminar el usuario.'
        ];
    }
    $stmt->close();
    $conexion->close();

    header("Location: ../Vista/listar_usuarios.php");
    exit();
} else {
    header("Location: ../Vista/listar_usuarios.php");
    exit();
}
?>
