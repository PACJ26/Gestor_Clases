<?php
session_start();
include("../Controladores/consultar_roles.php");
$resultado_tipos = obtenerTiposdocumento();
$resultado_roles = obtenerRoles();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!--izitoast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
    <link rel="stylesheet" href="CSS/form_registro.css">
    <title>Registro de Usuarios</title>
</head>

<body>
    <div class="card">
        <h4>Registrar Usuarios</h4>
        <form action="../Controladores/registrar_persona.php" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombres:</label>
                    <input type="text" name="nombres" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellidos:</label>
                    <input type="text" name="apellidos" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Documento:</label>
                    <select name="tipo_documento" class="form-control" required>
                        <option value="">Seleccione</option>
                        <?php while ($tipo = $resultado_tipos->fetch_assoc()) { ?>
                            <option value="<?= $tipo['id'] ?>"><?= $tipo['tipo'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Documento:</label>
                    <input type="text" name="documento" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Correo Electrónico:</label>
                    <input type="email" name="correo" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contraseña:</label>
                    <input type="password" name="contraseña" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rol:</label>
                    <select name="rol" class="form-control" required>
                        <option value="">Seleccione</option>
                        <?php while ($rol = $resultado_roles->fetch_assoc()) { ?>
                            <option value="<?= $rol['id'] ?>"><?= $rol['nombre'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Registrar</button>
                <a href="panel_administrador.php"class="btn btn-primary">Volver</a>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['mensaje'])): ?>
                iziToast.<?= $_SESSION['mensaje']['tipo'] ?>({
                    title: "<?= ucfirst($_SESSION['mensaje']['tipo']) ?>",
                    message: "<?= $_SESSION['mensaje']['texto'] ?>",
                    position: "topRight",
                    timeout: 5000,
                    progressBarColor: "black",
                    transitionIn: "bounceInLeft",
                    transitionOut: "fadeOutRight",
                    animateInside: true
                });
                <?php unset($_SESSION['mensaje']);
                ?>
            <?php endif; ?>
        });
    </script>
    <!--izitoast-->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <!--bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../Vista/JS/config_sesion.js"></script>
</body>

</html>