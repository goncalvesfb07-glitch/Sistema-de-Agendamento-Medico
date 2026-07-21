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

require_once "../../includes/verificar_admin.php";
require_once "../../config/conexao.php";

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

<?php endwhile; ?>

</table>
