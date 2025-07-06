<?php
// public/index.php
session_start();

// Si el usuario ya está logueado, redirigir al dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ../views/admin/dashboard.php');
    exit;
}

// Si no está logueado, mostrar el login
header('Location: ../views/login.php');
exit;
?>