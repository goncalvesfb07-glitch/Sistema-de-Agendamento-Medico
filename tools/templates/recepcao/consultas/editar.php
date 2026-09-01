<?php

require_once "../../../includes/verificar_recepcao.php";
require_once "../../../config/conexao.php";


$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    $_SESSION["erro"] =
        "Consulta inválida.";

    header("Location: index.php");

    exit;
}


$sql = "SELECT
            c.*,
            p.nome AS paciente,
            u.nome AS medico

        FROM consultas c

        INNER JOIN pacientes p
            ON p.id = c.paciente_id

        INNER JOIN medicos m
            ON m.id = c.medico_id

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        WHERE c.id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] =
        "Consulta não encontrada.";

    header("Location: index.php");

    exit;
}


$consulta = $resultado->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Reagendar Consulta</title>

</head>

<body>

<h1>Reagendar Consulta</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color:red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<p>
    <strong>Paciente:</strong>
    <?= htmlspecialchars($consulta["paciente"]); ?>
</p>


<p>
    <strong>Médico:</strong>
    <?= htmlspecialchars($consulta["medico"]); ?>
</p>


<form
    action="../../../actions/consultas/editar.php"
    method="POST"
>

    <input
        type="hidden"
        name="id"
        value="<?= $consulta["id"]; ?>"
    >


    <label>
        Nova data:
    </label>

    <br>

    <input
        type="date"
        name="data_consulta"
        value="<?= htmlspecialchars($consulta["data_consulta"]); ?>"
        required
    >


    <br><br>


    <label>
        Novo horário:
    </label>

    <br>

    <input
        type="time"
        name="horario"
        value="<?= htmlspecialchars(
            substr($consulta["horario"], 0, 5)
        ); ?>"
        required
    >


    <br><br>


    <button type="submit">
        Reagendar
    </button>

</form>


<br>

<a href="index.php">
    Voltar
</a>

</body>

</html>