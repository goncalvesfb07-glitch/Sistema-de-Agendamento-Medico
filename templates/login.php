<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clínica Vida+ | Login</title>
<link rel="stylesheet" href="../public/css/app.css">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <div class="login-brand">
            <div class="icon">✚</div>
            <h1>Clínica Vida+</h1>
            <p>Sistema de Agendamento Médico</p>
        </div>

        <?php if (isset($_SESSION["erro"])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_SESSION["erro"]) ?></div>
            <?php unset($_SESSION["erro"]); ?>
        <?php endif; ?>

        <form action="../auth.php" method="POST">
            <div class="field">
                <label for="email">E-mail</label>
                <input id="email" type="email" name="email" required autocomplete="email">
            </div>
            <div class="field">
                <label for="senha">Senha</label>
                <input id="senha" type="password" name="senha" required autocomplete="current-password">
            </div>
            <button class="btn-primary" type="submit">Entrar</button>
        </form>
    </div>
</div>
</body>
</html>
