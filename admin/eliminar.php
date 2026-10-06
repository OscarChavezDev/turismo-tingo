<?php
require_once __DIR__ . '/../includes/funciones.php';
requiere_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    db()->prepare('DELETE FROM lugares WHERE id = ?')->execute([(int) ($_POST['id'] ?? 0)]);
    header('Location: index.php?ok=eliminado');
    exit;
}
header('Location: index.php');
