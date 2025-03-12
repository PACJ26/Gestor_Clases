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
    <title>Asignar Clases</title>
</head>
<!-- Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<!--izitoast-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
<link rel="stylesheet" href="CSS/asignar.css">

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
            <button type="submit" class="btn btn-yellow">Asignar Estudiantes</button>
            <a href="panel_administrador.php" class="btn btn-yellow">Regresar</a>
        </form>
        <hr>
        <h4 class="text-center">Estudiantes Asignados</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Clase</th>
                    <th>Horario</th>
                    <th>Profesor</th>
                    <th>Estudiante</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql_listar = "SELECT i.id AS inscripcion_id, c.id AS clase_id, a.nombre AS asignatura, 
                   CONCAT(u.nombres, ' ', u.apellidos) AS profesor, 
                   CONCAT(e.nombres, ' ', e.apellidos) AS estudiante, e.id AS estudiante_id, c.hora, c.hora_fin
                   FROM clases c
                   INNER JOIN asignaturas a ON c.asignatura_id = a.id
                   INNER JOIN usuarios u ON c.profesor_id = u.id
                   INNER JOIN inscripciones i ON c.id = i.clase_id
                   INNER JOIN usuarios e ON i.estudiante_id = e.id";
                $resultado_listar = $conexion->query($sql_listar);

                while ($fila = $resultado_listar->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $fila["asignatura"] ?></td>
                        <td><?= $fila["hora"] . " - " . $fila["hora_fin"] ?></td>
                        <td><?= $fila["profesor"] ?></td>
                        <td><?= $fila["estudiante"] ?></td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?= $fila['inscripcion_id'] ?>)">Eliminar</button>
                            <form id="formEliminar<?= $fila['inscripcion_id'] ?>" action="../Controladores/eliminar_estudiante.php" method="POST" style="display: none;">
                                <input type="hidden" name="inscripcion_id" value="<?= $fila['inscripcion_id'] ?>">
                            </form>
                        </td>
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
        function confirmarEliminacion(inscripcion_id) {
            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                title: '¿Estás seguro?',
                message: 'Esta acción no se puede deshacer.',
                position: 'center',
                buttons: [
                    ['<button><b>Si, eliminar</b></button>', function(instance, toast) {
                        document.getElementById('formEliminar' + inscripcion_id).submit();
                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast);
                    }, true],
                    ['<button>Cancelar</button>', function(instance, toast) {
                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast);
                    }]
                ]
            });
        }
    </script>

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