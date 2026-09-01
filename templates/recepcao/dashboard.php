<?php
require_once "../../includes/verificar_recepcao.php";
require_once "../../config/conexao.php";
$total_pacientes = 0; $total_consultas = 0;
$r = $conn->query("SELECT COUNT(*) total FROM pacientes WHERE ativo=1");
if ($r) $total_pacientes = $r->fetch_assoc()["total"];
$r = $conn->query("SELECT COUNT(*) total FROM consultas WHERE data_consulta=CURDATE() AND status <> 'Cancelada'");
if ($r) $total_consultas = $r->fetch_assoc()["total"];
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Recepção</title><link rel="stylesheet" href="../../public/css/app.css"></head>
<body><div class="layout">
<aside class="sidebar"><div class="brand">Clínica Vida+<small>Recepção</small></div><nav class="nav">
<a class="active" href="dashboard.php">Dashboard</a>
<a href="pacientes/index.php">Pacientes</a><a href="medicos/index.php">Médicos</a><a href="agenda/index.php">Agenda</a><a href="consultas/index.php">Consultas</a><a href="../../logout.php">Sair</a>
</nav></aside>
<main class="main"><div class="topbar"><h1>Dashboard</h1><div class="user"><?= htmlspecialchars($_SESSION["nome"] ?? "") ?></div></div>
<div class="grid"><div class="stat"><span>Pacientes ativos</span><strong><?= $total_pacientes ?></strong></div><div class="stat"><span>Consultas hoje</span><strong><?= $total_consultas ?></strong></div></div>
<div class="card"><h2>Acesso rápido</h2><div class="actions"><a class="btn btn-primary" href="pacientes/cadastrar.php">Cadastrar paciente</a><a class="btn btn-primary" href="consultas/cadastrar.php">Agendar consulta</a><a class="btn btn-secondary" href="agenda/index.php">Consultar agenda</a></div></div>
</main></div><script src="../../public/js/app.js"></script></body></html>
