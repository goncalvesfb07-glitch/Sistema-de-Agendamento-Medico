<?php

session_start();

require_once "config/conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;

}

$email = trim($_POST["email"]);
$senha = $_POST["senha"];

$sql = "SELECT * FROM usuarios Where email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$resultado = $stmt->get_result();



if ($resultado->num_rows == 1) {

    $usuario = $resultado->fetch_assoc();


} else {

   $_SESSION["erro"] = "E-mail ou Senha inválidos.";
   header("Location: index.php");
    exit;

}

if (password_verify($senha, $usuario["senha"]))  {
    
    $_SESSION["id"] = $usuario["id"];
    $_SESSION["nome"] = $usuario["nome"];
    $_SESSION["email"] = $usuario["email"];
    $_SESSION["perfil"] = $usuario["perfil"];

    header("Location: dashboard.php");
    exit;


} else {

    $_SESSION["erro"] = "E-mail ou senha inválidos.";
    header("Location: index.php");
    exit;

}

