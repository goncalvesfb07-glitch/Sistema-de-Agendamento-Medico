<?php

/*
|--------------------------------------------------------------------------
| Página: editar médico
|--------------------------------------------------------------------------
| Objetivo:
| Exibir os dados atuais de um médico para edição.
|--------------------------------------------------------------------------
*/


require_once "../../../includes/verificar_admin.php";
require_once "../../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Verifica o ID recebido
|--------------------------------------------------------------------------
*/

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {

    $_SESSION["erro"] = "Médico inválido.";

    header("Location: index.php");
    exit;
}


$medico_id = (int) $_GET["id"];


/*
|--------------------------------------------------------------------------
| Busca os dados do médico
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            m.id,
            m.usuario_id,
            m.crm_numero,
            m.crm_uf,
            m.telefone,
            m.ativo,
            u.nome,
            u.email,
            me.especialidade_id

        FROM medicos m

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        LEFT JOIN medicos_especialidades me
            ON me.medico_id = m.id

        WHERE m.id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $medico_id);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Médico não encontrado.";

    header("Location: index.php");
    exit;
}


$medico = $resultado->fetch_assoc();


/*
|--------------------------------------------------------------------------
| Busca as especialidades ativas
|--------------------------------------------------------------------------
*/

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

    <title>Editar Médico</title>

</head>

<body>

<h1>Editar Médico</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color: red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<form
    action="../../../actions/medicos/editar.php"
    method="POST"
>


    <!-- ID do médico -->

    <input
        type="hidden"
        name="medico_id"
        value="<?= $medico["id"]; ?>"
    >


    <h3>Dados do médico</h3>


    <label for="nome">
        Nome:
    </label>

    <br>

    <input
        type="text"
        id="nome"
        name="nome"
        value="<?= htmlspecialchars($medico["nome"]); ?>"
        maxlength="100"
        required
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
        value="<?= htmlspecialchars($medico["email"]); ?>"
        maxlength="150"
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
        value="<?= htmlspecialchars($medico["crm_numero"]); ?>"
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
        value="<?= htmlspecialchars($medico["crm_uf"]); ?>"
        maxlength="2"
        required
    >

    <br><br>


    <label for="telefone">
        Telefone:
    </label>

    <br>

    <input
        type="text"
        id="telefone"
        name="telefone"
        value="<?= htmlspecialchars($medico["telefone"] ?? ""); ?>"
        maxlength="20"
    >

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
        <?= ($medico["ativo"] == 1) ? "selected" : ""; ?>
    >
        Ativo
    </option>

    <option
        value="0"
        <?= ($medico["ativo"] == 0) ? "selected" : ""; ?>
    >
        Inativo
    </option>

</select>

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

            <option
                value="<?= $especialidade["id"]; ?>"
                <?= (
                    $especialidade["id"] == $medico["especialidade_id"]
                ) ? "selected" : ""; ?>
            >

                <?= htmlspecialchars($especialidade["nome"]); ?>

            </option>

        <?php endwhile; ?>

    </select>

    <br><br>


    <button type="submit">
        Salvar alterações
    </button>


</form>


<br>


<a href="index.php">
    Cancelar
</a>

</body>

</html>