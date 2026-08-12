<?php

/*
|--------------------------------------------------------------------------
| Página: cadastrar especialidade
|--------------------------------------------------------------------------
| Objetivo:
| Exibir o formulário para cadastro de uma especialidade médica.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_admin.php";

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Especialidade</title>

</head>

<body>

<h1>Cadastrar Especialidade</h1>


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


<form action="../../../actions/especialidades/cadastrar.php" method="POST">


    <label for="nome">
        Nome da especialidade:
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


    <label for="descricao">
        Descrição:
    </label>

    <br>

    <textarea
        id="descricao"
        name="descricao"
        rows="5"
        cols="40"
    ></textarea>

    <br><br>


    <button type="submit">
        Cadastrar
    </button>


</form>

<br>

<a href="index.php">
    Voltar para especialidades
</a>

</body>

</html>