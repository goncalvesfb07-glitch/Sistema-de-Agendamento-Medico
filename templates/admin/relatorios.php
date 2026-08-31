<?php
require_once "../../actions/relatorios_consultas.php";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Relatórios de Consultas - Administração</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        h2 { margin-top: 40px; }
    </style>
</head>
<body>
    <h1>Relatórios de Consultas</h1>

    <!-- US031 – Relatório de Consultas -->
    <section>
        <h2>Todas as Consultas (US031)</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Médico</th>
                    <th>Data de Início</th>
                    <th>Status</th>
                    <th>Especialidade</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res_todas_consultas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['medico_nome']); ?></td>
                    <td><?php echo htmlspecialchars($row['data_inicio']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td><?php echo htmlspecialchars($row['especialidade']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- US032 – Consultas por Médico -->
    <section>
        <h2>Consultas por Médico (US032)</h2>
        <table>
            <thead>
                <tr>
                    <th>Médico</th>
                    <th>Total de Consultas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res_consultas_por_medico->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['medico']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_consultas']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>

    <!-- US033 – Consultas por Especialidade -->
    <section>
        <h2>Consultas por Especialidade (US033)</h2>
        <table>
            <thead>
                <tr>
                    <th>Especialidade</th>
                    <th>Total de Consultas</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res_consultas_por_especialidade->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['especialidade']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_consultas']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
