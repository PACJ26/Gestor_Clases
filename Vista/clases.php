<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}
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
                            <label for="hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="hora" name="hora" required>
                        </div>
                        <div class="mb-3">
                            <label for="hora_fin">Hora de Finalización:</label>
                            <input type="time" class="form-control" name="hora_fin" id="hora_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="asignatura" class="form-label">Asignatura</label>
                            <select class="form-control" id="asignatura" name="asignatura" required>
                                <option value="">Seleccione una asignatura</option>
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
                                <option value="">Seleccione un profesor</option>
                                <?php
                                $resultado = $conexion->query("SELECT id, nombres FROM usuarios WHERE rol_id = 2");
                                while ($fila = $resultado->fetch_assoc()) {
                                    echo "<option value='" . $fila['id'] . "'>" . $fila['nombres'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Clase</button>
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
                            <label for="edit_hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="edit_hora" name="hora" required>
                        </div>
                        <div class="mb-3">
                            <label for="hora_fin">Hora de Finalización:</label>
                            <input type="time" class="form-control" name="hora_fin" id="edit_hora_fin" required>
                        </div>
                        <button type="submit" class="btn btn-success">Actualizar</button>
                        <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-4">
        <h3 class="text-center">Calendario de Clases</h3>
        <div id="calendar"></div>
    </div>

    <style>
        .evento-centrado {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100%;
            font-size: 15px;
            /* Ajusta el tamaño del texto */
            padding: 5px;
            /* Agrega espacio dentro del evento */

        }
    </style>

    <!--izitoast-->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/es.js"></script>

    <script src="../Vista/JS/config_sesion.js"></script>

    <!--Armar un scrip por aparte-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                events: '../Controladores/obtener_clases.php',

                eventDidMount: function(info) {
                    let horaInicio = info.event.start.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    let horaFin = info.event.end ? info.event.end.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : "";


                    info.el.classList.add("evento-centrado");
                    info.el.innerHTML = `<b>${info.event.title}</b><br>${horaInicio} - ${horaFin}`;
                },



                dateClick: function(info) {
                    document.getElementById('fecha').value = info.dateStr;
                    var myModal = new bootstrap.Modal(document.getElementById('modalAgregarClase'));
                    myModal.show();
                },

                eventClick: function(info) {
                    let id = info.event.id;
                    let fecha = info.event.startStr.split("T")[0];
                    let hora = info.event.startStr.split("T")[1]?.substring(0, 5);
                    let hora_fin = info.event.endStr.split("T")[1]?.substring(0, 5);

                    document.getElementById("edit_id").value = id;
                    document.getElementById("edit_fecha").value = fecha;
                    document.getElementById("edit_hora").value = hora;
                    document.getElementById("edit_hora_fin").value = hora_fin;

                    var myModal = new bootstrap.Modal(document.getElementById("modalEditarClase"));
                    myModal.show();
                }
            });

            document.getElementById("formAgregarClase").addEventListener("submit", function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch("../Controladores/guardar_clase.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert("Clase registrada con éxito");
                            location.reload();
                        } else {
                            alert("Error al registrar la clase");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });

            document.getElementById("formEditarClase").addEventListener("submit", function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch("../Controladores/editar_clase.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert("Clase actualizada correctamente");
                            location.reload();
                        } else {
                            alert("Error al actualizar la clase");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });

            document.getElementById("btnEliminar").addEventListener("click", function() {
                if (!confirm("¿Estás seguro de eliminar esta clase?")) {
                    return;
                }

                var id = document.getElementById("edit_id").value;

                fetch("../Controladores/eliminar_clase.php", {
                        method: "POST",
                        body: new URLSearchParams({
                            id: id
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            alert("Clase eliminada correctamente");
                            location.reload();
                        } else {
                            alert("Error al eliminar la clase");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });

            calendar.render();
        });
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