<?php

/*
|--------------------------------------------------------------------------
| Ação: excluir médico
|--------------------------------------------------------------------------
| Objetivo:
| Desativar um médico sem apagar seu histórico do banco.
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_admin.php";
require_once "../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Verifica o método da requisição
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    header("Location: ../../templates/admin/medicos/index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica o ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    $_SESSION["erro"] = "Médico inválido.";

    header("Location: ../../templates/admin/medicos/index.php");
    exit;
}


$medico_id = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| Verifica se o médico existe
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM medicos
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $medico_id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Médico não encontrado.";

    header("Location: ../../templates/admin/medicos/index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Desativa o médico
|--------------------------------------------------------------------------
*/

$sql = "UPDATE medicos
        SET ativo = 0
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $medico_id
);

$stmt->execute();


/*
|--------------------------------------------------------------------------
| Mensagem de sucesso
|--------------------------------------------------------------------------
*/

$_SESSION["sucesso"] = "Médico desativado com sucesso.";


header("Location: ../../templates/admin/medicos/index.php");
exit;