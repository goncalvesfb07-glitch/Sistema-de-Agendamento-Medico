<?php
session_start();
require_once '../config/conexao.php';
require_once "../../includes/verificar_medico.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atendimento_id = filter_input(INPUT_POST, 'atendimento_id', FILTER_VALIDATE_INT);

    if (!$atendimento_id) {
        $_SESSION['erro'] = "Atendimento inválido.";
        header("Location: ../templates/agenda_medico.php");
        exit;
    }

    $sql = "UPDATE atendimentos SET status = 'finalizado', data_fim = NOW() WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $atendimento_id);

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Atendimento finalizado com sucesso.";
        header("Location: ../templates/agenda_medico.php");
        exit;
    } else {
        $_SESSION['erro'] = "Erro ao finalizar atendimento.";
        header("Location: ../templates/encerrar_atendimento.php?atendimento_id=" . $atendimento_id);
        exit;
    }
} else {
    header("Location: ../templates/agenda_medico.php");
    exit;
}