<?php
/*
|--------------------------------------------------------------------------
| Página: index.php
|--------------------------------------------------------------------------
| Objetivo:
| Exibir a tela de login.
|
| Responsabilidades:
| - Iniciar a sessão.
| - Verificar se o usuário já está autenticado.
| - Exibir mensagens de erro do login.
|--------------------------------------------------------------------------
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION["id"])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Agendamento Médico</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="container">

    <div class="left">

        <div class="logo">
            <i class="fa-solid fa-hospital"></i>
        </div>

        <h1>Clínica Vida+</h1>

        <p>
            Sistema de Agendamento Médico
        </p>

    </div>

    <div class="right">

        <form id="loginForm" action="login.php" method="POST">

            <h2>Login</h2>

            <?php
            if (isset($_SESSION["erro"])) {
                echo "<p style='color:red; text-align:center; margin-bottom:15px;'>"
                    . $_SESSION["erro"] .
                    "</p>";

                unset($_SESSION["erro"]);
            }
            ?>

            <div class="inputBox">

                <i class="fa-solid fa-envelope"></i>

                <input
                    type="email"
                    name="email"
                    placeholder="E-mail"
                    required>

            </div>

            <div class="inputBox">

                <i class="fa-solid fa-lock"></i>

                <input
                    type="password"
                    name="senha"
                    placeholder="Senha"
                    required>

            </div>

            <button type="submit">
                Entrar
            </button>

        </form>

    </div>

</div>

<script src="assets/js/script.js"></script>

</body>

</html>