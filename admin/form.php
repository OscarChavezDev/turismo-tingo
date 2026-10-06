<?php
require_once __DIR__ . '/../includes/funciones.php';
requiere_login();

$id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$cats = categorias();

$lugar = ['categoria_id' => 0, 'nombre' => '', 'resumen' => '', 'descripcion' => '',
          'ubicacion' => '', 'recomendaciones' => '', 'imagen_url' => '', 'destacado' => 0];

if ($id > 0) {
    $stmt = db()->prepare('SELECT * FROM lugares WHERE id = ?');
    $stmt->execute([$id]);
    $encontrado = $stmt->fetch();
    if (!$encontrado) {
        header('Location: index.php');
        exit;
    }
    $lugar = $encontrado;
}

$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    $lugar = [
        'categoria_id'    => (int) ($_POST['categoria_id'] ?? 0),
        'nombre'          => trim($_POST['nombre'] ?? ''),
        'resumen'         => trim($_POST['resumen'] ?? ''),
        'descripcion'     => trim($_POST['descripcion'] ?? ''),
        'ubicacion'       => trim($_POST['ubicacion'] ?? ''),
        'recomendaciones' => trim($_POST['recomendaciones'] ?? ''),
        'imagen_url'      => trim($_POST['imagen_url'] ?? ''),
        'destacado'       => isset($_POST['destacado']) ? 1 : 0,
    ];

    if ($lugar['nombre'] === '' || largo($lugar['nombre']) > 120) {
        $errores[] = 'Escribe un nombre de hasta 120 caracteres.';
    }
    if ($lugar['resumen'] === '' || largo($lugar['resumen']) > 255) {
        $errores[] = 'Escribe un resumen de hasta 255 caracteres.';
    }
    if ($lugar['descripcion'] === '') {
        $errores[] = 'Escribe la descripción.';
    }
    if (!in_array($lugar['categoria_id'], array_map('intval', array_column($cats, 'id')), true)) {
        $errores[] = 'Elige una categoría.';
    }
    if ($lugar['imagen_url'] !== '' && !preg_match('#^(https?://|assets/img/[A-Za-z0-9._-]+$)#i', $lugar['imagen_url'])) {
        $errores[] = 'La imagen debe ser una dirección http(s) o un archivo de assets/img/, por ejemplo assets/img/foto.jpg.';
    }

    if (!$errores) {
        $datos = [
            $lugar['categoria_id'], $lugar['nombre'], $lugar['resumen'], $lugar['descripcion'],
            $lugar['ubicacion'], $lugar['recomendaciones'] ?: null, $lugar['imagen_url'] ?: null, $lugar['destacado'],
        ];
        if ($id > 0) {
            $datos[] = $id;
            db()->prepare('UPDATE lugares SET categoria_id=?, nombre=?, resumen=?, descripcion=?, ubicacion=?,
                           recomendaciones=?, imagen_url=?, destacado=? WHERE id=?')->execute($datos);
            header('Location: index.php?ok=guardado');
        } else {
            db()->prepare('INSERT INTO lugares (categoria_id, nombre, resumen, descripcion, ubicacion,
                           recomendaciones, imagen_url, destacado) VALUES (?,?,?,?,?,?,?,?)')->execute($datos);
            header('Location: index.php?ok=creado');
        }
        exit;
    }
}

$titulo = ($id > 0 ? 'Editar lugar' : 'Agregar lugar') . ' | ' . APP_NOMBRE;
$base   = '../';
require __DIR__ . '/../includes/header.php';
?>
<section class="contenedor panel panel--medio">
    <h1><?= $id > 0 ? 'Editar lugar' : 'Agregar lugar' ?></h1>

    <?php if ($errores): ?>
        <ul class="aviso aviso--error">
            <?php foreach ($errores as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" class="formulario">
        <?= csrf_campo() ?>
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" maxlength="120" required value="<?= e($lugar['nombre']) ?>">

        <label for="categoria_id">Categoría</label>
        <select id="categoria_id" name="categoria_id" required>
            <option value="">Elige una categoría</option>
            <?php foreach ($cats as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= (int) $lugar['categoria_id'] === (int) $c['id'] ? 'selected' : '' ?>><?= e($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="resumen">Resumen (una línea)</label>
        <input id="resumen" name="resumen" maxlength="255" required value="<?= e($lugar['resumen']) ?>">

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" rows="6" required><?= e($lugar['descripcion']) ?></textarea>

        <label for="ubicacion">Dónde queda</label>
        <input id="ubicacion" name="ubicacion" maxlength="200" value="<?= e($lugar['ubicacion']) ?>">

        <label for="recomendaciones">Recomendaciones antes de ir</label>
        <textarea id="recomendaciones" name="recomendaciones" rows="3"><?= e($lugar['recomendaciones']) ?></textarea>

        <label for="imagen_url">Imagen (opcional): archivo de assets/img/ o dirección https</label>
        <input id="imagen_url" name="imagen_url" maxlength="500" placeholder="assets/img/foto.jpg" value="<?= e($lugar['imagen_url']) ?>">

        <label class="casilla">
            <input type="checkbox" name="destacado" <?= $lugar['destacado'] ? 'checked' : '' ?>>
            Mostrar como destacado al inicio de la lista
        </label>

        <div class="formulario__acciones">
            <button class="boton" type="submit"><?= $id > 0 ? 'Guardar cambios' : 'Agregar lugar' ?></button>
            <a href="index.php">Cancelar</a>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
