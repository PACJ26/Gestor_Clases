<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nombres = trim($_POST["nombres"]);
    $apellidos = trim($_POST["apellidos"]);
    $correo = trim($_POST["correo"]);
    $telefono = trim($_POST["telefono"]);
    $tipo_documento_id = $_POST["tipo_documento_id"];
    $rol_id = $_POST["rol_id"];

    // Verificar que el correo no esté duplicado en otro usuario
    $sql_verificar = "SELECT id FROM usuarios WHERE correo = ? AND id != ?";
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("si", $correo, $id);
    $stmt_verificar->execute();
    $stmt_verificar->store_result();

    if ($stmt_verificar->num_rows > 0) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'El correo ya está registrado por otro usuario.'
        ];
    } else {
        $sql = "UPDATE usuarios SET nombres = ?, apellidos = ?, correo = ?, telefono = ?, tipo_documento_id = ?, rol_id = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssiii", $nombres, $apellidos, $correo, $telefono, $tipo_documento_id, $rol_id, $id);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => 'Usuario actualizado correctamente.'
            ];
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Error al actualizar el usuario.'
            ];
        }
        $stmt->close();
    }

    $stmt_verificar->close();
    $conexion->close();

    // Redirigir de nuevo a la lista de usuarios
    header("Location: ../Vista/usuarios_registrados.php");
    exit();
} else {
    header("Location: ../Vista/usuarios_registrados.php");
    exit();
}
