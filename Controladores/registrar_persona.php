<?php
session_start(); // Iniciar sesión para almacenar mensajes
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = trim($_POST["nombres"]);
    $apellidos = trim($_POST["apellidos"]);
    $tipo_documento = $_POST["tipo_documento"];
    $documento = trim($_POST["documento"]);
    $telefono = trim($_POST["telefono"]);
    $correo = trim($_POST["correo"]);
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_BCRYPT); 
    $rol = $_POST["rol"];

    // Verificar si el documento o correo ya existe
    $verificar = $conexion->prepare("SELECT id FROM usuarios WHERE documento = ? OR correo = ?");
    $verificar->bind_param("ss", $documento, $correo);
    $verificar->execute();
    $verificar->store_result();

    if ($verificar->num_rows > 0) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'El documento o correo ya están registrados.'
        ];
    } else {
        // Insertar usuario
        $sql = "INSERT INTO usuarios (nombres, apellidos, tipo_documento_id, documento, telefono, correo, contraseña, rol_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssisissi", $nombres, $apellidos, $tipo_documento, $documento, $telefono, $correo, $contraseña, $rol);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => 'Usuario registrado exitosamente.'
            ];
            header("Location: ../Vista/panel_administrador.php");
            exit();
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Error al registrar el usuario.'
            ];
        }
        $stmt->close();
    }
    $verificar->close();
    $conexion->close();
    
    header("Location: ../Vista/registro_usuario.php");
    exit();
} else {
    header("Location: ../Vista/registro_usuario.php");
    exit();
}
?>
