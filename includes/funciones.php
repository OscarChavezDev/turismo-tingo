<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

/** Escapa texto para imprimirlo en HTML. */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_verificar(): void
{
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(400);
        exit('La sesión del formulario expiró. Vuelve atrás y recarga la página.');
    }
}

function esta_logueado(): bool
{
    return !empty($_SESSION['usuario_id']);
}

function requiere_login(): void
{
    if (!esta_logueado()) {
        header('Location: login.php');
        exit;
    }
}

function categorias(): array
{
    return db()->query('SELECT id, nombre FROM categorias ORDER BY nombre')->fetchAll();
}

/** Cuenta caracteres UTF-8 sin depender de la extensión mbstring. */
function largo(string $texto): int
{
    return function_exists('mb_strlen') ? mb_strlen($texto, 'UTF-8') : (int) preg_match_all('/./us', $texto);
}

/**
 * Devuelve la dirección de la imagen si se puede mostrar, o '' si no.
 * Acepta direcciones http(s) y archivos locales dentro de assets/img/.
 */
function imagen_mostrable(?string $ruta): string
{
    $ruta = trim($ruta ?? '');
    if ($ruta === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $ruta)) {
        return $ruta;
    }
    if (preg_match('#^assets/img/[A-Za-z0-9._-]+$#', $ruta) && is_file(__DIR__ . '/../' . $ruta)) {
        return $ruta;
    }
    return '';
}
