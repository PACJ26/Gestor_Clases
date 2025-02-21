let tiempoInactividad = 30 * 1000; // 30 segundos

    // Función para redirigir al login después del tiempo de inactividad
    function cerrarSesionPorInactividad() {
        localStorage.setItem("sesion_expirada", "true");
        window.location.href = "index.php";
    }

    // Resetear el temporizador en cada interacción del usuario
    function resetearTemporizador() {
        clearTimeout(window.temporizador);
        window.temporizador = setTimeout(cerrarSesionPorInactividad, tiempoInactividad);
    }

    // Eventos que reinician el temporizador
    window.onload = resetearTemporizador;
    document.onmousemove = resetearTemporizador;
    document.onkeypress = resetearTemporizador;


    