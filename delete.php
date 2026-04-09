<?php
require_once __DIR__ . '/functions.php';
require_admin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('ID inválido para exclusão.', 'error');
    header('Location: index.php');
    exit;
}

try {
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
