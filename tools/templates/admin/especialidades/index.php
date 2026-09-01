<?php

/*
|--------------------------------------------------------------------------
| Página: index.php
|--------------------------------------------------------------------------
| Objetivo:
| Listar as especialidades cadastradas.
|--------------------------------------------------------------------------
*/

require_once "../../../config/conexao.php";
require_once "../../../includes/verificar_admin.php";


$sql = "SELECT
            id,
            nome,
            descricao,
            ativo,
            created_at
        FROM especialidades
        ORDER BY nome ASC";


$stmt = $conn->prepare($sql);

$stmt->execute();

$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Especialidades</title>

</head>

<body>

<h1>Especialidades Médicas</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color:red'>" . $_SESSION["erro"] . "</p>";

    unset($_SESSION["erro"]);

}


if (isset($_SESSION["sucesso"])) {

    echo "<p style='color:green'>" . $_SESSION["sucesso"] . "</p>";

    unset($_SESSION["sucesso"]);

}

?>


<a href="cadastrar.php">
    Cadastrar Especialidade
</a>


<br><br>


<table border="1" cellpadding="8">

    <thead>

        <tr>

            <th>ID</th>

            <th>Nome</th>

            <th>Descrição</th>

            <th>Status</th>

            <th>Data de Cadastro</th>

            <th>Ações</th>

        </tr>

    </thead>


    <tbody>

    <?php while ($especialidade = $resultado->fetch_assoc()): ?>

        <tr>

            <td>
                <?= $especialidade["id"]; ?>
            </td>

            <td>
                <?= htmlspecialchars($especialidade["nome"]); ?>
            </td>

            <td>
                <?= htmlspecialchars($especialidade["descricao"] ?? ""); ?>
            </td>

            <td>

                <?php if ($especialidade["ativo"] == 1): ?>

                    Ativa

                <?php else: ?>

                    Inativa

                <?php endif; ?>

            </td>

            <td>
                <?= $especialidade["created_at"]; ?>
            </td>

            <td>

                <a href="editar.php?id=<?= $especialidade["id"]; ?>">
                    Editar
                </a>


    |

<a
    href="../../../actions/especialidades/excluir.php?id=<?= $especialidade["id"]; ?>"
    onclick="return confirm('Tem certeza que deseja excluir esta especialidade?');"
>
    Excluir
</a>

</td>
            </td>

        </tr>

    <?php endwhile; ?>

    </tbody>

</table>

</body>

</html>