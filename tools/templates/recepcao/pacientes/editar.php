<?php

/*
|--------------------------------------------------------------------------
| Página: editar paciente
|--------------------------------------------------------------------------
| Objetivo:
| Exibir os dados de um paciente para edição.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_recepcao.php";
require_once "../../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Valida o ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    $_SESSION["erro"] = "Paciente inválido.";

    header("Location: index.php");
    exit;
}


$id = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| Busca o paciente
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            id,
            nome,
            cpf,
            data_nascimento,
            sexo,
            telefone,
            email,
            endereco,
            convenio,
            tipo_sanguineo,
            alergias,
            observacoes,
            ativo

        FROM pacientes

        WHERE id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Paciente não encontrado.";

    header("Location: index.php");
    exit;
}


$paciente = $resultado->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Editar Paciente</title>

</head>

<body>

<h1>Editar Paciente</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color: red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<form
    action="../../../actions/pacientes/editar.php"
    method="POST"
>


    <input
        type="hidden"
        name="id"
        value="<?= $paciente["id"]; ?>"
    >


    <label for="nome">
        Nome:
    </label>

    <br>

    <input
        type="text"
        id="nome"
        name="nome"
        value="<?= htmlspecialchars($paciente["nome"]); ?>"
        maxlength="100"
        required
    >

    <br><br>


    <label for="cpf">
        CPF:
    </label>

    <br>

    <input
        type="text"
        id="cpf"
        name="cpf"
        value="<?= htmlspecialchars($paciente["cpf"]); ?>"
        maxlength="14"
        required
    >

    <br><br>


    <label for="data_nascimento">
        Data de nascimento:
    </label>

    <br>

    <input
        type="date"
        id="data_nascimento"
        name="data_nascimento"
        value="<?= htmlspecialchars($paciente["data_nascimento"]); ?>"
        required
    >

    <br><br>


    <label for="sexo">
        Sexo:
    </label>

    <br>

    <select
        id="sexo"
        name="sexo"
        required
    >

        <option value="">
            Selecione
        </option>

        <option
            value="Masculino"
            <?= $paciente["sexo"] === "Masculino"
                ? "selected"
                : ""; ?>
        >
            Masculino
        </option>

        <option
            value="Feminino"
            <?= $paciente["sexo"] === "Feminino"
                ? "selected"
                : ""; ?>
        >
            Feminino
        </option>

        <option
            value="Outro"
            <?= $paciente["sexo"] === "Outro"
                ? "selected"
                : ""; ?>
        >
            Outro
        </option>

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
        value="<?= htmlspecialchars($paciente["telefone"] ?? ""); ?>"
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
        value="<?= htmlspecialchars($paciente["email"] ?? ""); ?>"
        maxlength="150"
    >

    <br><br>


    <label for="endereco">
        Endereço:
    </label>

    <br>

    <input
        type="text"
        id="endereco"
        name="endereco"
        value="<?= htmlspecialchars($paciente["endereco"] ?? ""); ?>"
        maxlength="255"
    >

    <br><br>


    <label for="convenio">
        Convênio:
    </label>

    <br>

    <input
        type="text"
        id="convenio"
        name="convenio"
        value="<?= htmlspecialchars($paciente["convenio"] ?? ""); ?>"
        maxlength="100"
    >

    <br><br>


    <label for="tipo_sanguineo">
        Tipo sanguíneo:
    </label>

    <br>

    <input
        type="text"
        id="tipo_sanguineo"
        name="tipo_sanguineo"
        value="<?= htmlspecialchars($paciente["tipo_sanguineo"] ?? ""); ?>"
        maxlength="5"
    >

    <br><br>


    <label for="alergias">
        Alergias:
    </label>

    <br>

    <textarea
        id="alergias"
        name="alergias"
        rows="4"
        cols="40"
    ><?= htmlspecialchars($paciente["alergias"] ?? ""); ?></textarea>

    <br><br>


    <label for="observacoes">
        Observações:
    </label>

    <br>

    <textarea
        id="observacoes"
        name="observacoes"
        rows="4"
        cols="40"
    ><?= htmlspecialchars($paciente["observacoes"] ?? ""); ?></textarea>

    <br><br>


    <label for="ativo">
        Status:
    </label>

    <br>

    <select
        id="ativo"
        name="ativo"
        required
    >

        <option
            value="1"
            <?= $paciente["ativo"] == 1
                ? "selected"
                : ""; ?>
        >
            Ativo
        </option>

        <option
            value="0"
            <?= $paciente["ativo"] == 0
                ? "selected"
                : ""; ?>
        >
            Inativo
        </option>

    </select>

    <br><br>


    <button type="submit">
        Salvar alterações
    </button>

</form>


<br>


<a href="index.php">
    Voltar
</a>

</body>

</html>