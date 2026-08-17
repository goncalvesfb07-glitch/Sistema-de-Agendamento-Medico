<?php

/*
|--------------------------------------------------------------------------
| Ação: cadastrar paciente
|--------------------------------------------------------------------------
| Objetivo:
| Cadastrar um novo paciente no banco de dados.
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

    header("Location: ../../templates/recepcao/pacientes/cadastrar.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Recebe os dados do formulário
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| Validação dos campos obrigatórios
|--------------------------------------------------------------------------
*/

if (
    empty($nome) ||
    empty($cpf) ||
    empty($data_nascimento) ||
    empty($sexo)
) {

    $_SESSION["erro"] = "Preencha todos os campos obrigatórios.";

    header(
        "Location: ../../templates/recepcao/pacientes/cadastrar.php"
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
        "Location: ../../templates/recepcao/pacientes/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Formata o CPF para armazenamento
|--------------------------------------------------------------------------
*/

$cpf = substr($cpf, 0, 3) . "."
     . substr($cpf, 3, 3) . "."
     . substr($cpf, 6, 3) . "-"
     . substr($cpf, 9, 2);


/*
|--------------------------------------------------------------------------
| Valida o e-mail, caso informado
|--------------------------------------------------------------------------
*/

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["erro"] = "Informe um e-mail válido.";

    header(
        "Location: ../../templates/recepcao/pacientes/cadastrar.php"
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
        WHERE cpf = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $cpf
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Este CPF já está cadastrado.";

    header(
        "Location: ../../templates/recepcao/pacientes/cadastrar.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Insere o paciente
|--------------------------------------------------------------------------
*/

$sql = "INSERT INTO pacientes (
            nome,
            cpf,
            data_nascimento,
            sexo,
            telefone,
            email,
            endereco,
            convenio,
            tipo_sanguineo,
            alergias,
            observacoes
        )

        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


$stmt = $conn->prepare($sql);


$stmt->bind_param(
    "sssssssssss",
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
    $observacoes
);


if ($stmt->execute()) {

    $_SESSION["sucesso"] = "Paciente cadastrado com sucesso.";

    header(
        "Location: ../../templates/recepcao/pacientes/index.php"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Erro no cadastro
|--------------------------------------------------------------------------
*/

$_SESSION["erro"] = "Não foi possível cadastrar o paciente.";

header(
    "Location: ../../templates/recepcao/pacientes/cadastrar.php"
);

exit;