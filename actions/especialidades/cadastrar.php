<?php

/*
|--------------------------------------------------------------------------
| Ação: cadastrar especialidade
|--------------------------------------------------------------------------
| Objetivo:
| Cadastrar uma nova especialidade médica.
|--------------------------------------------------------------------------
*/

require_once "../../config/conexao.php";
require_once "../../includes/verificar_admin.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../../templates/admin/especialidades/cadastrar.php");
    exit;

}


$nome = trim($_POST["nome"] ?? "");

$descricao = trim($_POST["descricao"] ?? "");


if (empty($nome)) {

    $_SESSION["erro"] = "O nome da especialidade é obrigatório.";

    header("Location: ../../templates/admin/especialidades/cadastrar.php");
    exit;

}


$sql = "SELECT id
        FROM especialidades
        WHERE nome = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $nome);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Esta especialidade já está cadastrada.";

    header("Location: ../../templates/admin/especialidades/cadastrar.php");
    exit;

}


$sql = "INSERT INTO especialidades (nome, descricao)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ss",
    $nome,
    $descricao
);

$stmt->execute();


$_SESSION["sucesso"] = "Especialidade cadastrada com sucesso.";

header("Location: ../../templates/admin/especialidades/cadastrar.php");
exit;