<?php
session_start();
require_once '../config/conexao.php';
require_once "../../includes/verificar_medico.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atendimento_id = filter_input(INPUT_POST, 'atendimento_id', FILTER_VALIDATE_INT);
    $sintomas = trim($_POST['sintomas'] ?? '');
    $diagnostico = trim($_POST['diagnostico'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');
    $tratamento = trim($_POST['tratamento'] ?? '');

    if (!$atendimento_id) {
        $_SESSION['erro'] = "Atendimento inválido.";
        header("Location: ../templates/agenda_medico.php");
        exit;
    }

    $sql = "UPDATE atendimentos SET sintomas = ?, diagnostico = ?, observacoes = ?, tratamento = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $sintomas, $diagnostico, $observacoes, $tratamento, $atendimento_id);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Prontuário registrado com sucesso.";
        header("Location: ../templates/emitir_receita.php?atendimento_id=" . $atendimento_id);
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao registrar prontuário.";
        header("Location: ../templates/registrar_prontuario.php?atendimento_id=" . $atendimento_id);
        exit;
    }
} else {
    header("Location: ../templates/agenda_medico.php");
    exit;
}
