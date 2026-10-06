<?php
require_once __DIR__ . '/includes/funciones.php';

$cat = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
$q   = trim($_GET['q'] ?? '');

$sql    = 'SELECT l.id, l.nombre, l.resumen, l.ubicacion, l.imagen_url, l.destacado, c.nombre AS categoria
           FROM lugares l JOIN categorias c ON c.id = l.categoria_id WHERE 1=1';
$params = [];
if ($cat > 0) {
    $sql .= ' AND l.categoria_id = ?';
    $params[] = $cat;
}
if ($q !== '') {
    $sql .= ' AND (l.nombre LIKE ? OR l.resumen LIKE ? OR l.descripcion LIKE ?)';
    $like = '%' . $q . '%';
    array_push($params, $like, $like, $like);
}
$sql .= ' ORDER BY l.destacado DESC, l.nombre';

$stmt = db()->prepare($sql);
$stmt->execute($params);
$lugares = $stmt->fetchAll();
$cats    = categorias();

$titulo = APP_NOMBRE;
require __DIR__ . '/includes/header.php';
?>
<section class="portada">
    <div class="contenedor portada__texto">
        <h1>La puerta de la Amazonía duerme bajo una montaña</h1>
        <p>Cuevas, cataratas, sabores y fiestas de Tingo María, la ciudad de La Bella Durmiente.</p>
    </div>
    <svg class="portada__cerros" viewBox="0 0 1200 220" preserveAspectRatio="none" aria-hidden="true">
        <path class="cerros--lejos" d="M0,220 L0,150 C80,120 140,135 210,110 C270,90 300,60 350,72 C390,82 400,118 450,112 C520,104 560,58 640,50 C700,44 730,78 790,86 C860,96 900,70 960,84 C1040,102 1100,128 1200,120 L1200,220 Z"/>
        <path class="cerros--cerca" d="M0,220 L0,185 C120,160 200,176 300,150 C380,130 420,150 520,138 C640,124 700,160 820,150 C940,140 1040,172 1200,158 L1200,220 Z"/>
    </svg>
</section>

<section class="contenedor filtros" aria-label="Filtrar lugares">
    <div class="chips">
        <a class="chip <?= $cat === 0 ? 'chip--activo' : '' ?>" href="index.php<?= $q !== '' ? '?q=' . urlencode($q) : '' ?>">Todo</a>
        <?php foreach ($cats as $c): ?>
            <a class="chip <?= $cat === (int) $c['id'] ? 'chip--activo' : '' ?>"
               href="index.php?cat=<?= (int) $c['id'] ?><?= $q !== '' ? '&amp;q=' . urlencode($q) : '' ?>"><?= e($c['nombre']) ?></a>
        <?php endforeach; ?>
    </div>
    <form class="buscador" method="get" action="index.php" role="search">
        <?php if ($cat > 0): ?><input type="hidden" name="cat" value="<?= $cat ?>"><?php endif; ?>
        <label class="oculto" for="q">Buscar un lugar</label>
        <input id="q" type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar: catarata, juane, cueva">
        <button type="submit">Buscar</button>
    </form>
</section>

<section class="contenedor lista">
    <?php if (!$lugares): ?>
        <p class="vacio">No hay resultados con ese filtro. <a href="index.php">Ver todos los lugares</a>.</p>
    <?php endif; ?>

    <?php foreach ($lugares as $l): ?>
        <article class="lugar <?= $l['destacado'] ? 'lugar--destacado' : '' ?>">
            <?php if ($foto = imagen_mostrable($l['imagen_url'])): ?>
                <img class="lugar__foto" src="<?= e($foto) ?>" alt="<?= e($l['nombre']) ?>" loading="lazy">
            <?php endif; ?>
            <div class="lugar__cuerpo">
                <p class="lugar__categoria"><?= e($l['categoria']) ?></p>
                <h2><a href="lugar.php?id=<?= (int) $l['id'] ?>"><?= e($l['nombre']) ?></a></h2>
                <p><?= e($l['resumen']) ?></p>
                <?php if ($l['ubicacion'] !== ''): ?>
                    <p class="lugar__ubicacion"><?= e($l['ubicacion']) ?></p>
                <?php endif; ?>
            </div>
        </article>
    <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
