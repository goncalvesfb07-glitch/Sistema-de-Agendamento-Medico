<?php
session_start();

// Suponha que você receba os dados do usuário após autenticação
$usuario = [
    'id' => 'medico',
    'nome' => 'Médico',
    'tipo' => 'medico',
    'status' => 'Ativo'
];

// Armazena na sessão
$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['perfil'] = $usuario['tipo'];

// Redireciona para dashboard
header("Location: medico/dashboard.php");
exit;
