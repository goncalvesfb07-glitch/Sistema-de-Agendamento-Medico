<?php

require_once __DIR__ . "/../templates/login.php";

session_start();

if (isset($_SESSION["id"])) {
    header("Location: ../templates/painel-usuario.php");
} else {
    header("Location: ../templates/login.php");

}

exit;
