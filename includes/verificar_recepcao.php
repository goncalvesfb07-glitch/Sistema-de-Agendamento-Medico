<?php

/*
|--------------------------------------------------------------------------
| Arquivo: verificar_recepcao.php
|--------------------------------------------------------------------------
| Objetivo:
| Permitir acesso apenas para usuários autenticados com perfil
| Recepcionista.
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION["id"])) {

    header("Location: /ProjetoAgendamentoMedico/public/index.php");
    exit;
}


if ($_SESSION["perfil"] !== "Recepcionista") {

    header("Location: /ProjetoAgendamentoMedico/templates/painel-usuario.php");
    exit;
}