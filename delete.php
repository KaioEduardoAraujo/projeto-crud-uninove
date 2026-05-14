<?php
// Deleta um relógio (só admin pode fazer isso)
require_once __DIR__ . '/functions.php';

// Verifica se é admin
require_admin();

// Valida o ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('ID inválido.', 'error');
    header('Location: index.php');
    exit;
}

try {
    // Deleta o relógio
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
