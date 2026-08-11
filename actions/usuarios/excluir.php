<?php

/*
|--------------------------------------------------------------------------
| Ação: excluir usuário
|--------------------------------------------------------------------------
| Objetivo:
| Excluir um usuário do banco de dados.
|--------------------------------------------------------------------------
*/

session_start();

require_once "../../config/conexao.php";
require_once "../../includes/verificar_admin.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    HEADER("Location: ../../templates/admin/usuarios/index.php");
    exit;

}


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    $_SESSION["erro"] = "Usuário inválido.";

    header("Location: ../../templates/admin/usuarios/index.php");
    exit;


}


$id = (int) $_GET["id"];


$sql = "DELETE FROM usuarios WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

if ($stmt->affected_rows === 0) {

    $_SESSION["erro"] = "Usuário não encontrado.";

    header("Location: ../../templates/admin/usuarios/index.php");
    exit;
}


$_SESSION["sucesso"] = "Usuário excluído com sucesso.";
header("Location: ../../templates/admin/usuarios/index.php");
exit;

