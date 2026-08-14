<?php

/*
|--------------------------------------------------------------------------
| Ação: excluir especialidade
|--------------------------------------------------------------------------
| Objetivo:
| Excluir uma especialidade do banco de dados.
|--------------------------------------------------------------------------
*/

require_once "../../config/conexao.php";
require_once "../../includes/verificar_admin.php";


if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    header("Location: ../../templates/admin/especialidades/index.php");
    exit;

}


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    $_SESSION["erro"] = "Especialidade inválida.";

    header("Location: ../../templates/admin/especialidades/index.php");
    exit;
}


$id = (int) $_GET["id"];


$sql = "DELETE FROM especialidades WHERE id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();


if ($stmt->affected_rows === 0) {

    $_SESSION["erro"] = "Especialidade não encontrada.";

    header("Location: ../../templates/admin/especialidades/index.php");
    exit;
}


$_SESSION["sucesso"] = "Especialidade excluída com sucesso.";

header("Location: ../../templates/admin/especialidades/index.php");
exit;