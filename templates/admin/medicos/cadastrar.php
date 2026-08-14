<?php

/*
|--------------------------------------------------------------------------
| Página: cadastrar médico
|--------------------------------------------------------------------------
| Objetivo:
| Exibir o formulário para cadastro de um médico.
|--------------------------------------------------------------------------
*/

require_once "../../../config/conexao.php";
require_once "../../../includes/verificar_admin.php";


$sql = "SELECT
            id,
            nome
        FROM especialidades
        WHERE ativo = 1
        ORDER BY nome ASC";


$stmt = $conn->prepare($sql);

$stmt->execute();

$resultado_especialidades = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Médico</title>

</head>

<body>

<h1>Cadastrar Médico</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color:red'>" . htmlspecialchars($_SESSION["erro"]) . "</p>";

    unset($_SESSION["erro"]);

}


if (isset($_SESSION["sucesso"])) {

    echo "<p style='color:green'>" . htmlspecialchars($_SESSION["sucesso"]) . "</p>";

    unset($_SESSION["sucesso"]);

}

?>


<form action="../../../actions/medicos/cadastrar.php" method="POST">


    <label for="nome">
        Nome:
    </label>

    <br>

    <input
        type="text"
        id="nome"
        name="nome"
        maxlength="100"
        required
    >

    <br><br>


    <label for="crm_numero">
        Número do CRM:
    </label>

    <br>

    <input
        type="text"
        id="crm_numero"
        name="crm_numero"
        maxlength="20"
        required
    >

    <br><br>


    <label for="crm_uf">
        UF do CRM:
    </label>

    <br>

    <input
        type="text"
        id="crm_uf"
        name="crm_uf"
        maxlength="2"
        required
    >

    <br><br>


    <label for="especialidade_id">
        Especialidade:
    </label>

    <br>

    <select
        id="especialidade_id"
        name="especialidade_id"
        required
    >

        <option value="">
            Selecione uma especialidade
        </option>


        <?php while ($especialidade = $resultado_especialidades->fetch_assoc()): ?>

            <option value="<?= $especialidade["id"]; ?>">

                <?= htmlspecialchars($especialidade["nome"]); ?>

            </option>

        <?php endwhile; ?>

    </select>

    <br><br>


    <label for="telefone">
        Telefone:
    </label>

    <br>

    <input
        type="text"
        id="telefone"
        name="telefone"
        maxlength="20"
    >

    <br><br>


    <label for="email">
        E-mail:
    </label>

    <br>

    <input
        type="email"
        id="email"
        name="email"
        maxlength="150"
        required
    >

    <br><br>


    <button type="submit">
        Cadastrar Médico
    </button>


</form>


<br>


<a href="index.php">
    Voltar para médicos
</a>

</body>

</html>