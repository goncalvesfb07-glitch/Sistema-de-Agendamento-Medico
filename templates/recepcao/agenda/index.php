<?php
require_once "../../../includes/verificar_recepcao.php"; require_once "../../../config/conexao.php";
$medico_id=filter_input(INPUT_GET,"medico_id",FILTER_VALIDATE_INT); $data=$_GET["data"]??"";
$medicos=$conn->query("SELECT m.id,u.nome FROM medicos m INNER JOIN usuarios u ON u.id=m.usuario_id WHERE m.ativo=1 AND u.ativo=1 ORDER BY u.nome");
$slots=[];
if($medico_id && $data){
    $q=$conn->prepare("SELECT horario,status FROM consultas WHERE medico_id=? AND data_consulta=? AND status IN ('Agendada','Em Andamento')");
    $q->bind_param("is",$medico_id,$data); $q->execute(); $r=$q->get_result(); $ocup=[];
    while($x=$r->fetch_assoc())$ocup[substr($x["horario"],0,5)]=$x["status"];
    $dia=(int)(new DateTime($data))->format("w");
    $q=$conn->prepare("SELECT hora_inicio,hora_fim,intervalo_minutos FROM horarios WHERE medico_id=? AND dia_semana=? AND ativo=1 ORDER BY hora_inicio");
    $q->bind_param("ii",$medico_id,$dia);$q->execute();$r=$q->get_result();
    while($faixa=$r->fetch_assoc()){
        $inicio=new DateTime("$data ".$faixa["hora_inicio"]);$fim=new DateTime("$data ".$faixa["hora_fim"]);$int=(int)$faixa["intervalo_minutos"];
        while(true){$prox=clone $inicio;$prox->modify("+$int minutes");if($prox>$fim)break;$h=$inicio->format("H:i");$slots[]=[$h,$ocup[$h]??null];$inicio=$prox;}
    }
}
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Agenda</title><link rel="stylesheet" href="../../../public/css/app.css"></head><body><div class="layout">
<aside class="sidebar"><div class="brand">Clínica Vida+<small>Recepção</small></div><nav class="nav"><a href="../dashboard.php">Dashboard</a><a href="../pacientes/index.php">Pacientes</a><a class="active" href="index.php">Agenda</a><a href="../consultas/index.php">Consultas</a><a href="../../../logout.php">Sair</a></nav></aside>
<main class="main"><div class="topbar"><h1>Agenda médica</h1></div>
<div class="card"><form method="GET" class="form-grid"><div class="field"><label>Médico</label><select name="medico_id" required><option value="">Selecione</option><?php while($m=$medicos->fetch_assoc()): ?><option value="<?= $m["id"] ?>" <?= $medico_id==$m["id"]?"selected":"" ?>><?= htmlspecialchars($m["nome"]) ?></option><?php endwhile; ?></select></div><div class="field"><label>Data</label><input type="date" name="data" value="<?= htmlspecialchars($data) ?>" min="<?= date("Y-m-d") ?>" required></div><div class="actions"><button class="btn btn-primary">Consultar</button></div></form></div>
<?php if($medico_id&&$data): ?><div class="card"><h2>Horários</h2><div class="table-wrap"><table class="table"><thead><tr><th>Horário</th><th>Status</th><th>Ação</th></tr></thead><tbody><?php foreach($slots as $s): ?><tr><td><?= $s[0] ?></td><td><?= $s[1]?'<span class="badge badge-red">Ocupado</span>':'<span class="badge badge-green">Disponível</span>' ?></td><td><?php if(!$s[1]): ?><a class="btn btn-primary" href="../consultas/cadastrar.php">Agendar</a><?php else: ?>—<?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div></div><?php endif; ?>
</main></div></body></html>
