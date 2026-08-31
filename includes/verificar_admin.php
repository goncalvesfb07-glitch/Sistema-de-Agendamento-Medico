<?php
/*
|--------------------------------------------------------------------------
| Arquivo: verificar_admin.php
|--------------------------------------------------------------------------
| Objetivo:
| Permitir acesso apenas para usuários autenticados com perfil
| Administrador.
|--------------------------------------------------------------------------
*/

// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado (existe id na sessão)
if (!isset($_SESSION["id"])) {
    header("Location: /ProjetoAgendamentoMedico/public/index.php");
    exit;
}

// Verifica se o perfil do usuário é Administrador
if (!isset($_SESSION["perfil"]) || $_SESSION["perfil"] !== "Administrador") {
    header("Location: /ProjetoAgendamentoMedico/templates/painel-usuario.php");
    exit;
}
