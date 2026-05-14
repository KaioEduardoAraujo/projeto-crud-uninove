<?php
// Processa a atualização de um relógio
require_once __DIR__ . '/functions.php';
require_login();

// Só processa POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Valida o ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('ID inválido.', 'error');
    header('Location: index.php');
    exit;
}

// Valida os dados
$errors = validate_relogio($_POST);
if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}

// Pega os dados limpos
$marca = trim($_POST['marca']);
$cor = trim($_POST['cor_pulseira']);

// Verifica se essa combinação já existe (excluindo o relógio atual)
if (check_marca_cor_exists($marca, $cor, $id)) {
    set_flash('Já existe outro relógio com essa marca e cor.', 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    // Atualiza o relógio
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
    set_flash('Erro ao atualizar o relógio.', 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}
