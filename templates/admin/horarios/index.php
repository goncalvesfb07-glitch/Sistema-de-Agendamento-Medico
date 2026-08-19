<?php

/*
|--------------------------------------------------------------------------
| Página: consultar horários
|--------------------------------------------------------------------------
| Objetivo:
| Exibir os horários de atendimento cadastrados pelos administradores.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_admin.php";
require_once "../../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Busca os horários
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            h.id,
            h.medico_id,
            h.dia_semana,
            h.hora_inicio,
            h.hora_fim,
            h.intervalo_minutos,
            h.ativo,
            u.nome AS medico_nome

        FROM horarios h

        INNER JOIN medicos m
            ON m.id = h.medico_id

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        ORDER BY
            u.nome ASC,
            h.dia_semana ASC,
            h.hora_inicio ASC";


$stmt = $conn->prepare($sql);

$stmt->execute();

$resultado = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| Converte o número do dia para o nome
|--------------------------------------------------------------------------
*/

$dias_semana = [
    0 => "Domingo",
    1 => "Segunda-feira",
    2 => "Terça-feira",
    3 => "Quarta-feira",
    4 => "Quinta-feira",
    5 => "Sexta-feira",
    6 => "Sábado"
];

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Horários de Atendimento</title>

</head>

<body>

<h1>Horários de Atendimento</h1>


<?php

/*
|--------------------------------------------------------------------------
| Mensagem de sucesso
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["sucesso"])) {

    echo "<p style='color: green;'>"
        . htmlspecialchars($_SESSION["sucesso"])
        . "</p>";

    unset($_SESSION["sucesso"]);
}


/*
|--------------------------------------------------------------------------
| Mensagem de erro
|--------------------------------------------------------------------------
*/

if (isset($_SESSION["erro"])) {

    echo "<p style='color: red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<a href="cadastrar.php">
    Definir novo horário
</a>

<br><br>


<table border="1" cellpadding="8">

    <thead>

        <tr>

            <th>Médico</th>

            <th>Dia</th>

            <th>Horário inicial</th>

            <th>Horário final</th>

            <th>Intervalo</th>

            <th>Status</th>

        </tr>

    </thead>


    <tbody>

    <?php if ($resultado->num_rows > 0): ?>

        <?php while ($horario = $resultado->fetch_assoc()): ?>

            <tr>

                <td>
                    <?= htmlspecialchars($horario["medico_nome"]); ?>
                </td>


                <td>

                    <?= htmlspecialchars(
                        $dias_semana[$horario["dia_semana"]]
                        ?? "Desconhecido"
                    ); ?>

                </td>


                <td>

                    <?= htmlspecialchars(
                        substr($horario["hora_inicio"], 0, 5)
                    ); ?>

                </td>


                <td>

                    <?= htmlspecialchars(
                        substr($horario["hora_fim"], 0, 5)
                    ); ?>

                </td>


                <td>

                    <?= (int) $horario["intervalo_minutos"]; ?>

                    minutos

                </td>


                <td>

                    <?php if ($horario["ativo"] == 1): ?>

                        Ativo

                    <?php else: ?>

                        Inativo

                    <?php endif; ?>

                </td>

            </tr>

        <?php endwhile; ?>

    <?php else: ?>

        <tr>

            <td colspan="6">

                Nenhum horário cadastrado.

            </td>

        </tr>

    <?php endif; ?>

    </tbody>

</table>

</body>

</html>