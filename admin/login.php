<?php
require_once __DIR__ . '/../includes/funciones.php';

if (esta_logueado()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    $usuario = trim($_POST['usuario'] ?? '');
    $clave   = $_POST['clave'] ?? '';

    $stmt = db()->prepare('SELECT id, nombre, clave_hash FROM usuarios WHERE usuario = ?');
    $stmt->execute([$usuario]);
    $u = $stmt->fetch();

    if ($u && password_verify($clave, $u['clave_hash'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id']     = (int) $u['id'];
        $_SESSION['usuario_nombre'] = $u['nombre'];
        header('Location: index.php');
        exit;
    }
    $error = 'Usuario o contraseña incorrectos.';
}

$titulo = 'Iniciar sesión | ' . APP_NOMBRE;
$base   = '../';
require __DIR__ . '/../includes/header.php';
?>
<section class="contenedor panel panel--angosto">
    <h1>Iniciar sesión</h1>
    <?php if ($error): ?><p class="aviso aviso--error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" class="formulario">
        <?= csrf_campo() ?>
        <label for="usuario">Usuario</label>
        <input id="usuario" name="usuario" required autofocus autocomplete="username">
        <label for="clave">Contraseña</label>
        <input id="clave" name="clave" type="password" required autocomplete="current-password">
        <button class="boton" type="submit">Iniciar sesión</button>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
