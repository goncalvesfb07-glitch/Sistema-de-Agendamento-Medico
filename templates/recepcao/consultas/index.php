<?php

require_once "../../../includes/verificar_recepcao.php";
require_once "../../../config/conexao.php";


$data = $_GET["data"] ?? "";

$medico_id = filter_input(
    INPUT_GET,
    "medico_id",
    FILTER_VALIDATE_INT
);


$sql = "SELECT
            c.id,
            c.data_consulta,
            c.horario,
            c.status,
            c.hora_checkin,
            p.nome AS paciente,
            u.nome AS medico

        FROM consultas c

        INNER JOIN pacientes p
            ON p.id = c.paciente_id

        INNER JOIN medicos m
            ON m.id = c.medico_id

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        WHERE 1 = 1";


$parametros = [];

$tipos = "";


if (!empty($data)) {

    $sql .= " AND c.data_consulta = ?";

    $parametros[] = $data;

    $tipos .= "s";
}


if ($medico_id) {

    $sql .= " AND c.medico_id = ?";

    $parametros[] = $medico_id;

    $tipos .= "i";
}


$sql .= "
    ORDER BY
        c.data_consulta ASC,
        c.horario ASC
";


$stmt = $conn->prepare($sql);


if (!empty($parametros)) {

    $stmt->bind_param(
        $tipos,
        ...$parametros
    );
}


$stmt->execute();

$consultas = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Consultas</title>

</head>

<body>

<h1>Consultas</h1>


<form method="GET">

    <label>
        Data:
    </label>

    <input
        type="date"
        name="data"
        value="<?= htmlspecialchars($data); ?>"
    >


    <br><br>


    <label>
        Médico:
    </label>

    <select name="medico_id">

        <option value="">
            Todos
        </option>

        <?php

        $sql_medicos = "
            SELECT
                m.id,
                u.nome

            FROM medicos m

            INNER JOIN usuarios u
                ON u.id = m.usuario_id

            WHERE m.ativo = 1
            AND u.ativo = 1

            ORDER BY u.nome
        ";

        $resultado_medicos =
            $conn->query($sql_medicos);

        ?>

        <?php while (
            $m = $resultado_medicos->fetch_assoc()
        ): ?>

            <option
                value="<?= $m["id"]; ?>"
                <?= $medico_id == $m["id"]
                    ? "selected"
                    : ""; ?>
            >
                <?= htmlspecialchars($m["nome"]); ?>
            </option>

        <?php endwhile; ?>

    </select>


    <br><br>


    <button type="submit">
        Consultar
    </button>

</form>


<br>


<table border="1" cellpadding="8">

    <thead>

        <tr>

            <th>Data</th>

            <th>Horário</th>

            <th>Paciente</th>

            <th>Médico</th>

            <th>Status</th>

            <th>Check-in</th>

            <th>Ações</th>

        </tr>

    </thead>


    <tbody>

    <?php while (
        $consulta = $consultas->fetch_assoc()
    ): ?>

        <tr>

            <td>
                <?= date(
                    "d/m/Y",
                    strtotime($consulta["data_consulta"])
                ); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    substr($consulta["horario"], 0, 5)
                ); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $consulta["paciente"]
                ); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $consulta["medico"]
                ); ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $consulta["status"]
                ); ?>
            </td>

            <td>

                <?php if (
                    $consulta["hora_checkin"]
                ): ?>

                    <?= date(
                        "d/m/Y H:i",
                        strtotime(
                            $consulta["hora_checkin"]
                        )
                    ); ?>

                <?php else: ?>

                    —

                <?php endif; ?>

            </td>

            <td>

                <a
                    href="editar.php?id=<?= $consulta["id"]; ?>"
                >
                    Reagendar
                </a>

                |

                <a
                    href="../../../actions/consultas/cancelar.php?id=<?= $consulta["id"]; ?>"
                    onclick="return confirm('Deseja cancelar esta consulta?');"
                >
                    Cancelar
                </a>

                |

                <?php if (
                    empty($consulta["hora_checkin"]) &&
                    $consulta["status"] === "Agendada"
                ): ?>

                    <a
                        href="../../../actions/consultas/confirmar.php?id=<?= $consulta["id"]; ?>"
                    >
                        Confirmar presença
                    </a>

                <?php endif; ?>

            </td>

        </tr>

    <?php endwhile; ?>

    </tbody>

</table>

</body>
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

</html>