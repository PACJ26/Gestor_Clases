<?php
session_start();
include("../Conexion/conexion.php");
$sql = "SELECT * FROM asignaturas";
$resultado = $conexion->query($sql);
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
    <link rel="stylesheet" href="CSS/formulario_asignaturas.css">
    <title>Asignaturas</title>
</head>

<body>
    <div class="container mt-4">
        <h3 class="text-center">Registrar Asignaturas</h3>
        <form action="../Controladores/registrar_asignatura.php" method="POST">
            <div class="form-group">
                <label class="form-label">Nombre de la Asignatura</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="text-center p-3">
                <button type="submit" class="btn btn-yellow">Registrar</button>
                <a href="panel_administrador.php" class="btn btn-yellow">Volver</a>
            </div>
        </form>

        <div class="m-auto">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $resultado->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $fila["id"] ?></td>
                            <td><?= $fila["nombre"] ?></td>
                            <td>
                                <a href="editar_asignatura.php?id=<?= $fila['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="../Controladores/eliminar_asignatura.php?id=<?= $fila['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta asignatura?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!--izitoast-->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <!--bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../Vista/JS/config_sesion.js"></script>
    <script>
        <?php if (isset($_SESSION['mensaje'])): ?>
            iziToast.<?php echo $_SESSION['mensaje']['tipo']; ?>({
                title: "Mensaje",
                message: "<?php echo $_SESSION['mensaje']['texto']; ?>",
                position: "topRight",
                timeout: 5000,
                color: "black",
                progressBarColor: "black",
                backgroundColor: "<?php echo ($_SESSION['mensaje']['tipo'] == 'error') ? '#b33c3c' : '#33a658'; ?>",
                icon: "fas <?php echo ($_SESSION['mensaje']['tipo'] == 'error') ? 'fa-times-circle' : 'fa-check-circle'; ?>",
                transitionIn: "bounceInLeft",
                transitionOut: "fadeOutRight"
            });
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>
    </script>
</body>

</html>