<?php
session_start();

// Verificar si el usuario está autenticado y es administrador
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
    <link rel="stylesheet" href="CSS/form_administrador.css">
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

    <div class="content">
        <h2 class="text-center">Panel de Docente</h2>
        <div class="content">
            <a href="clases.php" class="btn btn-success p-4">Ver Calendario</a>
            <a href="gestionar_notas.php" class="btn btn-success p-4">Gestionar Notas</a>
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