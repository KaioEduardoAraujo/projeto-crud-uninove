<?php
require_once __DIR__ . '/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('ID de relógio inválido.', 'error');
    header('Location: index.php');
    exit;
}

$errors = validate_relogio($_POST);
if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}

// Validar se combinação marca + cor já existe (excluindo este ID)
$marca = trim($_POST['marca']);
$cor = trim($_POST['cor_pulseira']);
if (check_marca_cor_exists($marca, $cor, $id)) {
    set_flash('Já existe outro relógio com essa combinação de marca e cor.', 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE relogios SET marca = :marca, cor_pulseira = :cor_pulseira, tipo = :tipo, preco = :preco, quantidade_estoque = :quantidade_estoque WHERE id = :id');
    $stmt->execute([
        ':marca' => $marca,
        ':cor_pulseira' => $cor,
        ':tipo' => trim($_POST['tipo']),
        ':preco' => floatval($_POST['preco']),
        ':quantidade_estoque' => intval($_POST['quantidade_estoque']),
        ':id' => $id,
    ]);
    set_flash('Relógio atualizado com sucesso.', 'success');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    set_flash('Erro ao atualizar o relógio. Tente novamente.', 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}
