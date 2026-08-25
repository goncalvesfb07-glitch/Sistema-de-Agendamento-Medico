<?php

require_once "../../../includes/verificar_recepcao.php";
require_once "../../../config/conexao.php";


$medico_id = filter_input(
    INPUT_GET,
    "medico_id",
    FILTER_VALIDATE_INT
);

$data_consulta = trim(
    $_GET["data_consulta"] ?? ""
);

$horario = trim(
    $_GET["horario"] ?? ""
);


if (
    !$medico_id ||
    empty($data_consulta) ||
    empty($horario)
) {

    $_SESSION["erro"] =
        "Médico, data e horário são obrigatórios.";

    header(
        "Location: ../agenda/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Busca médico
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            m.id,
            u.nome

        FROM medicos m

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        WHERE m.id = ?
        AND m.ativo = 1
        AND u.ativo = 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $medico_id
);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {

    $_SESSION["erro"] =
        "Médico não encontrado.";

    header(
        "Location: ../agenda/index.php"
    );

    exit;
}

$medico = $resultado->fetch_assoc();


/*
|--------------------------------------------------------------------------
| Busca pacientes ativos
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            id,
            nome,
            cpf

        FROM pacientes

        WHERE ativo = 1

        ORDER BY nome ASC";

$stmt = $conn->prepare($sql);

$stmt->execute();

$pacientes = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Agendar Consulta</title>

</head>

<body>

<h1>Agendar Consulta</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color:red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<p>
    <strong>Médico:</strong>
    <?= htmlspecialchars($medico["nome"]); ?>
</p>


<p>
    <strong>Data:</strong>
    <?= date(
        "d/m/Y",
        strtotime($data_consulta)
    ); ?>
</p>


<p>
    <strong>Horário:</strong>
    <?= htmlspecialchars($horario); ?>
</p>


<form
    action="../../../actions/consultas/cadastrar.php"
    method="POST"
>

    <input
        type="hidden"
        name="medico_id"
        value="<?= $medico_id; ?>"
    >

    <input
        type="hidden"
        name="data_consulta"
        value="<?= htmlspecialchars($data_consulta); ?>"
    >

    <input
        type="hidden"
        name="horario"
        value="<?= htmlspecialchars($horario); ?>"
    >


    <label for="paciente_id">
        Paciente:
    </label>

    <br>

    <select
        id="paciente_id"
        name="paciente_id"
        required
    >

        <option value="">
            Selecione o paciente
        </option>

        <?php while ($paciente = $pacientes->fetch_assoc()): ?>

            <option
                value="<?= $paciente["id"]; ?>"
            >
                <?= htmlspecialchars($paciente["nome"]); ?>
                -
                <?= htmlspecialchars($paciente["cpf"]); ?>
            </option>

        <?php endwhile; ?>

    </select>


    <br><br>


    <label for="motivo_consulta">
        Motivo da consulta:
    </label>

    <br>

    <textarea
        id="motivo_consulta"
        name="motivo_consulta"
        rows="5"
        cols="50"
    ></textarea>


    <br><br>


    <button type="submit">
        Agendar Consulta
    </button>

</form>


<br>

<a href="../agenda/index.php">
    Voltar
</a>

</body>

</html>