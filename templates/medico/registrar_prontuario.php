<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Prontuário</title>
</head>
<body>

<h2>Registrar Prontuário</h2>

<?php if (!empty($_SESSION['sucesso'])): ?>
    <div style="color: green;"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
<?php endif; ?>

<?php if (!empty($_SESSION['erro'])): ?>
    <div style="color: red;"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
<?php endif; ?>

<form method="POST" action="../../actions/medico/processar_atendimento.php">
    <input type="hidden" name="acao" value="registrar_prontuario">
    <input type="hidden" name="atendimento_id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">

    <label for="sintomas">Sintomas:</label><br>
    <textarea name="sintomas" id="sintomas" required></textarea><br><br>

    <label for="diagnostico">Diagnóstico:</label><br>
    <textarea name="diagnostico" id="diagnostico" required></textarea><br><br>

    <label for="observacoes">Observações:</label><br>
    <textarea name="observacoes" id="observacoes"></textarea><br><br>

    <label for="tratamento">Tratamento:</label><br>
    <textarea name="tratamento" id="tratamento" required></textarea><br><br>

    <button type="submit">Salvar Prontuário</button>
</form>

<a href="consultar.php?id=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">Voltar</a>

</body>
</html>
