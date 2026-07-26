<?php

require_once "config/conexao.php";

$nome = "Administrador";
$email = "admin@admin.com";
$senha = password_hash("admin123", PASSWORD_DEFAULT);
$perfil = "Administrador";

$sql = "INSERT INTO usuarios (nome, email, senha, perfil)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssss",
    $nome,
    $email,
    $senha,
    $perfil
);

if ($stmt->execute()) {

    echo "Administrador criado com sucesso!";

} else {

    echo "Erro: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>