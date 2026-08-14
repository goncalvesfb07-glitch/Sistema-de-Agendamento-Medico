<?php

/*
|--------------------------------------------------------------------------
| Página: editar especialidade
|--------------------------------------------------------------------------
| Objetivo:
| Exibir os dados de uma especialidade para edição.
|--------------------------------------------------------------------------
*/

require_once "../../../config/conexao.php";
require_once "../../../includes/verificar_admin.php";


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    $_SESSION["erro"] = "Especialidade inválida.";

    header("Location: index.php");
    exit;
}


$id = (int) $_GET["id"];


$sql = "SELECT
            id,
            nome,
            descricao,
            ativo
        FROM especialidades
        WHERE id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Especialidade não encontrada.";

    header("Location: index.php");
    exit;
}


$especialidade = $resultado->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Editar Especialidade</title>

</head>

<body>

<h1>Editar Especialidade</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color:red'>" . $_SESSION["erro"] . "</p>";

    unset($_SESSION["erro"]);

}

?>


<form action="../../../actions/especialidades/editar.php" method="POST">


    <input
        type="hidden"
        name="id"
        value="<?= $especialidade["id"]; ?>"
    >


    <label for="nome">
        Nome da especialidade:
    </label>

    <br>

    <input
        type="text"
        id="nome"
        name="nome"
        value="<?= htmlspecialchars($especialidade["nome"]); ?>"
        maxlength="100"
        required
    >

    <br><br>


    <label for="descricao">
        Descrição:
    </label>

    <br>

    <textarea
        id="descricao"
        name="descricao"
        rows="5"
        cols="40"
    ><?= htmlspecialchars($especialidade["descricao"] ?? ""); ?></textarea>

    <br><br>


    <label for="ativo">
        Status:
    </label>

    <br>

    <select id="ativo" name="ativo">

        <option
            value="1"
            <?= $especialidade["ativo"] == 1 ? "selected" : ""; ?>
        >
            Ativa
        </option>

        <option
            value="0"
            <?= $especialidade["ativo"] == 0 ? "selected" : ""; ?>
        >
            Inativa
        </option>

    </select>

    <br><br>


    <button type="submit">
        Salvar Alterações
    </button>


</form>


<br>


<a href="index.php">
    Voltar para especialidades
</a>

</body>

</html>