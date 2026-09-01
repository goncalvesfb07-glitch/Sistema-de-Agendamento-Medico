<?php
require_once "../../../includes/verificar_recepcao.php"; require_once "../../../config/conexao.php";
$sql="SELECT c.id,c.data_consulta,c.horario,c.status,p.nome paciente,u.nome medico FROM consultas c INNER JOIN pacientes p ON p.id=c.paciente_id INNER JOIN medicos m ON m.id=c.medico_id INNER JOIN usuarios u ON u.id=m.usuario_id ORDER BY c.data_consulta DESC,c.horario DESC";
$res=$conn->query($sql);
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Consultas</title><link rel="stylesheet" href="../../../public/css/app.css"></head><body><div class="layout">
<aside class="sidebar"><div class="brand">Clínica Vida+<small>Recepção</small></div><nav class="nav"><a href="../dashboard.php">Dashboard</a><a href="../pacientes/index.php">Pacientes</a><a href="../agenda/index.php">Agenda</a><a class="active" href="index.php">Consultas</a><a href="../../../logout.php">Sair</a></nav></aside>
<main class="main"><div class="topbar"><h1>Consultas</h1><a class="btn btn-primary" href="cadastrar.php">+ Agendar consulta</a></div>
<?php if(isset($_SESSION["sucesso"])): ?><div class="alert alert-success"><?= htmlspecialchars($_SESSION["sucesso"]); unset($_SESSION["sucesso"]); ?></div><?php endif; ?>
<?php if(isset($_SESSION["erro"])): ?><div class="alert alert-error"><?= htmlspecialchars($_SESSION["erro"]); unset($_SESSION["erro"]); ?></div><?php endif; ?>
<div class="card table-wrap"><table class="table"><thead><tr><th>Data</th><th>Horário</th><th>Paciente</th><th>Médico</th><th>Status</th><th>Ações</th></tr></thead><tbody>
<?php while($c=$res->fetch_assoc()): ?><tr><td><?= date("d/m/Y",strtotime($c["data_consulta"])) ?></td><td><?= substr($c["horario"],0,5) ?></td><td><?= htmlspecialchars($c["paciente"]) ?></td><td><?= htmlspecialchars($c["medico"]) ?></td><td><span class="badge badge-blue"><?= htmlspecialchars($c["status"]) ?></span></td><td><a class="btn btn-secondary" href="editar.php?id=<?= $c["id"] ?>">Reagendar</a></td></tr><?php endwhile; ?>
</tbody></table></div></main></div></body></html>
