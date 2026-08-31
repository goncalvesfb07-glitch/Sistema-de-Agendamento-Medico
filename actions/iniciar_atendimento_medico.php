<?php
session_start();
require_once '../config/conexao.php'; // Ajuste o caminho conforme seu projeto
require_once "../../includes/verificar_medico.php"; // Verifica se o usuário é médico

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_consulta = filter_input(INPUT_POST, 'id_consulta', FILTER_VALIDATE_INT);

    if (!$id_consulta) {
        $_SESSION['erro'] = "Consulta inválida.";
        header("Location: ../templates/agenda_medico.php");
        exit;
    }

    // Verifica se já existe atendimento para essa consulta
    $sql_check = "SELECT id FROM atendimentos WHERE consulta_id = ? LIMIT 1";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $id_consulta);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $_SESSION['erro'] = "Atendimento já iniciado para esta consulta.";
        header("Location: ../templates/agenda_medico.php");
        exit;
    }

    // Insere novo atendimento
    $sql = "INSERT INTO atendimentos (consulta_id, medico_id, data_inicio, status) VALUES (?, ?, NOW(), 'em_andamento')";
    $medico_id = $_SESSION['usuario_id']; // Ajuste conforme sua sessão
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_consulta, $medico_id);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Atendimento iniciado com sucesso.";
        header("Location: ../templates/registrar_prontuario.php?atendimento_id=" . $stmt->insert_id);
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao iniciar atendimento.";
        header("Location: ../templates/agenda_medico.php");
        exit;
    }
} else {
    header("Location: ../templates/agenda_medico.php");
    exit;
}
