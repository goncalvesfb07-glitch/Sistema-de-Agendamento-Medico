<?php

/*
|--------------------------------------------------------------------------
| Ação: editar usuário
|--------------------------------------------------------------------------
| Objetivo:
| Atualizar os dados de um usuário.
|--------------------------------------------------------------------------
*/

session_start();

require_once "../../config/conexao.php";
require_once "../../includes/verificar_admin.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../../templates/admin/usuarios/index.php");
    exit;

}

$id = (int) $_POST["id"];

$nome = trim($_POST["nome"]);

$email = trim($_POST["email"]);

$perfil = trim($_POST["perfil"]);

if (empty($nome) || empty($email) || empty($perfil)) {
    
    $_SESSION["erro"] = "Todos os campos são obrigatórios.";

    header("Location: ../../templates/admin/usuarios/editar.php?id=" . $id);
    exit;

}

$sql = "SELECT
            id
            FROM usuarios
            WHERE email = ?
            AND id != ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("si", $email, $id);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Este Email já está cadastrado.";

    header("Location: ../../templates/admin/usuarios/editar.php?id=" . $id);
    exit;

}

$sql = "UPDATE usuarios
        SET
            nome = ?,
            email = ?,
            perfil = ?
            WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssi",
    $nome,
    $email,
    $perfil,
    $id
);

$stmt->execute();

$_SESSION["sucesso"] = "Usuário atualizado com sucesso.";

header("Location: ../../templates/admin/usuarios/index.php");
exit;
