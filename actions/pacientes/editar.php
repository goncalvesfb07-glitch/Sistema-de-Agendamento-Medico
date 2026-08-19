<?php

/*
|--------------------------------------------------------------------------
| Ação: editar paciente
|--------------------------------------------------------------------------
| Objetivo:
| Atualizar os dados de um paciente.
|--------------------------------------------------------------------------
*/

require_once "../../includes/verificar_recepcao.php";
require_once "../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Verifica o método da requisição
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../../templates/recepcao/pacientes/index.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Recebe os dados
|--------------------------------------------------------------------------
*/

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

$nome = trim($_POST["nome"] ?? "");

$cpf = trim($_POST["cpf"] ?? "");

$data_nascimento = trim($_POST["data_nascimento"] ?? "");

$sexo = trim($_POST["sexo"] ?? "");

$telefone = trim($_POST["telefone"] ?? "");

$email = trim($_POST["email"] ?? "");

$endereco = trim($_POST["endereco"] ?? "");

$convenio = trim($_POST["convenio"] ?? "");

$tipo_sanguineo = trim($_POST["tipo_sanguineo"] ?? "");

$alergias = trim($_POST["alergias"] ?? "");

$observacoes = trim($_POST["observacoes"] ?? "");

$ativo = filter_input(
    INPUT_POST,
    "ativo",
    FILTER_VALIDATE_INT
);


/*
|--------------------------------------------------------------------------
| Validação dos campos obrigatórios
|--------------------------------------------------------------------------
*/

if (
    !$id ||
    empty($nome) ||
    empty($cpf) ||
    empty($data_nascimento) ||
    empty($sexo) ||
    ($ativo !== 0 && $ativo !== 1)
) {

    $_SESSION["erro"] = "Preencha todos os campos obrigatórios.";

    header(
        "Location: ../../templates/recepcao/pacientes/editar.php?id="
        . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Remove caracteres do CPF
|--------------------------------------------------------------------------
*/

$cpf = preg_replace("/[^0-9]/", "", $cpf);


/*
|--------------------------------------------------------------------------
| Valida o CPF
|--------------------------------------------------------------------------
*/

if (strlen($cpf) !== 11) {

    $_SESSION["erro"] = "Informe um CPF válido.";

    header(
        "Location: ../../templates/recepcao/pacientes/editar.php?id="
        . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Formata o CPF
|--------------------------------------------------------------------------
*/

$cpf = substr($cpf, 0, 3) . "."
     . substr($cpf, 3, 3) . "."
     . substr($cpf, 6, 3) . "-"
     . substr($cpf, 9, 2);


/*
|--------------------------------------------------------------------------
| Valida o e-mail
|--------------------------------------------------------------------------
*/

if (
    !empty($email)
    && !filter_var($email, FILTER_VALIDATE_EMAIL)
) {

    $_SESSION["erro"] = "Informe um e-mail válido.";

    header(
        "Location: ../../templates/recepcao/pacientes/editar.php?id="
        . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica se o paciente existe
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM pacientes
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Paciente não encontrado.";

    header(
        "Location: ../../templates/recepcao/pacientes/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica CPF duplicado
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM pacientes
        WHERE cpf = ?
        AND id != ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "si",
    $cpf,
    $id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Este CPF já está cadastrado para outro paciente.";

    header(
        "Location: ../../templates/recepcao/pacientes/editar.php?id="
        . $id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Atualiza o paciente
|--------------------------------------------------------------------------
*/

$sql = "UPDATE pacientes
        SET
            nome = ?,
            cpf = ?,
            data_nascimento = ?,
            sexo = ?,
            telefone = ?,
            email = ?,
            endereco = ?,
            convenio = ?,
            tipo_sanguineo = ?,
            alergias = ?,
            observacoes = ?,
            ativo = ?
        WHERE id = ?";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssssssssii",
    $nome,
    $cpf,
    $data_nascimento,
    $sexo,
    $telefone,
    $email,
    $endereco,
    $convenio,
    $tipo_sanguineo,
    $alergias,
    $observacoes,
    $ativo,
    $id
);


if ($stmt->execute()) {

    $_SESSION["sucesso"] = "Paciente atualizado com sucesso.";

    header(
        "Location: ../../templates/recepcao/pacientes/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Erro na atualização
|--------------------------------------------------------------------------
*/

$_SESSION["erro"] = "Não foi possível atualizar o paciente.";

header(
    "Location: ../../templates/recepcao/pacientes/editar.php?id="
    . $id
);

exit;