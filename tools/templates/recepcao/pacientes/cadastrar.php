<?php

/*
|--------------------------------------------------------------------------
| Página: cadastrar paciente
|--------------------------------------------------------------------------
| Objetivo:
| Permitir que a recepção cadastre novos pacientes.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_recepcao.php";

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastrar Paciente</title>

</head>

<body>

    <h1>Cadastrar Paciente</h1>


    <?php

    if (isset($_SESSION["erro"])) {

        echo "<p style='color: red;'>"
            . htmlspecialchars($_SESSION["erro"])
            . "</p>";

        unset($_SESSION["erro"]);
    }

    ?>


    <form
        action="../../../actions/pacientes/cadastrar.php"
        method="POST"
    >

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


        <label for="cpf">
            CPF:
        </label>

        <br>

        <input
            type="text"
            id="cpf"
            name="cpf"
            maxlength="14"
            placeholder="000.000.000-00"
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

            <option value="Masculino">
                Masculino
            </option>

            <option value="Feminino">
                Feminino
            </option>

            <option value="Outro">
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
        ></textarea>

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
        ></textarea>

        <br><br>


        <button type="submit">
            Cadastrar paciente
        </button>

    </form>


    <br>


    <a href="index.php">
        Voltar
    </a>

</body>

</html>