<?php

/*
|--------------------------------------------------------------------------
| Ação: editar especialidade
|--------------------------------------------------------------------------
| Objetivo:
| Atualizar os dados de uma especialidade.
|--------------------------------------------------------------------------
*/

require_once "../../config/conexao.php";
require_once "../../includes/verificar_admin.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../../templates/admin/especialidades/index.php");
    exit;

}


$id = (int) ($_POST["id"] ?? 0);

$nome = trim($_POST["nome"] ?? "");

$descricao = trim($_POST["descricao"] ?? "");

$ativo = isset($_POST["ativo"]) ? (int) $_POST["ativo"] : 0;


if ($id <= 0) {

    $_SESSION["erro"] = "Especialidade inválida.";

    header("Location: ../../templates/admin/especialidades/index.php");
    exit;
}


if (empty($nome)) {

    $_SESSION["erro"] = "O nome da especialidade é obrigatório.";

    header("Location: ../../templates/admin/especialidades/editar.php?id=" . $id);
    exit;
}


if ($ativo !== 0 && $ativo !== 1) {

    $_SESSION["erro"] = "Status da especialidade inválido.";

    header("Location: ../../templates/admin/especialidades/editar.php?id=" . $id);
    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica se o nome já pertence a outra especialidade
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM especialidades
        WHERE nome = ?
        AND id != ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("si", $nome, $id);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Esta especialidade já está cadastrada.";

    header("Location: ../../templates/admin/especialidades/editar.php?id=" . $id);
    exit;
}


/*
|--------------------------------------------------------------------------
| Atualiza a especialidade
|--------------------------------------------------------------------------
*/

$sql = "UPDATE especialidades
        SET
            nome = ?,
            descricao = ?,
            ativo = ?,
            updated_at = CURRENT_TIMESTAMP
        WHERE id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssii",
    $nome,
    $descricao,
    $ativo,
    $id
);

$stmt->execute();


$_SESSION["sucesso"] = "Especialidade atualizada com sucesso.";

header("Location: ../../templates/admin/especialidades/index.php");
exit;