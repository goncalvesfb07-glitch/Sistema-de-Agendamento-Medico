<?php

/*
|--------------------------------------------------------------------------
| Ação: cadastrar consulta
|--------------------------------------------------------------------------
| US020 - Agendar Consulta
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_recepcao.php";
require_once "../../config/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../../templates/recepcao/agenda/index.php");
    exit;
}

/*
|--------------------------------------------------------------------------
| Recebe os dados
|--------------------------------------------------------------------------
*/

$paciente_id = filter_input(
    INPUT_POST,
    "paciente_id",
    FILTER_VALIDATE_INT
);

$medico_id = filter_input(
    INPUT_POST,
    "medico_id",
    FILTER_VALIDATE_INT
);

$data_consulta = trim($_POST["data_consulta"] ?? "");

$horario = trim($_POST["horario"] ?? "");

$motivo_consulta = trim(
    $_POST["motivo_consulta"] ?? ""
);


/*
|--------------------------------------------------------------------------
| Validação básica
|--------------------------------------------------------------------------
*/

if (
    !$paciente_id ||
    !$medico_id ||
    empty($data_consulta) ||
    empty($horario)
) {

    $_SESSION["erro"] =
        "Preencha todos os campos obrigatórios.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Valida data
|--------------------------------------------------------------------------
*/

$data_objeto = DateTime::createFromFormat(
    "Y-m-d",
    $data_consulta
);

if (
    !$data_objeto ||
    $data_objeto->format("Y-m-d") !== $data_consulta
) {

    $_SESSION["erro"] = "Data inválida.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}

$hoje = new DateTime();

$hoje->setTime(0, 0, 0);

$data_objeto->setTime(0, 0, 0);

if ($data_objeto < $hoje) {

    $_SESSION["erro"] =
        "Não é possível agendar uma consulta para uma data passada.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Valida horário
|--------------------------------------------------------------------------
*/

$horario_objeto = DateTime::createFromFormat(
    "H:i",
    $horario
);

if (
    !$horario_objeto ||
    $horario_objeto->format("H:i") !== $horario
) {

    $_SESSION["erro"] = "Horário inválido.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica paciente
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM pacientes
        WHERE id = ?
        AND ativo = 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $paciente_id
);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {

    $_SESSION["erro"] =
        "Paciente não encontrado ou está inativo.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica médico
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            m.id

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
        "Médico não encontrado ou está inativo.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Descobre o dia da semana
|--------------------------------------------------------------------------
*/

$dia_semana = (int) $data_objeto->format("w");


/*
|--------------------------------------------------------------------------
| Verifica horário de atendimento
|--------------------------------------------------------------------------
*/

$sql = "SELECT
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

$horario_valido = false;

while ($faixa = $resultado_horarios->fetch_assoc()) {

    $inicio = new DateTime(
        $data_consulta . " " . $faixa["hora_inicio"]
    );

    $fim = new DateTime(
        $data_consulta . " " . $faixa["hora_fim"]
    );

    $consulta = new DateTime(
        $data_consulta . " " . $horario
    );

    $intervalo = (int) $faixa["intervalo_minutos"];

    if (
        $consulta >= $inicio &&
        $consulta < $fim
    ) {

        $cursor = clone $inicio;

        while ($cursor < $fim) {

            if (
                $cursor->format("H:i") === $horario
            ) {

                $proximo = clone $cursor;

                $proximo->modify(
                    "+" . $intervalo . " minutes"
                );

                if ($proximo <= $fim) {
                    $horario_valido = true;
                }

                break;
            }

            $cursor->modify(
                "+" . $intervalo . " minutes"
            );
        }
    }

    if ($horario_valido) {
        break;
    }
}

if (!$horario_valido) {

    $_SESSION["erro"] =
        "O horário informado não está disponível na agenda deste médico.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica conflito
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM consultas
        WHERE medico_id = ?
        AND data_consulta = ?
        AND horario = ?
        AND status IN (
            'Agendada',
            'Em Andamento'
        )
        LIMIT 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iss",
    $medico_id,
    $data_consulta,
    $horario
);

$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {

    $_SESSION["erro"] =
        "Este médico já possui uma consulta neste horário.";

    header(
        "Location: ../../templates/recepcao/consultas/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Usuário responsável pelo agendamento
|--------------------------------------------------------------------------
*/

$usuario_id = $_SESSION["id"];


/*
|--------------------------------------------------------------------------
| Cadastra a consulta
|--------------------------------------------------------------------------
*/

$sql = "INSERT INTO consultas (
            paciente_id,
            medico_id,
            usuario_id,
            data_consulta,
            horario,
            motivo_consulta,
            status
        )

        VALUES (?, ?, ?, ?, ?, ?, 'Agendada')";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iiisss",
    $paciente_id,
    $medico_id,
    $usuario_id,
    $data_consulta,
    $horario,
    $motivo_consulta
);

if ($stmt->execute()) {

    $_SESSION["sucesso"] =
        "Consulta agendada com sucesso.";

    header(
        "Location: ../../templates/recepcao/agenda/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Erro
|--------------------------------------------------------------------------
*/

$_SESSION["erro"] =
    "Não foi possível agendar a consulta.";

header(
    "Location: ../../templates/recepcao/consultas/cadastrar.php"
);

exit;