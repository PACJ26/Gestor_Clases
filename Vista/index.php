<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!--izitoast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
    <link rel="stylesheet" href="CSS/login.css">
    <title>Inicio de Sesión</title>
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Iniciar Sesión</h2>
        <form action="../Controladores/procesar_login.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Correo Electrónico:</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <input type="password" name="contraseña" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-yellow">Iniciar Sesión</button>
        </form>
    </div>

    <!--izitoast-->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <!--bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
    <script>
        if (localStorage.getItem("sesion_expirada") === "true") {
            iziToast.warning({
                title: 'Sesión Expirada',
                message: 'Tu sesión ha expirado por inactividad.',
                position: 'topRight',
                timeout: 5000
            });
            localStorage.removeItem("sesion_expirada"); // Eliminar repeticiones
        }
    </script>
</body>

</html>