<?php

/*
|--------------------------------------------------------------------------
| Página: consultar médicos - administrador
|--------------------------------------------------------------------------
*/



require_once "../../../includes/verificar_admin.php";
require_once "../../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Busca os médicos
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            m.id,
            u.nome,
            u.email,
            m.crm_numero,
            m.crm_uf,
            m.telefone,
            m.ativo,

            GROUP_CONCAT(
                e.nome
                ORDER BY e.nome
                SEPARATOR ', '
            ) AS especialidades

        FROM medicos m

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        LEFT JOIN medicos_especialidades me
            ON me.medico_id = m.id

        LEFT JOIN especialidades e
            ON e.id = me.especialidade_id

        GROUP BY
            m.id,
            u.nome,
            u.email,
            m.crm_numero,
            m.crm_uf,
            m.telefone,
            m.ativo

        ORDER BY u.nome ASC";


$stmt = $conn->prepare($sql);

$stmt->execute();

$resultado = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Médicos</title>

</head>

<body>

<h1>Médicos Cadastrados</h1>


<?php

if (isset($_SESSION["sucesso"])) {

    echo "<p style='color: green;'>"
        . htmlspecialchars($_SESSION["sucesso"])
        . "</p>";

    unset($_SESSION["sucesso"]);
}


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

            <th>CRM</th>

            <th>Especialidade</th>

            <th>Telefone</th>

            <th>E-mail</th>

            <th>Status</th>

            <th>Ações</th>

        </tr>

    </thead>


    <tbody>

    <?php if ($resultado->num_rows > 0): ?>

        <?php while ($medico = $resultado->fetch_assoc()): ?>

            <tr>

                <td>
                    <?= $medico["id"]; ?>
                </td>

                <td>
                    <?= htmlspecialchars($medico["nome"]); ?>
                </td>

                <td>
                    <?= htmlspecialchars($medico["crm_numero"]); ?>
                    /
                    <?= htmlspecialchars($medico["crm_uf"]); ?>
                </td>

                <td>
                    <?= htmlspecialchars(
                        $medico["especialidades"] ?? "Não informada"
                    ); ?>
                </td>

                <td>
                    <?= htmlspecialchars($medico["telefone"] ?? ""); ?>
                </td>

                <td>
                    <?= htmlspecialchars($medico["email"]); ?>
                </td>

                <td>

                    <?php if ($medico["ativo"] == 1): ?>

                        Ativo

                    <?php else: ?>

                        Inativo

                    <?php endif; ?>

                </td>

                <td>

                    <a href="editar.php?id=<?= $medico["id"]; ?>">
                        Editar
                    </a>

                    |

                    <a href="../../../actions/medicos/excluir.php?id=<?= $medico["id"]; ?>">
                        Excluir
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    <?php else: ?>

        <tr>

            <td colspan="8">
                Nenhum médico cadastrado.
            </td>

        </tr>

    <?php endif; ?>

    </tbody>

</table>


<br>

<a href="cadastrar.php">
    Cadastrar novo médico
</a>

</body>

</html>