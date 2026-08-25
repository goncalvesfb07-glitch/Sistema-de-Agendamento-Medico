<?php

/*
|--------------------------------------------------------------------------
| US022 - Reagendar Consulta
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_recepcao.php";
require_once "../../config/conexao.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

$data_consulta = trim(
    $_POST["data_consulta"] ?? ""
);

$horario = trim(
    $_POST["horario"] ?? ""
);


if (
    !$id ||
    empty($data_consulta) ||
    empty($horario)
) {

    $_SESSION["erro"] =
        "Preencha todos os campos.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Busca consulta
|--------------------------------------------------------------------------
*/

$sql = "SELECT *
        FROM consultas
        WHERE id = ?";

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

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
    );

    exit;
}


$consulta = $resultado->fetch_assoc();


if (
    $consulta["status"] === "Cancelada" ||
    $consulta["status"] === "Finalizada"
) {

    $_SESSION["erro"] =
        "Esta consulta não pode ser reagendada.";

    header(
        "Location: ../../templates/recepcao/consultas/index.php"
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

    $_SESSION["erro"] =
        "Data inválida.";

    header(
        "Location: ../../templates/recepcao/consultas/editar.php?id=" . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Impede data passada
|--------------------------------------------------------------------------
*/

$hoje = new DateTime();

$hoje->setTime(0, 0, 0);

$data_objeto->setTime(0, 0, 0);

if ($data_objeto < $hoje) {

    $_SESSION["erro"] =
        "Não é possível reagendar para uma data passada.";

    header(
        "Location: ../../templates/recepcao/consultas/editar.php?id=" . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica horário
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

    $_SESSION["erro"] =
        "Horário inválido.";

    header(
        "Location: ../../templates/recepcao/consultas/editar.php?id=" . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica disponibilidade do médico
|--------------------------------------------------------------------------
*/

$dia_semana = (int) $data_objeto->format("w");

$sql = "SELECT
            hora_inicio,
            hora_fim,
            intervalo_minutos

        FROM horarios

        WHERE medico_id = ?
        AND dia_semana = ?
        AND ativo = 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $consulta["medico_id"],
    $dia_semana
);

$stmt->execute();

$horarios = $stmt->get_result();

$horario_valido = false;


while ($faixa = $horarios->fetch_assoc()) {

    $inicio = new DateTime(
        $data_consulta . " " . $faixa["hora_inicio"]
    );

    $fim = new DateTime(
        $data_consulta . " " . $faixa["hora_fim"]
    );

    $cursor = clone $inicio;

    $intervalo =
        (int) $faixa["intervalo_minutos"];

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

    if ($horario_valido) {
        break;
    }
}


if (!$horario_valido) {

    $_SESSION["erro"] =
        "O horário não está disponível para este médico.";

    header(
        "Location: ../../templates/recepcao/consultas/editar.php?id=" . $id
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
        AND id <> ?
        AND status IN (
            'Agendada',
            'Em Andamento'
        )

        LIMIT 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "issi",
    $consulta["medico_id"],
    $data_consulta,
    $horario,
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] =
        "Este médico já possui uma consulta neste horário.";

    header(
        "Location: ../../templates/recepcao/consultas/editar.php?id=" . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Atualiza consulta
|--------------------------------------------------------------------------
*/

$sql = "UPDATE consultas

        SET
            data_consulta = ?,
            horario = ?,
            updated_at = CURRENT_TIMESTAMP

        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssi",
    $data_consulta,
    $horario,
    $id
);


if ($stmt->execute()) {

    $_SESSION["sucesso"] =
        "Consulta reagendada com sucesso.";

} else {

    $_SESSION["erro"] =
        "Não foi possível reagendar a consulta.";
}


header(
    "Location: ../../templates/recepcao/consultas/index.php"
);

exit;