<?php
/** Variables esperadas: $titulo (string), $base (ruta relativa a la raíz, '' o '../') */
$base   = $base ?? '';
$titulo = $titulo ?? APP_NOMBRE;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,600;12..96,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $base ?>assets/css/estilos.css">
</head>
<body>
<header class="barra">
    <div class="contenedor barra__fila">
        <a class="marca" href="<?= $base ?>index.php">Tingo María <span>turismo</span></a>
        <nav class="barra__nav" aria-label="Principal">
            <a href="<?= $base ?>index.php">Lugares</a>
            <?php if (esta_logueado()): ?>
                <a href="<?= $base ?>admin/index.php">Panel</a>
                <a href="<?= $base ?>admin/logout.php">Cerrar sesión</a>
            <?php else: ?>
                <a href="<?= $base ?>admin/login.php">Administrar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
