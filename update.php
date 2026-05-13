<?php
/**
 * Processamento de Atualização de Relógio
 * 
 * Recebe dados do formulário de edição, valida, verifica se a combinação 
 * marca+cor não está sendo usada por outro relógio, e atualiza o banco.
 */

require_once __DIR__ . '/functions.php';
require_login();

// Somente aceita requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Valida e obtém o ID do relógio a ser atualizado
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('ID de relógio inválido.', 'error');
    header('Location: index.php');
    exit;
}

// Valida os dados de entrada
$errors = validate_relogio($_POST);
if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}

// Obtém e limpa os dados do formulário
$marca = trim($_POST['marca']);
$cor = trim($_POST['cor_pulseira']);

// Verifica se já existe outro relógio com essa combinação marca+cor
// Passa o ID atual para não validar contra ele mesmo
if (check_marca_cor_exists($marca, $cor, $id)) {
    set_flash('Já existe outro relógio com essa combinação de marca e cor.', 'error');
    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    // Atualiza o relógio no banco de dados
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
