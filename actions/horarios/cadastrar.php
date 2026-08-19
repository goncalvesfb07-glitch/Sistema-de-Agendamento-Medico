<?php

/*
|--------------------------------------------------------------------------
| Ação: cadastrar horário
|--------------------------------------------------------------------------
| Objetivo:
| Cadastrar uma disponibilidade de atendimento para um médico.
|
| Regras:
| - Médico deve existir e estar ativo.
| - Dia da semana deve ser válido.
| - Hora final deve ser maior que a hora inicial.
| - Intervalo deve ser positivo.
| - Não pode existir sobreposição de horários para o mesmo médico.
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_admin.php";
require_once "../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Verifica o método da requisição
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header(
        "Location: ../../templates/admin/horarios/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Recebe os dados
|--------------------------------------------------------------------------
*/

$medico_id = filter_input(
    INPUT_POST,
    "medico_id",
    FILTER_VALIDATE_INT
);

$dia_semana = filter_input(
    INPUT_POST,
    "dia_semana",
    FILTER_VALIDATE_INT
);

$hora_inicio = trim($_POST["hora_inicio"] ?? "");

$hora_fim = trim($_POST["hora_fim"] ?? "");

$intervalo_minutos = filter_input(
    INPUT_POST,
    "intervalo_minutos",
    FILTER_VALIDATE_INT
);


/*
|--------------------------------------------------------------------------
| Validação básica
|--------------------------------------------------------------------------
*/

if (
    !$medico_id ||
    $dia_semana === false ||
    $dia_semana < 0 ||
    $dia_semana > 6 ||
    empty($hora_inicio) ||
    empty($hora_fim) ||
    !$intervalo_minutos ||
    $intervalo_minutos <= 0
) {

    $_SESSION["erro"] = "Preencha corretamente todos os campos.";

    header(
        "Location: ../../templates/admin/horarios/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Validação das horas
|--------------------------------------------------------------------------
*/

if ($hora_inicio >= $hora_fim) {

    $_SESSION["erro"] =
        "A hora final deve ser maior que a hora inicial.";

    header(
        "Location: ../../templates/admin/horarios/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica se o médico existe e está ativo
|--------------------------------------------------------------------------
*/

$sql = "SELECT m.id

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
        "Location: ../../templates/admin/horarios/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica conflito de horário
|--------------------------------------------------------------------------
|
| Dois horários entram em conflito quando:
|
| novo início < horário existente
| E
| novo fim > horário existente
|
| Exemplos:
|
| Existente: 08:00 - 12:00
| Novo:      10:00 - 14:00  → conflito
|
| Existente: 08:00 - 12:00
| Novo:      12:00 - 14:00  → permitido
|--------------------------------------------------------------------------
*/

$sql = "SELECT id

        FROM horarios

        WHERE medico_id = ?
        AND dia_semana = ?
        AND ativo = 1

        AND hora_inicio < ?
        AND hora_fim > ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iiss",
    $medico_id,
    $dia_semana,
    $hora_fim,
    $hora_inicio
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] =
        "Este médico já possui um horário de atendimento "
        . "que entra em conflito com o período informado.";

    header(
        "Location: ../../templates/admin/horarios/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Insere o horário
|--------------------------------------------------------------------------
*/

$sql = "INSERT INTO horarios (
            medico_id,
            dia_semana,
            hora_inicio,
            hora_fim,
            intervalo_minutos,
            ativo
        )

        VALUES (?, ?, ?, ?, ?, 1)";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iissi",
    $medico_id,
    $dia_semana,
    $hora_inicio,
    $hora_fim,
    $intervalo_minutos
);


if ($stmt->execute()) {

    $_SESSION["sucesso"] =
        "Horário de atendimento cadastrado com sucesso.";

    header(
        "Location: ../../templates/admin/horarios/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Erro no cadastro
|--------------------------------------------------------------------------
*/

$_SESSION["erro"] =
    "Não foi possível cadastrar o horário.";

header(
    "Location: ../../templates/admin/horarios/cadastrar.php"
);

exit;