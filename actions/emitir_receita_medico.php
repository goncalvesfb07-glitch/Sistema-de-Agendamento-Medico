<?php
session_start();
require_once '../config/conexao.php';
require_once "../../includes/verificar_medico.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $atendimento_id = filter_input(INPUT_POST, 'atendimento_id', FILTER_VALIDATE_INT);
    $medicamentos = $_POST['medicamentos'] ?? []; // Array com nome e quantidade

    if (!$atendimento_id || empty($medicamentos)) {
        $_SESSION['erro'] = "Dados inválidos para emissão da receita.";
        header("Location: ../templates/emitir_receita.php?atendimento_id=" . $atendimento_id);
        exit;
    }

    // Inicia transação
    $conn->begin_transaction();

    try {
        // Insere receita
        $sql_receita = "INSERT INTO receitas (atendimento_id, data_emissao) VALUES (?, NOW())";
        $stmt_receita = $conn->prepare($sql_receita);
        $stmt_receita->bind_param('i', $atendimento_id);
        $stmt_receita->execute();
        $receita_id = $stmt_receita->insert_id;

        // Insere itens da receita
        $sql_item = "INSERT INTO itens_receita (receita_id, medicamento, quantidade) VALUES (?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);

        foreach ($medicamentos as $item) {
            $medicamento = trim($item['nome'] ?? '');
            $quantidade = intval($item['quantidade'] ?? 0);

            if ($medicamento === '' || $quantidade <= 0) {
                throw new Exception("Medicamento ou quantidade inválidos.");
            }

            $stmt_item->bind_param('isi', $receita_id, $medicamento, $quantidade);
            $stmt_item->execute();
        }

        $conn->commit();
        $_SESSION['sucesso'] = "Receita emitida com sucesso.";
        header("Location: ../templates/encerrar_atendimento.php?atendimento_id=" . $atendimento_id);
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['erro'] = "Erro ao emitir receita: " . $e->getMessage();
        header("Location: ../templates/emitir_receita.php?atendimento_id=" . $atendimento_id);
        exit;
    }
} else {
    header("Location: ../templates/agenda_medico.php");
    exit;
}
