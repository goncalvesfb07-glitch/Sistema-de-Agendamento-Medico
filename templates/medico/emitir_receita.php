<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Emitir Receita</title>
</head>
<body>

<h2>Emitir Receita</h2>

<?php if (!empty($_SESSION['sucesso'])): ?>
    <div style="color: green;"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php endif; ?>

<?php if (!empty($_SESSION['erro'])): ?>
    <div style="color: red;"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
<?php endif; ?>

<form method="POST" action="../../actions/medico/processar_atendimento.php">
    <input type="hidden" name="acao" value="adicionar_medicamento">
    <input type="hidden" name="atendimento_id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">

    <label for="medicamento">Medicamento:</label><br>
    <input type="text" name="medicamento" id="medicamento" required><br><br>

    <label for="posologia">Posologia:</label><br>
    <input type="text" name="posologia" id="posologia" required><br><br>

    <button type="submit">Adicionar Medicamento</button>
</form>

<a href="consultar.php?id=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">Voltar</a>

</body>
</html>
