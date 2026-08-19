<?php
/*
|--------------------------------------------------------------------------
| Página: index.php
|--------------------------------------------------------------------------
| Objetivo:
| Exibir a listagem de usuários cadastrados.
|
| Responsabilidades:
| - Verificar se o usuário é administrador.
| - Buscar os usuários cadastrados.
| - Exibir a lista de usuários.
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/../../../includes/verificar_admin.php";
require_once(__DIR__ . "/../../../config/conexao.php");

if (isset($_SESSION["sucesso"])) { ?>

    <div style="
        background:#d4edda;
        color:#155724;
        border:1px solid #c3e6cb;
        padding:12px;
        margin-bottom:20px;
        border-radius:6px;
        font-weight:bold;
    ">
        ✔ <?= $_SESSION["sucesso"]; ?>
    </div>

<?php

unset($_SESSION["sucesso"]);

}

?>

<?php

if (isset($_SESSION["erro"])) { ?>

    <div style="
        background:#f8d7da;
        color:#721c24;
        border:1px solid #f5c6cb;
        padding:12px;
        margin-bottom:20px;
        border-radius:6px;
        font-weight:bold;
    ">
        ✖ <?= $_SESSION["erro"]; ?>
    </div>

<?php

unset($_SESSION["erro"]);

}

?>
<?php
$sql = "SELECT
            id,
            nome,
            email,
            perfil,
            created_at
        FROM usuarios
        ORDER BY nome ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();

$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Usuários</title>

</head>

<body>

<h1>Usuários Cadastrados</h1>

<table border="1" cellpadding="8">

    <tr>

        <th>ID</th>
        <th>Nome</th>
        <th>E-mail</th>
        <th>Perfil</th>
        <th>Data de Cadastro</th>
        <th>Ações</th>


    </tr>
</body>

</html>

<?php while ($usuario = $resultado->fetch_assoc()) : ?>
    <tr>

<td><?= $usuario["id"]; ?></td>

<td><?= $usuario["nome"]; ?></td>

<td><?= $usuario["email"]; ?></td>

<td><?= $usuario["perfil"]; ?></td>

<td><?= $usuario["created_at"]; ?></td>

</tr>

<td>

    <a href="editar.php?id=<?= $usuario["id"]; ?>">
        Editar
    </a>

    <a href="../../../actions/usuarios/excluir.php?id=<?= $usuario["id"]; ?>"
   onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
    Excluir
</a>

</td>

<?php endwhile; ?>

</table>
