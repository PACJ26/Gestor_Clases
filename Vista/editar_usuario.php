<?php
include("../Conexion/conexion.php");
include("../Controladores/consultar_roles.php");
// Verificar si se ha recibido el ID por GET
if (!isset($_GET['id'])) {
    echo "<script>alert('ID no proporcionado.'); window.location.href='lista_usuarios.php';</script>";
    exit();
}

$id = $_GET['id'];
// Obtener los datos del usuario
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    echo "<script>alert('Usuario no encontrado.'); window.location.href='lista_usuarios.php';</script>";
    exit();
}
$resultado_tipos = obtenerTiposdocumento();
$resultado_roles = obtenerRoles();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- IziToast -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">
    <link rel="stylesheet" href="CSS/formulario_registro.css">
</head>

<body>
    <div class="card">
        <h2 class="text-center">Editar Usuario</h2>
        <form action="../Controladores/editar_usuario.php" method="POST">
            <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nombres:</label>
                    <input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($usuario['nombres']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Apellidos:</label>
                    <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tipo de Documento:</label>
                    <select name="tipo_documento_id" class="form-control" required>
                        <?php while ($doc = $resultado_tipos->fetch_assoc()): ?>
                            <option value="<?= $doc['id'] ?>" <?= ($usuario['tipo_documento_id'] == $doc['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($doc['tipo']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Documento:</label>
                    <input type="text" name="documento" class="form-control" value="<?= htmlspecialchars($usuario['documento']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Teléfono:</label>
                    <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Correo Electrónico:</label>
                    <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Rol:</label>
                    <select name="rol_id" class="form-control" required>
                        <?php while ($rol = $resultado_roles->fetch_assoc()): ?>
                            <option value="<?= $rol['id'] ?>" <?= ($usuario['rol_id'] == $rol['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($rol['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-yellow">Guardar Cambios</button>
            <a href="usuarios_registrados.php" class="btn btn-yellow">Volver</a>
        </form>
    </div>

    <!-- IziToast -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../Vista/JS/config_sesion.js"></script>
</body>

</html>