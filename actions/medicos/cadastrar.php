<?php

/*
|--------------------------------------------------------------------------
| Ação: cadastrar médico
|--------------------------------------------------------------------------
| Objetivo:
| Cadastrar um médico no sistema.
|--------------------------------------------------------------------------
*/

require_once "../../config/conexao.php";
require_once "../../includes/verificar_admin.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../../templates/admin/medicos/cadastrar.php");
    exit;

}


/*
|--------------------------------------------------------------------------
| Recebe os dados do formulário
|--------------------------------------------------------------------------
*/

$nome = trim($_POST["nome"] ?? "");

$crm_numero = trim($_POST["crm_numero"] ?? "");

$crm_uf = strtoupper(trim($_POST["crm_uf"] ?? ""));

$especialidade_id = (int) ($_POST["especialidade_id"] ?? 0);

$telefone = trim($_POST["telefone"] ?? "");

$email = trim($_POST["email"] ?? "");


/*
|--------------------------------------------------------------------------
| Validações
|--------------------------------------------------------------------------
*/

if (
    empty($nome) ||
    empty($crm_numero) ||
    empty($crm_uf) ||
    $especialidade_id <= 0 ||
    empty($email)
) {

    $_SESSION["erro"] = "Preencha todos os campos obrigatórios.";

    header("Location: ../../templates/admin/medicos/cadastrar.php");
    exit;
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["erro"] = "Informe um e-mail válido.";

    header("Location: ../../templates/admin/medicos/cadastrar.php");
    exit;
}


if (strlen($crm_uf) !== 2) {

    $_SESSION["erro"] = "A UF do CRM deve possuir 2 caracteres.";

    header("Location: ../../templates/admin/medicos/cadastrar.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica se o e-mail já está cadastrado
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM usuarios
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Este e-mail já está cadastrado.";

    header("Location: ../../templates/admin/medicos/cadastrar.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Verifica se o CRM já está cadastrado
|--------------------------------------------------------------------------
*/

$sql = "SELECT id
        FROM medicos
        WHERE crm_numero = ?
        AND crm_uf = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("ss", $crm_numero, $crm_uf);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows > 0) {

    $_SESSION["erro"] = "Este CRM já está cadastrado.";

    header("Location: ../../templates/admin/medicos/cadastrar.php");
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

$stmt->bind_param("i", $especialidade_id);

$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows === 0) {

    $_SESSION["erro"] = "A especialidade selecionada é inválida ou está inativa.";

    header("Location: ../../templates/admin/medicos/cadastrar.php");
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
    | 1. Cadastra o usuário
    |--------------------------------------------------------------------------
    */

    $senha = password_hash("medico123", PASSWORD_DEFAULT);

    $perfil = "Medico";


    $sql = "INSERT INTO usuarios
                (nome, email, senha, perfil)
            VALUES (?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssss",
        $nome,
        $email,
        $senha,
        $perfil
    );

    $stmt->execute();


    $usuario_id = $conn->insert_id;


    /*
    |--------------------------------------------------------------------------
    | 2. Cadastra o médico
    |--------------------------------------------------------------------------
    */

    $sql = "INSERT INTO medicos
                (usuario_id, crm_numero, crm_uf, telefone)
            VALUES (?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "isss",
        $usuario_id,
        $crm_numero,
        $crm_uf,
        $telefone
    );

    $stmt->execute();


    $medico_id = $conn->insert_id;


    /*
    |--------------------------------------------------------------------------
    | 3. Relaciona o médico com a especialidade
    |--------------------------------------------------------------------------
    */

    $sql = "INSERT INTO medicos_especialidades
                (medico_id, especialidade_id)
            VALUES (?, ?)";


    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $medico_id,
        $especialidade_id
    );

    $stmt->execute();


    /*
    |--------------------------------------------------------------------------
    | Confirma a transação
    |--------------------------------------------------------------------------
    */

    $conn->commit();


    $_SESSION["sucesso"] = "Médico cadastrado com sucesso.";

    header("Location: ../../templates/admin/medicos/index.php");
    exit;


} catch (mysqli_sql_exception $erro) {

    /*
    |--------------------------------------------------------------------------
    | Desfaz tudo caso alguma etapa falhe
    |--------------------------------------------------------------------------
    */

    $conn->rollback();

    $_SESSION["erro"] = "Não foi possível cadastrar o médico.";

    header("Location: ../../templates/admin/medicos/cadastrar.php");
    exit;
}