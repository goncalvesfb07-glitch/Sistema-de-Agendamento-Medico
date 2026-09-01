<?php require_once "../../includes/verificar_admin.php"; require_once "../../config/conexao.php";
$u=$conn->query("SELECT COUNT(*) total FROM usuarios WHERE ativo=1")->fetch_assoc()["total"];
$m=$conn->query("SELECT COUNT(*) total FROM medicos WHERE ativo=1")->fetch_assoc()["total"];
$p=$conn->query("SELECT COUNT(*) total FROM pacientes WHERE ativo=1")->fetch_assoc()["total"];
$c=$conn->query("SELECT COUNT(*) total FROM consultas WHERE data_consulta=CURDATE() AND status<>'Cancelada'")->fetch_assoc()["total"];
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Administrador</title><link rel="stylesheet" href="../../public/css/app.css"></head><body><div class="layout">
<aside class="sidebar"><div class="brand">Clínica Vida+<small>Administrador</small></div><nav class="nav"><a class="active" href="dashboard.php">Dashboard</a><a href="usuarios/index.php">Usuários</a><a href="medicos/index.php">Médicos</a><a href="especialidades/index.php">Especialidades</a><a href="horarios/index.php">Horários</a><a href="../../logout.php">Sair</a></nav></aside>
<main class="main"><div class="topbar"><h1>Dashboard</h1><div class="user"><?= htmlspecialchars($_SESSION["nome"]??"") ?></div></div><div class="grid"><div class="stat"><span>Usuários ativos</span><strong><?= $u ?></strong></div><div class="stat"><span>Médicos ativos</span><strong><?= $m ?></strong></div><div class="stat"><span>Pacientes ativos</span><strong><?= $p ?></strong></div><div class="stat"><span>Consultas hoje</span><strong><?= $c ?></strong></div></div>
<div class="card"><h2>Gerenciamento</h2><div class="actions"><a class="btn btn-primary" href="usuarios/cadastrar.php">Novo usuário</a><a class="btn btn-primary" href="medicos/cadastrar.php">Novo médico</a><a class="btn btn-secondary" href="horarios/index.php">Gerenciar horários</a></div></div></main></div></body></html>
