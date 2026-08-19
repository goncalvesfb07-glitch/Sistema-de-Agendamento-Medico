<?php

/*
|--------------------------------------------------------------------------
| Página: cadastrar horário
|--------------------------------------------------------------------------
| Objetivo:
| Permitir que o administrador defina a disponibilidade de um médico.
|--------------------------------------------------------------------------
*/

require_once "../../../includes/verificar_admin.php";
require_once "../../../config/conexao.php";


/*
|--------------------------------------------------------------------------
| Busca os médicos ativos
|--------------------------------------------------------------------------
*/

$sql = "SELECT
            m.id,
            u.nome

        FROM medicos m

        INNER JOIN usuarios u
            ON u.id = m.usuario_id

        WHERE m.ativo = 1
        AND u.ativo = 1

        ORDER BY u.nome ASC";


$stmt = $conn->prepare($sql);

$stmt->execute();

$resultado = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Cadastrar Horário</title>

</head>

<body>

<h1>Definir Horário de Atendimento</h1>


<?php

if (isset($_SESSION["erro"])) {

    echo "<p style='color: red;'>"
        . htmlspecialchars($_SESSION["erro"])
        . "</p>";

    unset($_SESSION["erro"]);
}

?>


<form
    action="../../../actions/horarios/cadastrar.php"
    method="POST"
>


    <label for="medico_id">
        Médico:
    </label>

    <br>

    <select
        id="medico_id"
        name="medico_id"
        required
    >

        <option value="">
            Selecione o médico
        </option>


        <?php while ($medico = $resultado->fetch_assoc()): ?>

            <option value="<?= $medico["id"]; ?>">

                <?= htmlspecialchars($medico["nome"]); ?>

            </option>

        <?php endwhile; ?>

    </select>

    <br><br>


    <label for="dia_semana">
        Dia da semana:
    </label>

    <br>

    <select
        id="dia_semana"
        name="dia_semana"
        required
    >

        <option value="">
            Selecione
        </option>

        <option value="0">
            Domingo
        </option>

        <option value="1">
            Segunda-feira
        </option>

        <option value="2">
            Terça-feira
        </option>

        <option value="3">
            Quarta-feira
        </option>

        <option value="4">
            Quinta-feira
        </option>

        <option value="5">
            Sexta-feira
        </option>

        <option value="6">
            Sábado
        </option>

    </select>

    <br><br>


    <label for="hora_inicio">
        Hora inicial:
    </label>

    <br>

    <input
        type="time"
        id="hora_inicio"
        name="hora_inicio"
        required
    >

    <br><br>


    <label for="hora_fim">
        Hora final:
    </label>

    <br>

    <input
        type="time"
        id="hora_fim"
        name="hora_fim"
        required
    >

    <br><br>


    <label for="intervalo_minutos">
        Intervalo entre atendimentos:
    </label>

    <br>

    <select
        id="intervalo_minutos"
        name="intervalo_minutos"
        required
    >

        <option value="15">
            15 minutos
        </option>

        <option value="30" selected>
            30 minutos
        </option>

        <option value="45">
            45 minutos
        </option>

        <option value="60">
            60 minutos
        </option>

    </select>

    <br><br>


    <button type="submit">
        Cadastrar horário
    </button>

</form>


<br>


<a href="index.php">
    Voltar
</a>

</body>

</html>