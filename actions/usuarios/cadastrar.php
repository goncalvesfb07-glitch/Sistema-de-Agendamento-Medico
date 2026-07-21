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

// Verificar Envio de Formulários

if($_SERVER["REQUEST_METHOD"] !== "POST"){

    echo "Acesso inválido";
    exit;


}


// Validar campos obrigatórios

if(
    empty($_POST["nome"]) ||
    empty($_POST["email"]) ||
    empty($_POST["senha"]) ||
    empty($_POST["perfil"])

){

    echo "Preencha todos os campos!";
    exit;

}


// Recendo dados

$nome = $_POST["nome"];
$email = $_POST["email"];
$senha = $_POST["senha"];
$perfil = $_POST["perfil"];


// Verificar email existente

$sqlEmail = "
SELECT id
FROM usuarios
WHERE email = ?
";



$stmtEmail = $conn->prepare($sqlEmail);


$stmtEmail->bind_param(
    "s",git 
    $email
);


$stmtEmail->execute();


$resultado = $stmtEmail->get_result();



if($resultado->num_rows > 0) {

    echo "
    Este email já está cadastrado!
    <br>
    <a href='../../admin/usuarios/cadastrar.php'>
    Voltar
    </a>
    ";

    exit;

}



// Criptografar senha

$senhaHash = password_hash(
    $senha,
    PASSWORD_DEFAULT
);




// Inserir Usuário
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


$stmt = $conn->prepare($sql);



$stmt->bind_param(
    "ssss",
    $nome,
    $email,
    $senhaHash,
    $perfil

);



if(stmt->execute()){


    echo "
    Usuário cadastrado com sucesso!
    <br><br>

    <a href='../../admin/usuarios/cadastrar.php'>
    Cadastar outro usuário
    </a>

    ";


}else{

    echo "
    Erro ao cadastrar usuário:
    "
    .$conn->erro;


}



$stmt->close();
$conn->close();


?>
