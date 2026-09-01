<?php
session_start();
if (!isset($_SESSION["id"], $_SESSION["perfil"])) {
    header("Location: login.php");
    exit;
}
$perfil = $_SESSION["perfil"];
switch ($perfil) {
    case "Administrador": $destino = "admin/dashboard.php"; break;
    case "Recepcionista": $destino = "recepcao/dashboard.php"; break;
    case "Medico": $destino = "medico/dashboard.php"; break;
    default:
        session_destroy();
        header("Location: login.php");
        exit;
}
header("Location: " . $destino);
exit;
