<?php
/*
|--------------------------------------------------------------------------
| Página: editar.php
|--------------------------------------------------------------------------
| Objetivo:
| Exibir o formulário de edição de usuários.
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_admin.php";
require_once "../../config/conexao.php";

if (!isset($_GET["id"])) {

    header("Location: index.php");
    exit;

}

$id =(int) $_GET["id"];
