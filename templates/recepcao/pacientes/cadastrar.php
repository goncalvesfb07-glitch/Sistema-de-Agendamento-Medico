<?php require_once "../../../includes/verificar_recepcao.php"; ?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Cadastrar paciente</title><link rel="stylesheet" href="../../../public/css/app.css"></head><body>
<div class="layout"><aside class="sidebar"><div class="brand">Clínica Vida+<small>Recepção</small></div><nav class="nav"><a href="../dashboard.php">Dashboard</a><a class="active" href="index.php">Pacientes</a><a href="../consultas/index.php">Consultas</a><a href="../../../logout.php">Sair</a></nav></aside>
<main class="main"><div class="topbar"><h1>Cadastrar paciente</h1></div>
<?php if(isset($_SESSION["erro"])): ?><div class="alert alert-error"><?= htmlspecialchars($_SESSION["erro"]); unset($_SESSION["erro"]); ?></div><?php endif; ?>
<div class="card"><form action="../../../actions/pacientes/cadastrar.php" method="POST"><div class="form-grid">
<div class="field"><label>Nome *</label><input name="nome" maxlength="100" required></div>
<div class="field"><label>CPF *</label><input name="cpf" maxlength="14" placeholder="000.000.000-00" required></div>
<div class="field"><label>Data de nascimento *</label><input type="date" name="data_nascimento" required></div>
<div class="field"><label>Sexo *</label><select name="sexo" required><option value="">Selecione</option><option>Masculino</option><option>Feminino</option><option>Outro</option></select></div>
<div class="field"><label>Telefone</label><input name="telefone" maxlength="20"></div><div class="field"><label>E-mail</label><input type="email" name="email" maxlength="150"></div>
<div class="field full"><label>Endereço</label><input name="endereco" maxlength="255"></div>
<div class="field"><label>Convênio</label><input name="convenio" maxlength="100"></div><div class="field"><label>Tipo sanguíneo</label><input name="tipo_sanguineo" maxlength="5"></div>
<div class="field full"><label>Alergias</label><textarea name="alergias"></textarea></div><div class="field full"><label>Observações</label><textarea name="observacoes"></textarea></div>
</div><div class="actions"><button class="btn btn-primary">Cadastrar</button><a class="btn btn-secondary" href="index.php">Cancelar</a></div></form></div></main></div></body></html>
