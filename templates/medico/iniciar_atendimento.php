<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Atendimento</title>
</head>
<body>

<h2>Iniciar Atendimento</h2>

<?php if (!empty($_SESSION['sucesso'])): ?>
    <div style="color: green;"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php endif; ?>

<?php if (!empty($_SESSION['erro'])): ?>
    <div style="color: red;"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
<?php endif; ?>

<form method="POST" action="../../actions/medico/processar_atendimento.php">
    <input type="hidden" name="acao" value="iniciar">
    <input type="hidden" name="atendimento_id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
    <button type="submit">Iniciar Atendimento</button>
</form>

<a href="consultar.php?id=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">Voltar</a>

</body>
</html>
