<?php

/*
|--------------------------------------------------------------------------
| Ação: editar médico
|--------------------------------------------------------------------------
| Objetivo:
| Atualizar os dados de um médico.
|--------------------------------------------------------------------------
*/

require_once "../../config/conexao.php";
require_once "../../includes/verificar_admin.php";


/*
|--------------------------------------------------------------------------
| Verifica o método da requisição
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../../templates/admin/medicos/index.php");
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

$nome = trim($_POST["nome"] ?? "");

$email = trim($_POST["email"] ?? "");

$crm_numero = trim($_POST["crm_numero"] ?? "");

$crm_uf = strtoupper(trim($_POST["crm_uf"] ?? ""));

$telefone = trim($_POST["telefone"] ?? "");

$ativo = filter_input(
    INPUT_POST,
    "ativo",
    FILTER_VALIDATE_INT
);

$especialidade_id = filter_input(
    INPUT_POST,
    "especialidade_id",
    FILTER_VALIDATE_INT
);


/*
|--------------------------------------------------------------------------
| Validação básica
|--------------------------------------------------------------------------
*/

if (
    !$medico_id ||
    empty($nome) ||
    empty($email) ||
    empty($crm_numero) ||
    empty($crm_uf) ||
    !$especialidade_id ||
    ($ativo !== 0 && $ativo !== 1)
) {

    $_SESSION["erro"] = "Preencha todos os campos obrigatórios.";

    header(
        "Location: ../../templates/admin/medicos/editar.php?id="
        . $medico_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Validação do e-mail
|--------------------------------------------------------------------------
*/

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["erro"] = "Informe um e-mail válido.";

    header(
        "Location: ../../templates/admin/medicos/editar.php?id="
        . $medico_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Busca o usuario_id do médico
|--------------------------------------------------------------------------
*/

$sql = "SELECT usuario_id
        FROM medicos
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $medico_id);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Médico não encontrado.";

    header(
        "Location: ../../templates/admin/medicos/index.php"
    );

    exit;
}


$medico = $resultado->fetch_assoc();

$usuario_id = $medico["usuario_id"];


/*
|--------------------------------------------------------------------------
| Verifica se o e-mail já pertence a outro usuário
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM usuarios
        WHERE email = ?
        AND id != ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "si",
    $email,
    $usuario_id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Este e-mail já está cadastrado.";

    header(
        "Location: ../../templates/admin/medicos/editar.php?id="
        . $medico_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica se o CRM já pertence a outro médico
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM medicos
        WHERE crm_numero = ?
        AND crm_uf = ?
        AND id != ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssi",
    $crm_numero,
    $crm_uf,
    $medico_id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Este CRM já está cadastrado.";

    header(
        "Location: ../../templates/admin/medicos/editar.php?id="
        . $medico_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica se a especialidade existe e está ativa
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM especialidades
        WHERE id = ?
        AND ativo = 1";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $especialidade_id
);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "Especialidade inválida.";

    header(
        "Location: ../../templates/admin/medicos/editar.php?id="
        . $medico_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Inicia a transação
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();


try {

    /*
    |--------------------------------------------------------------------------
    | Atualiza usuario
    |--------------------------------------------------------------------------
    */

    $sql = "UPDATE usuarios
            SET
                nome = ?,
                email = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssi",
        $nome,
        $email,
        $usuario_id
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | Atualiza medico
    |--------------------------------------------------------------------------
    */

    $sql = "UPDATE medicos
            SET
                crm_numero = ?,
                crm_uf = ?,
                telefone = ?,
                ativo = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssii",
        $crm_numero,
        $crm_uf,
        $telefone,
        $ativo,
        $medico_id
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | Atualiza especialidade
    |--------------------------------------------------------------------------
    */

    $sql = "UPDATE medicos_especialidades
            SET especialidade_id = ?
            WHERE medico_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $especialidade_id,
        $medico_id
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | Confirma todas as alterações
    |--------------------------------------------------------------------------
    */

    $conn->commit();


    $_SESSION["sucesso"] = "Médico atualizado com sucesso.";


    header(
        "Location: ../../templates/admin/medicos/index.php"
    );

    exit;


} catch (Exception $e) {

    /*
    |--------------------------------------------------------------------------
    | Desfaz alterações em caso de erro
    |--------------------------------------------------------------------------
    */

    $conn->rollback();


    $_SESSION["erro"] = "Erro ao atualizar o médico.";


    header(
        "Location: ../../templates/admin/medicos/editar.php?id="
        . $medico_id
    );

    exit;
}