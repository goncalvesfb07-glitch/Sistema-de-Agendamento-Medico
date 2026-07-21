<?php

/*
|--------------------------------------------------------------------------
| Ação: cadastrar usuário
|--------------------------------------------------------------------------
| Responsável:
| Receber dados do formulário
| Criptografar senha
| Salvar no banco
|--------------------------------------------------------------------------
*/


require_once "../../config/conexao.php";


// Recebendo dados do formulário

$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$perfil = $_POST["perfil"];


// Criptografando senha

$senhaHash = password_hash(
    $senha,
    PASSWORD_DEFAULT
);


// SQL de inserção

$sql = "
INSERT INTO usuarios
(
    nome,
    email,
    senha,
    perfil
)
VALUES
(
    ?,
    ?,
    ?,
    ?
)
";


// Preparando comando

$stmt = $conn->prepare($sql);


// Associando valores

$stmt->bind_param(
    "ssss",
    $nome,
    $email,
    $senhaHash,
    $perfil
);


// Executando

if($stmt->execute()){


    echo "
    Usuário cadastrado com sucesso!
    <br>
    <a href='../../admin/usuarios/cadastrar.php'>
    Cadastrar outro usuário
    </a>
    ";


}else{


    echo "
    Erro ao cadastrar usuário:
    "
    .$conn->error;


}

?>