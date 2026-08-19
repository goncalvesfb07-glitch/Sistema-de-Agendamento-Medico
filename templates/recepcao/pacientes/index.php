<?php

/*
|--------------------------------------------------------------------------
| Página: consultar pacientes
|--------------------------------------------------------------------------
| Objetivo:
| Exibir os pacientes cadastrados para a recepção.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_recepcao.php";
require_once "../../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Busca os pacientes
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            id,
            nome,
            cpf,
            data_nascimento,
            telefone,
            email,
            endereco,
            convenio,
            ativo

        FROM pacientes

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

    <title>Pacientes</title>

</head>

<body>

<h1>Pacientes Cadastrados</h1>


<?php

/*
|--------------------------------------------------------------------------
| Mensagem de sucesso
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["sucesso"])) {

    echo "<p style='color: green;'>"
        . htmlspecialchars($_SESSION["sucesso"])
        . "</p>";

    unset($_SESSION["sucesso"]);
}


/*
|--------------------------------------------------------------------------
| Mensagem de erro
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["erro"])) {

    echo "<p style='color: red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<table border="1" cellpadding="8">

    <thead>

        <tr>

            <th>ID</th>

            <th>Nome</th>

            <th>CPF</th>

            <th>Data de nascimento</th>

            <th>Telefone</th>

            <th>E-mail</th>

            <th>Convênio</th>

            <th>Status</th>

            <th>Ações</th>

        </tr>

    </thead>


    <tbody>

    <?php if ($resultado->num_rows > 0): ?>

        <?php while ($paciente = $resultado->fetch_assoc()): ?>

            <tr>

                <td>
                    <?= $paciente["id"]; ?>
                </td>

                <td>
                    <?= htmlspecialchars($paciente["nome"]); ?>
                </td>

                <td>
                    <?= htmlspecialchars($paciente["cpf"]); ?>
                </td>

                <td>
                    <?= date(
                        "d/m/Y",
                        strtotime($paciente["data_nascimento"])
                    ); ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $paciente["telefone"] ?? ""
                    ); ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $paciente["email"] ?? ""
                    ); ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $paciente["convenio"] ?? "Particular"
                    ); ?>
                </td>

                <td>

                    <?php if ($paciente["ativo"] == 1): ?>

                        Ativo

                    <?php else: ?>

                        Inativo

                    <?php endif; ?>

                </td>

                <td>

                    <a href="editar.php?id=<?= $paciente["id"]; ?>">
                        Editar
                    </a>

                    |

                    <a href="visualizar.php?id=<?= $paciente["id"]; ?>">
                        Visualizar
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    <?php else: ?>

        <tr>

            <td colspan="9">
                Nenhum paciente cadastrado.
            </td>

        </tr>

    <?php endif; ?>

    </tbody>

</table>


<br>


<a href="cadastrar.php">
    Cadastrar novo paciente
</a>

</body>

</html>