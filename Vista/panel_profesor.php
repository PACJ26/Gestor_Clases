<?php
session_start();

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] != 2) {
    header("Location: index.php");
    exit();
}

$nombre = $_SESSION["usuario"]["nombre"];
$apellido = $_SESSION["usuario"]["apellido"];
$rol = "Profesor";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Docente</title>
    <!--bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!--izitoast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
    <!--ICONOS-->
    <script src="https://kit.fontawesome.com/fb9c53fb4c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="CSS/paneles.css">
</head>

<body>
    <div class="sidebar">
        <div class="text-center">
            <div class="profile-img">👤</div>
            <h4 class="mt-3"><?= $nombre . " " . $apellido ?></h4>
            <p class="text-light"><?= $rol ?></p>
        </div>
        <div>
            <a href="../Controladores/cerrarsesion.php" class="btn btn-danger logout-btn">Cerrar Sesión</a>
        </div>
    </div>

    <div class="container text-center">
        <h2 class="text-center mb-4">Panel de Docente</h2>
        <div class="d-flex flex-wrap justify-content-center gap-4">
            <a href="clases.php" class="btn btn-yellow p-4 text-center d-flex flex-column align-items-center">
                <i class="fa-solid fa-calendar-days fa-2x"></i>
                <span class="mt-2">Ver Calendario</span>
            </a>
        </div>
    </div>

    <!--izitoast-->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <!--bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../Vista/JS/config_sesion.js"></script>
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