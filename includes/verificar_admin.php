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

if (session_start() === PHP_SESSION_NONE) {
    session_start();

}

if (!isset($_SESSION["id"])) {
    header("Location: /ProjetoAgendamentoMedico/public/index.php");
    exit;

}

if ($_SESSION["perfil"] !== "Administrador") {
    header("Location: /ProjetoAgendamentoMedico/templates/painel-usuario.php");
    exit;
    
}