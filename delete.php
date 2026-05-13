<?php
/**
 * Processamento de Exclusão de Relógio
 * 
 * Deleta um relógio do banco de dados. Requer acesso de administrador.
 */

require_once __DIR__ . '/functions.php';

// Valida se o usuário é administrador antes de permitir exclusão
require_admin();

// Valida e obtém o ID do relógio a ser deletado
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('ID inválido para exclusão.', 'error');
    header('Location: index.php');
    exit;
}

try {
    // Deleta o relógio do banco de dados
    $stmt = $pdo->prepare('DELETE FROM relogios WHERE id = :id');
    $stmt->execute([':id' => $id]);
    set_flash('Relógio excluído com sucesso.', 'success');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    set_flash('Erro ao excluir o relógio. Tente novamente.', 'error');
    header('Location: index.php');
    exit;
}
