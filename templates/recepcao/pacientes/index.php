<?php
require_once "../../../includes/verificar_recepcao.php"; require_once "../../../config/conexao.php";
$res = $conn->query("SELECT id,nome,cpf,data_nascimento,telefone,email FROM pacientes WHERE ativo=1 ORDER BY nome");
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Pacientes</title><link rel="stylesheet" href="../../../public/css/app.css"></head><body>
<div class="layout"><aside class="sidebar"><div class="brand">Clínica Vida+<small>Recepção</small></div><nav class="nav"><a href="../dashboard.php">Dashboard</a><a class="active" href="index.php">Pacientes</a><a href="../medicos/index.php">Médicos</a><a href="../agenda/index.php">Agenda</a><a href="../consultas/index.php">Consultas</a><a href="../../../logout.php">Sair</a></nav></aside>
<main class="main"><div class="topbar"><h1>Pacientes</h1><a class="btn btn-primary" href="cadastrar.php">+ Novo paciente</a></div>
<?php if(isset($_SESSION["sucesso"])): ?><div class="alert alert-success"><?= htmlspecialchars($_SESSION["sucesso"]); unset($_SESSION["sucesso"]); ?></div><?php endif; ?>
<?php if(isset($_SESSION["erro"])): ?><div class="alert alert-error"><?= htmlspecialchars($_SESSION["erro"]); unset($_SESSION["erro"]); ?></div><?php endif; ?>
<div class="card table-wrap"><table class="table"><thead><tr><th>Nome</th><th>CPF</th><th>Nascimento</th><th>Telefone</th><th>E-mail</th><th>Ações</th></tr></thead><tbody>
<?php while($p=$res->fetch_assoc()): ?><tr><td><?= htmlspecialchars($p["nome"]) ?></td><td><?= htmlspecialchars($p["cpf"]) ?></td><td><?= date("d/m/Y",strtotime($p["data_nascimento"])) ?></td><td><?= htmlspecialchars($p["telefone"] ?? "") ?></td><td><?= htmlspecialchars($p["email"] ?? "") ?></td><td><a class="btn btn-secondary" href="visualizar.php?id=<?= $p["id"] ?>">Ver</a> <a class="btn btn-primary" href="editar.php?id=<?= $p["id"] ?>">Editar</a></td></tr><?php endwhile; ?>
</tbody></table></div></main></div></body></html>
