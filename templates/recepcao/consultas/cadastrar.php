<?php
require_once "../../../includes/verificar_recepcao.php"; require_once "../../../config/conexao.php";
$pacientes=$conn->query("SELECT id,nome,cpf FROM pacientes WHERE ativo=1 ORDER BY nome");
$medicos=$conn->query("SELECT m.id,u.nome FROM medicos m INNER JOIN usuarios u ON u.id=m.usuario_id WHERE m.ativo=1 AND u.ativo=1 ORDER BY u.nome");
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Agendar consulta</title><link rel="stylesheet" href="../../../public/css/app.css"></head><body><div class="layout">
<aside class="sidebar"><div class="brand">Clínica Vida+<small>Recepção</small></div><nav class="nav"><a href="../dashboard.php">Dashboard</a><a href="../pacientes/index.php">Pacientes</a><a href="../agenda/index.php">Agenda</a><a class="active" href="index.php">Consultas</a><a href="../../../logout.php">Sair</a></nav></aside>
<main class="main"><div class="topbar"><h1>Agendar consulta</h1></div>
<?php if(isset($_SESSION["erro"])): ?><div class="alert alert-error"><?= htmlspecialchars($_SESSION["erro"]); unset($_SESSION["erro"]); ?></div><?php endif; ?>
<div class="card"><form action="../../../actions/consultas/cadastrar.php" method="POST"><div class="form-grid">
<div class="field"><label>Paciente *</label><select name="paciente_id" required><option value="">Selecione</option><?php while($p=$pacientes->fetch_assoc()): ?><option value="<?= $p["id"] ?>"><?= htmlspecialchars($p["nome"]) ?> — <?= htmlspecialchars($p["cpf"]) ?></option><?php endwhile; ?></select></div>
<div class="field"><label>Médico *</label><select name="medico_id" required><option value="">Selecione</option><?php while($m=$medicos->fetch_assoc()): ?><option value="<?= $m["id"] ?>"><?= htmlspecialchars($m["nome"]) ?></option><?php endwhile; ?></select></div>
<div class="field"><label>Data *</label><input type="date" name="data_consulta" min="<?= date("Y-m-d") ?>" required></div>
<div class="field"><label>Horário *</label><input type="time" name="horario" required></div>
<div class="field full"><label>Motivo da consulta</label><textarea name="motivo_consulta"></textarea></div>
</div><div class="actions"><button class="btn btn-primary">Agendar</button><a class="btn btn-secondary" href="index.php">Cancelar</a></div></form></div></main></div></body></html>
