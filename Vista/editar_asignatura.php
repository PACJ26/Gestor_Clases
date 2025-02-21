<?php
session_start();
include("../Conexion/conexion.php");

// Verificar si se recibió un ID válido
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "ID de asignatura no válido."
    ];
    header("Location: asignaturas.php");
    exit();
}

$id = $_GET["id"];
$sql = "SELECT * FROM asignaturas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    $_SESSION["mensaje"] = [
        "tipo" => "error",
        "texto" => "Asignatura no encontrada."
    ];
    header("Location: asignaturas.php");
    exit();
}

$asignatura = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Asignatura</title>
    <!--bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!--izitoast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
    <link rel="stylesheet" href="CSS/form_registro.css">
</head>

<body>

    <div class="card">
        <h4 class="text-center mb-3">Modificar Asignatura</h4>
        <form action="../Controladores/actualizar_asignatura.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombre de la Asignatura</label>
                    <input type="text" name="nombre" class="form-control" value="<?= $asignatura["nombre"] ?>" required>
                </div>
            </div>
            <div class="text-center p-3">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="registrar_asignatura.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
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