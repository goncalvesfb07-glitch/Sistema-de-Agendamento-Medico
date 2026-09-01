<?php
require_once "../../includes/verificar_admin.php";
require_once "../../config/conexao.php";

// Relatório US031 – Todas as consultas
$sql_todas_consultas = "
    SELECT a.id, a.data_inicio, a.status, m.nome AS medico_nome, a.especialidade
    FROM atendimentos a
    LEFT JOIN medicos m ON a.medico_id = m.id
    ORDER BY a.data_inicio DESC
";
$res_todas_consultas = $conn->query($sql_todas_consultas);

// Relatório US032 – Consultas por Médico
$sql_consultas_por_medico = "
    SELECT m.nome AS medico, COUNT(a.id) AS total_consultas
    FROM atendimentos a
    JOIN medicos m ON a.medico_id = m.id
    GROUP BY m.nome
    ORDER BY total_consultas DESC
";
$res_consultas_por_medico = $conn->query($sql_consultas_por_medico);

// Relatório US033 – Consultas por Especialidade
$sql_consultas_por_especialidade = "
    SELECT a.especialidade, COUNT(a.id) AS total_consultas
    FROM atendimentos a
    GROUP BY a.especialidade
    ORDER BY total_consultas DESC
";
$res_consultas_por_especialidade = $conn->query($sql_consultas_por_especialidade);

if (!$res_todas_consultas || !$res_consultas_por_medico || !$res_consultas_por_especialidade) {
    die("Erro ao executar consultas: " . $conn->error);
}
