<?php
session_start();
include("../Conexion/conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clase_id = $_POST["clase_id"];
    $estudiantes = isset($_POST["estudiantes"]) ? $_POST["estudiantes"] : [];

    // verifica cuantos estudiantes ya estan inscritos 
    $sql_verificar = "SELECT COUNT(*) as total FROM inscripciones WHERE clase_id = ?";
    $stmt = $conexion->prepare($sql_verificar);
    $stmt->bind_param("i", $clase_id);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();

    // condicion tope maximo de inscriciones
    if (($resultado['total'] + count($estudiantes)) > 5) {
        $_SESSION['mensaje'] = [
            'tipo' => 'error',
            'texto' => 'No puedes asignar más de 5 estudiantes a una clase.'
        ];
        session_write_close();
        header("Location: ../Vista/asignar_estudiantes.php");
        exit();
    }

    // registro en la base de datos
    foreach ($estudiantes as $estudiante_id) {
        $sql_insert = "INSERT INTO inscripciones (estudiante_id, clase_id) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql_insert);
        $stmt->bind_param("ii", $estudiante_id, $clase_id);
        $stmt->execute();
    }

    $_SESSION['mensaje'] = [
        'tipo' => 'success',
        'texto' => 'Estudiantes asignados correctamente.'
    ];
    session_write_close();
    header("Location: ../Vista/asignar_estudiantes.php");
    exit();
}
?>
