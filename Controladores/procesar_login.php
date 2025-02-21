<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST["correo"]);
    $contraseña = trim($_POST["contraseña"]);

    $sql = "SELECT id, nombres, apellidos, contraseña, rol_id FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows == 1) {
        $usuario = $resultado->fetch_assoc();

        if (password_verify($contraseña, $usuario["contraseña"])) {
            $_SESSION["usuario"] = [
                "id" => $usuario["id"],
                "nombre" => $usuario["nombres"],
                "apellido" => $usuario["apellidos"],
                "rol" => $usuario["rol_id"] // Se mantiene el ID del rol
            ];

            $_SESSION['mensaje'] = [
                'tipo' => 'success',
                'texto' => 'Inicio de sesión exitoso. Bienvenido ' . $usuario["nombres"] . ' ' . $usuario["apellidos"] . '!'
            ];

            // Redirigir según el rol
            switch ($usuario["rol_id"]) {
                case 1:
                    header("Location: ../Vista/panel_administrador.php");
                    break;
                case 2:
                    header("Location: ../Vista/panel_profesor.php");
                    break;
                case 3:
                    header("Location: ../Vista/panel_estudiante.php");
                    break;
                default:
                    header("Location: ../Vista/index.php");
            }
            exit();
        } else {
            $_SESSION['mensaje'] = [
                'tipo' => 'error',
                'texto' => 'Contraseña incorrecta.'
            ];
        }
    } else {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'Usuario no encontrado.'
        ];
    }

    header("Location: ../Vista/index.php");
    exit();
}
?>
