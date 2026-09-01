<?php

session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;

}

// Redireciona confrome o perfil
switch ($_SESSION["perfil"]) {

    case "Administrador";
    header("Location: admin/dashboard.php");
    break;

    case "Recepcionista";
    header("Location: recepcao/dashboard.php");
    break;

    case "Medico";
    header("Location: medico/dashboard.php");
    break;


default:
    // Perfil inválido
    session_destroy();
    header("Location: login.php");
    break;

}

exit;
