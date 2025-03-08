<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] != 1) {
    header("Location: index.php");
    exit();
}

include("../Conexion/conexion.php");

$sql_clases = "SELECT c.id, a.nombre as asignatura, c.fecha, c.hora, c.hora_fin, u.nombres as profesor
               FROM clases c
               INNER JOIN asignaturas a ON c.asignatura_id = a.id
               INNER JOIN usuarios u ON c.profesor_id = u.id";
$resultado_clases = $conexion->query($sql_clases);

$sql_estudiantes = "SELECT id, nombres, apellidos FROM usuarios WHERE rol_id = 3";
$resultado_estudiantes = $conexion->query($sql_estudiantes);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<!--izitoast-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
<link rel="stylesheet" href="CSS/form_registro.css">
<link rel="stylesheet" href="CSS/listar_usuarios.css">

<body>

    <div class="container mt-4">
        <h3 class="text-center">Asignar Estudiantes a Clases</h3>

        <form action="../Controladores/procesar_asignacion.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Seleccionar Clase:</label>
                <select name="clase_id" class="form-control" required>
                    <option value="">Seleccione una clase</option>
                    <?php while ($clase = $resultado_clases->fetch_assoc()): ?>
                        <option value="<?= $clase['id'] ?>">
                            <?= $clase['asignatura'] . " - " . $clase['profesor'] . " (" . $clase['fecha'] . " " . $clase['hora'] . " a " . $clase['hora_fin'] . ")" ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Seleccionar Estudiantes (Máximo 5):</label>
                <select name="estudiantes[]" class="form-control" multiple required>
                    <?php while ($estudiante = $resultado_estudiantes->fetch_assoc()): ?>
                        <option value="<?= $estudiante['id'] ?>">
                            <?= $estudiante['nombres'] . " " . $estudiante['apellidos'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Asignar Estudiantes</button>
        </form>
        <hr>
        <h4 class="text-center">Estudiantes Asignados</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Clase</th>
                    <th>Profesor</th>
                    <th>Estudiantes</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_listar = "SELECT c.id, a.nombre as asignatura, u.nombres as profesor, 
                       GROUP_CONCAT(e.nombres SEPARATOR ', ') as estudiantes
                       FROM clases c
                       INNER JOIN asignaturas a ON c.asignatura_id = a.id
                       INNER JOIN usuarios u ON c.profesor_id = u.id
                       LEFT JOIN inscripciones i ON c.id = i.clase_id
                       LEFT JOIN usuarios e ON i.estudiante_id = e.id
                       GROUP BY c.id";
                $resultado_listar = $conexion->query($sql_listar);

                while ($fila = $resultado_listar->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $fila["asignatura"] ?></td>
                        <td><?= $fila["profesor"] ?></td>
                        <td><?= $fila["estudiantes"] ?: 'Sin estudiantes asignados' ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
    <!--izitoast-->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if (isset($_SESSION['mensaje'])): ?>
            iziToast.<?= $_SESSION['mensaje']['tipo'] ?>({
                title: "Mensaje",
                message: "<?= $_SESSION['mensaje']['texto'] ?>",
                position: "topRight",
                timeout: 5000,
                backgroundColor: "<?= ($_SESSION['mensaje']['tipo'] == 'error') ? '#b33c3c' : '#33a658' ?>",
                icon: "fas <?= ($_SESSION['mensaje']['tipo'] == 'error') ? 'fa-times-circle' : 'fa-check-circle' ?>",
                transitionIn: "bounceInLeft",
                transitionOut: "fadeOutRight"
            });
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
    </script>
</body>

</html>