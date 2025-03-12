<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

$rol = $_SESSION['usuario']['rol'];

?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario Clases</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!--izitoast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <link rel="stylesheet" href="../Vista/CSS/calendario.css">
</head>

<body>

    <!-- Modal Agrergar-->
    <div class="modal fade" id="modalAgregarClase" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarClase">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="hora" class="form-label">Hora de Inicio</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                        <div class="mb-3">
                            <label for="hora_fin">Hora de Finalización</label>
                            <input type="time" class="form-control" name="hora_fin" id="hora_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="asignatura" class="form-label">Asignatura</label>
                            <select class="form-control" id="asignatura" name="asignatura" required>
                                <option value="">Seleccione asignatura</option>
                                <?php
                                include("../Conexion/conexion.php");
                                $resultado = $conexion->query("SELECT id, nombre FROM asignaturas");
                                while ($fila = $resultado->fetch_assoc()) {
                                    echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="profesor" class="form-label">Profesor</label>
                            <select class="form-control" id="profesor" name="profesor" required>
                                <option value="">Seleccione profesor</option>
                                <?php
                                $resultado = $conexion->query("SELECT id, nombres FROM usuarios WHERE rol_id = 2");
                                while ($fila = $resultado->fetch_assoc()) {
                                    echo "<option value='" . $fila['id'] . "'>" . $fila['nombres'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-yellow">Guardar Clase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Editar/Eliminar-->
    <div class="modal fade" id="modalEditarClase" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Clase</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarClase">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="edit_fecha" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_hora" class="form-label">Hora de Inicio </label>
                            <input type="time" class="form-control" id="edit_hora" name="hora" required>
                        </div>
                        <div class="mb-3">
                            <label for="hora_fin">Hora de Finalización </label>
                            <input type="time" class="form-control" name="hora_fin" id="edit_hora_fin" required>
                        </div>
                        <button type="submit" class="btn btn-yellow">Actualizar</button>
                        <button type="button" class="btn btn-yellow" id="btnEliminar">Eliminar</button>
                        <button id="btnCancelarClase" class="btn btn-yellow">Cancelar Clase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-4">
        <h3 class="text-center">Calendario de Clases</h3>
        <div id="calendar"></div>
    </div>

    <div class="text-center">
        <?php
        if ($rol == 1) {
            echo '<a href="panel_administrador.php" class="btn btn-yellow">Volver</a>';
        } elseif ($rol == 2) {
            echo '<a href="panel_profesor.php" class="btn btn-yellow">Volver</a>';
        } elseif ($rol == 3) {
            echo '<a href="panel_estudiante.php" class="btn btn-yellow">Volver</a>';
        }
        ?>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>
    <script src="../Vista/JS/config_sesion.js"></script>
    <script>
        var usuarioRol = <?php echo json_encode($rol); ?>;
    </script>
    <script src="../Vista//JS/calendario.js"></script>

</body>

</html>