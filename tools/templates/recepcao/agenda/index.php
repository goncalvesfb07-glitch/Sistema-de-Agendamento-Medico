<?php

/*
|--------------------------------------------------------------------------
| Página: consultar agenda
|--------------------------------------------------------------------------
| Objetivo:
| Permitir que a recepção consulte os horários disponíveis de um médico.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_recepcao.php";
require_once "../../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Busca os médicos ativos
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            m.id,
            u.nome

        FROM medicos m

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        WHERE m.ativo = 1
        AND u.ativo = 1

        ORDER BY u.nome ASC";


$stmt = $conn->prepare($sql);

$stmt->execute();

$medicos = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| Recebe os filtros
|--------------------------------------------------------------------------
*/

$medico_id = filter_input(
    INPUT_GET,
    "medico_id",
    FILTER_VALIDATE_INT
);

$data = $_GET["data"] ?? "";


/*
|--------------------------------------------------------------------------
| Variáveis da agenda
|--------------------------------------------------------------------------
*/

$horarios_agenda = [];

$medico_selecionado = null;


/*
|--------------------------------------------------------------------------
| Só consulta a agenda quando médico e data forem informados
|--------------------------------------------------------------------------
*/

if ($medico_id && !empty($data)) {


    /*
    |--------------------------------------------------------------------------
    | Valida a data
    |--------------------------------------------------------------------------
    */

    $data_objeto = DateTime::createFromFormat(
        "Y-m-d",
        $data
    );


    if (
        !$data_objeto ||
        $data_objeto->format("Y-m-d") !== $data
    ) {

        $_SESSION["erro"] = "Data inválida.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | Impede consulta para data passada
        |--------------------------------------------------------------------------
        */

        $hoje = new DateTime();

        $data_consulta = new DateTime($data);


        if ($data_consulta < $hoje) {

            $_SESSION["erro"] =
                "Não é possível consultar uma data passada.";

        } else {


            /*
            |--------------------------------------------------------------------------
            | Descobre o dia da semana
            |--------------------------------------------------------------------------
            */

            $dia_semana = (int) $data_consulta->format("w");


            /*
            |--------------------------------------------------------------------------
            | Busca o horário de atendimento do médico
            |--------------------------------------------------------------------------
            */

            $sql = "SELECT
                        id,
                        hora_inicio,
                        hora_fim,
                        intervalo_minutos

                    FROM horarios

                    WHERE medico_id = ?
                    AND dia_semana = ?
                    AND ativo = 1

                    ORDER BY hora_inicio ASC";


            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "ii",
                $medico_id,
                $dia_semana
            );

            $stmt->execute();

            $resultado_horarios = $stmt->get_result();


            /*
            |--------------------------------------------------------------------------
            | Busca consultas da data escolhida
            |--------------------------------------------------------------------------
            */

            $sql = "SELECT
                        horario,
                        status

                    FROM consultas

                    WHERE medico_id = ?
                    AND data_consulta = ?

                    AND status IN (
                        'Agendada',
                        'Em Andamento'
                    )";


            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "is",
                $medico_id,
                $data
            );

            $stmt->execute();

            $resultado_consultas = $stmt->get_result();


            /*
            |--------------------------------------------------------------------------
            | Guarda os horários ocupados
            |--------------------------------------------------------------------------
            */

            $horarios_ocupados = [];


            while (
                $consulta = $resultado_consultas->fetch_assoc()
            ) {

                $horario = substr(
                    $consulta["horario"],
                    0,
                    5
                );

                $horarios_ocupados[$horario] =
                    $consulta["status"];
            }


            /*
            |--------------------------------------------------------------------------
            | Gera os horários disponíveis
            |--------------------------------------------------------------------------
            */

            while (
                $faixa = $resultado_horarios->fetch_assoc()
            ) {


                $inicio = new DateTime(
                    $data . " " . $faixa["hora_inicio"]
                );


                $fim = new DateTime(
                    $data . " " . $faixa["hora_fim"]
                );


                $intervalo = (int)
                    $faixa["intervalo_minutos"];


                while ($inicio < $fim) {

                    $horario = $inicio->format("H:i");


                    /*
                    |--------------------------------------------------------------------------
                    | Não cria horário que ultrapasse o fim
                    |--------------------------------------------------------------------------
                    */

                    $proximo = clone $inicio;

                    $proximo->modify(
                        "+" . $intervalo . " minutes"
                    );


                    if ($proximo > $fim) {
                        break;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Verifica se está ocupado
                    |--------------------------------------------------------------------------
                    */

                    if (
                        isset(
                            $horarios_ocupados[$horario]
                        )
                    ) {

                        $status = "Ocupado";

                        $status_consulta =
                            $horarios_ocupados[$horario];

                    } else {

                        $status = "Disponível";

                        $status_consulta = null;
                    }


                    $horarios_agenda[] = [

                        "horario" => $horario,

                        "status" => $status,

                        "status_consulta" =>
                            $status_consulta

                    ];


                    $inicio = $proximo;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Busca o nome do médico selecionado
            |--------------------------------------------------------------------------
            */

            $sql = "SELECT
                        u.nome

                    FROM medicos m

                    INNER JOIN usuarios u
                        ON u.id = m.usuario_id

                    WHERE m.id = ?";


            $stmt = $conn->prepare($sql);

            $stmt->bind_param(
                "i",
                $medico_id
            );

            $stmt->execute();

            $resultado_medico =
                $stmt->get_result();


            if (
                $resultado_medico->num_rows > 0
            ) {

                $medico_selecionado =
                    $resultado_medico->fetch_assoc();
            }
        }
    }
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Agenda Médica</title>

</head>

<body>

<h1>Consultar Agenda</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color: red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<form method="GET">


    <label for="medico_id">
        Médico:
    </label>

    <br>

    <select
        name="medico_id"
        id="medico_id"
        required
    >

        <option value="">
            Selecione o médico
        </option>


        <?php while ($medico = $medicos->fetch_assoc()): ?>

            <option
                value="<?= $medico["id"]; ?>"
                <?= (
                    $medico_id == $medico["id"]
                )
                    ? "selected"
                    : ""; ?>
            >

                <?= htmlspecialchars(
                    $medico["nome"]
                ); ?>

            </option>

        <?php endwhile; ?>

    </select>


    <br><br>


    <label for="data">
        Data:
    </label>

    <br>

    <input
        type="date"
        name="data"
        id="data"
        value="<?= htmlspecialchars($data); ?>"
        min="<?= date("Y-m-d"); ?>"
        required
    >


    <br><br>


    <button type="submit">
        Consultar
    </button>

</form>


<?php if ($medico_selecionado): ?>

    <hr>

    <h2>
        Agenda de
        <?= htmlspecialchars(
            $medico_selecionado["nome"]
        ); ?>
    </h2>

    <p>

        Data:
        <?= date(
            "d/m/Y",
            strtotime($data)
        ); ?>

    </p>


    <?php if (count($horarios_agenda) > 0): ?>

        <table border="1" cellpadding="8">

            <thead>

                <tr>
                <th>Horário</th>
                <th>Status</th>
                <th>Ação</th>
                </tr>

            </thead>


            <tbody>

            <?php foreach (
    $horarios_agenda
    as $horario
): ?>

    <tr>

        <td>

            <?= htmlspecialchars(
                $horario["horario"]
            ); ?>

        </td>


        <td>

            <?php if (
                $horario["status"]
                === "Disponível"
            ): ?>

                Disponível

            <?php else: ?>

                Ocupado

                <?php if (
                    $horario["status_consulta"]
                ): ?>

                    -
                    <?= htmlspecialchars(
                        $horario["status_consulta"]
                    ); ?>

                <?php endif; ?>

            <?php endif; ?>

        </td>


        <td>

            <?php if (
                $horario["status"]
                === "Disponível"
            ): ?>

                <button
                    type="button"
                    onclick="selecionarHorario(
                        <?= $medico_id; ?>,
                        '<?= htmlspecialchars($data); ?>',
                        '<?= htmlspecialchars($horario["horario"]); ?>'
                    )"
                >
                    Selecionar
                </button>

            <?php else: ?>

                —

            <?php endif; ?>

        </td>

    </tr>

<?php endforeach; ?>
            </tbody>

        </table>

    <?php else: ?>

        <p>
            Nenhum horário de atendimento cadastrado
            para este médico nesta data.
        </p>

    <?php endif; ?>

<?php endif; ?>


</body>

</html>