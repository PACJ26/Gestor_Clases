document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es',
        initialView: 'dayGridMonth',
        editable: true,
        selectable: true,
        events: '../Controladores/obtener_clases.php',

        eventDidMount: function (info) {
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

        dateClick: function (info) {
            document.getElementById('fecha').value = info.dateStr;
            var myModal = new bootstrap.Modal(document.getElementById('modalAgregarClase'));
            myModal.show();
        },

        eventClick: function (info) {
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

    document.getElementById("formAgregarClase").addEventListener("submit", function (event) {
        event.preventDefault();

        var formData = new FormData(this);

        fetch("guardar_clase.php", {
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


    document.getElementById("formEditarClase").addEventListener("submit", function (event) {
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

    document.getElementById("btnEliminar").addEventListener("click", function () {
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
