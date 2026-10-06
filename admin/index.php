<?php
require_once __DIR__ . '/../includes/funciones.php';
requiere_login();

$lugares = db()->query('SELECT l.id, l.nombre, l.destacado, c.nombre AS categoria
                        FROM lugares l JOIN categorias c ON c.id = l.categoria_id
                        ORDER BY c.nombre, l.nombre')->fetchAll();

$mensajes = ['creado' => 'Lugar agregado.', 'guardado' => 'Cambios guardados.', 'eliminado' => 'Lugar eliminado.'];
$aviso    = $mensajes[$_GET['ok'] ?? ''] ?? '';

$titulo = 'Panel | ' . APP_NOMBRE;
$base   = '../';
require __DIR__ . '/../includes/header.php';
?>
<section class="contenedor panel">
    <div class="panel__cabecera">
        <h1>Lugares publicados</h1>
        <a class="boton" href="form.php">Agregar lugar</a>
    </div>
    <?php if ($aviso): ?><p class="aviso aviso--ok"><?= e($aviso) ?></p><?php endif; ?>

    <?php if (!$lugares): ?>
        <p class="vacio">Todavía no hay lugares. Agrega el primero con el botón de arriba.</p>
    <?php else: ?>
    <div class="tabla-scroll">
        <table class="tabla">
            <thead><tr><th>Nombre</th><th>Categoría</th><th>Destacado</th><th>Acciones</th></tr></thead>
            <tbody>
            <?php foreach ($lugares as $l): ?>
                <tr>
                    <td><a href="../lugar.php?id=<?= (int) $l['id'] ?>"><?= e($l['nombre']) ?></a></td>
                    <td><?= e($l['categoria']) ?></td>
                    <td><?= $l['destacado'] ? 'Sí' : 'No' ?></td>
                    <td class="tabla__acciones">
                        <a href="form.php?id=<?= (int) $l['id'] ?>">Editar</a>
                        <form method="post" action="eliminar.php"
                              onsubmit="return confirm('¿Eliminar este lugar? No se puede deshacer.');">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
                            <button class="enlace-peligro" type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
