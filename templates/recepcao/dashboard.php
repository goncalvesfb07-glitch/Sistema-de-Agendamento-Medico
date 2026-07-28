<?php

session_start();

//verifica se o usuário esta logado
if (!isset($_SESSION["id"])) {
    header("Location: ../login.php");
    exit;

}

//verifica se o usuário é administrador
if ($_SESSION["perfil"] !== "Administrador") {
    header("Location: ../painel-usuario.php");
    exit;

}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Recepção</title>
</head>
<body>

    <h1>Dashboard do Recepção</h1>

    <p>Bem-vindo, <strong><?= htmlspecialchars($_SESSION["nome"]) ?></strong>!</p>

    <hr>

    <h2>Menu</h2>

    <ul>
        <li>
            <a href="usuarios/index.php">Gerenciar Usuários</a>
        </li>

        <!-- Futuros módulos -->
        <li>Gerenciar Médicos (Em desenvolvimento)</li>
        <li>Consultas (Em desenvolvimento)</li>
    </ul>

    <hr>

    <a href="../../logout.php">Sair</a>

</body>
</html>
