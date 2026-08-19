<?php

/*
|--------------------------------------------------------------------------
| Ação: excluir paciente
|--------------------------------------------------------------------------
| Objetivo:
| Desativar um paciente sem apagar seu histórico do banco.
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_recepcao.php";
require_once "../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Verifica o método da requisição
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "GET") {

    header(
        "Location: ../../templates/recepcao/pacientes/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica o ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    $_SESSION["erro"] = "Paciente inválido.";

    header(
        "Location: ../../templates/recepcao/pacientes/index.php"
    );

    exit;
}


$id = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| Verifica se o paciente existe
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM pacientes
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Paciente não encontrado.";

    header(
        "Location: ../../templates/recepcao/pacientes/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Desativa o paciente
|--------------------------------------------------------------------------
*/

$sql = "UPDATE pacientes
        SET ativo = 0
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();


if ($stmt->affected_rows > 0) {

    $_SESSION["sucesso"] = "Paciente desativado com sucesso.";

} else {

    $_SESSION["erro"] = "Não foi possível desativar o paciente.";
}


/*
|--------------------------------------------------------------------------
| Redireciona para a listagem
|--------------------------------------------------------------------------
*/

header(
    "Location: ../../templates/recepcao/pacientes/index.php"
);

exit;