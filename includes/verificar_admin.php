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
    header("Location: /ProjetoAgendamentoMedico/index.php");
    exit;

}

if ($_SESSION["perfil"] !== "Administrador") {
    header("Location: /ProjetoAgendamentoMedico/dashboard.php");
    exit;
    
}