<?php
require_once __DIR__ . '/includes/funciones.php';

$id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$stmt = db()->prepare('SELECT l.*, c.nombre AS categoria FROM lugares l
                       JOIN categorias c ON c.id = l.categoria_id WHERE l.id = ?');
$stmt->execute([$id]);
$l = $stmt->fetch();

if (!$l) {
    http_response_code(404);
    $titulo = 'Lugar no encontrado';
    require __DIR__ . '/includes/header.php';
    echo '<section class="contenedor ficha"><h1>Ese lugar no existe</h1><p><a href="index.php">Volver a la lista de lugares</a></p></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$titulo = $l['nombre'] . ' | ' . APP_NOMBRE;
require __DIR__ . '/includes/header.php';
?>
<article class="contenedor ficha">
    <p class="ficha__volver"><a href="index.php">Volver a todos los lugares</a></p>
    <p class="lugar__categoria"><?= e($l['categoria']) ?></p>
    <h1><?= e($l['nombre']) ?></h1>
    <p class="ficha__resumen"><?= e($l['resumen']) ?></p>

    <?php if ($foto = imagen_mostrable($l['imagen_url'])): ?>
        <img class="ficha__foto" src="<?= e($foto) ?>" alt="<?= e($l['nombre']) ?>">
    <?php endif; ?>

    <div class="ficha__columnas">
        <div class="ficha__texto"><?= nl2br(e($l['descripcion'])) ?></div>
        <aside class="ficha__datos">
            <?php if ($l['ubicacion'] !== ''): ?>
                <h2>Dónde queda</h2>
                <p><?= e($l['ubicacion']) ?></p>
            <?php endif; ?>
            <?php if (!empty($l['recomendaciones'])): ?>
                <h2>Antes de ir</h2>
                <p><?= nl2br(e($l['recomendaciones'])) ?></p>
            <?php endif; ?>
        </aside>
    </div>
</article>
<?php require __DIR__ . '/includes/footer.php'; ?>
