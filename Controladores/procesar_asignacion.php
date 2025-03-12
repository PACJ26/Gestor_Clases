<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clase_id = $_POST["clase_id"];
    $estudiantes = $_POST["estudiantes"] ?? [];

    //cantidad de estudiantes ya inscritos
    $sql_verificar = "SELECT COUNT(*) as inscritos FROM inscripciones WHERE clase_id = ?";
    $stmt = $conexion->prepare($sql_verificar);
    $stmt->bind_param("i", $clase_id);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    $inscritos = $resultado['inscritos'];
    $cupos_disponibles = 5 - $inscritos;

    // Verificar si hay cupos
    if (count($estudiantes) > $cupos_disponibles) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => "Solo quedan $cupos_disponibles cupos disponibles en esta clase."
        ];
        session_write_close();
        header("Location: ../Vista/asignar_estudiantes.php");
        exit();
    }

    // Verificar si ya están inscritos
    $errores = [];
    foreach ($estudiantes as $estudiante_id) {
        // se captura el documento del estudiante
        $sql_doc = "SELECT documento, CONCAT(nombres, ' ', apellidos) AS nombre FROM usuarios WHERE id = ?";
        $stmt = $conexion->prepare($sql_doc);
        $stmt->bind_param("i", $estudiante_id);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();
        $documento = $resultado['documento'];
        $nombre_estudiante = $resultado['nombre'];

        // Verificar el estudiante para evitar duplicacion
        $sql_check = "SELECT COUNT(*) as existe FROM inscripciones 
                      INNER JOIN usuarios ON inscripciones.estudiante_id = usuarios.id
                      WHERE inscripciones.clase_id = ? AND usuarios.documento = ?";
        $stmt = $conexion->prepare($sql_check);
        $stmt->bind_param("is", $clase_id, $documento);
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_assoc();

        if ($resultado['existe'] > 0) {
            $errores[] = "El estudiante $nombre_estudiante (Documento: $documento) ya está inscrito en esta clase.";
        }
    }

    // mostrar mensaje en caso de error
    if (!empty($errores)) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => implode("<br>", $errores)
        ];
        session_write_close();
        header("Location: ../Vista/asignar_estudiantes.php");
        exit();
    }

    // se insertan a la base de datos
    $sql_insert = "INSERT INTO inscripciones (estudiante_id, clase_id) VALUES (?, ?)";
    $stmt = $conexion->prepare($sql_insert);

    foreach ($estudiantes as $estudiante_id) {
        $stmt->bind_param("ii", $estudiante_id, $clase_id);
        $stmt->execute();
    }

    //cupos restantes
    $nuevo_cupos_disponibles = $cupos_disponibles - count($estudiantes);

    $_SESSION['mensaje'] = [
        'tipo' => 'success',
        'texto' => "Estudiantes asignados correctamente. Cupos restantes: $nuevo_cupos_disponibles."
    ];

    session_write_close();
    header("Location: ../Vista/asignar_estudiantes.php");
    exit();
}
?>
