let tiempoInactividad = 10 * 60 * 1000;

    // Función inactividad
    function cerrarSesionPorInactividad() {
        localStorage.setItem("sesion_expirada", "true");
        window.location.href = "index.php";
    }

    // Resetear el temporizador
    function resetearTemporizador() {
        clearTimeout(window.temporizador);
        window.temporizador = setTimeout(cerrarSesionPorInactividad, tiempoInactividad);
    }

    //reinician el temporizador
    window.onload = resetearTemporizador;
    document.onmousemove = resetearTemporizador;
    document.onkeypress = resetearTemporizador;


    