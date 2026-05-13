<?php
/**
 * Processamento de Criação de Relógio
 * 
 * Recebe dados do formulário, valida, verifica se a combinação marca+cor
 * já existe, e insere novo relógio no banco de dados.
 */

require_once __DIR__ . '/functions.php';
require_login();

// Somente aceita requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create.php');
    exit;
}

// Valida os dados de entrada
$errors = validate_relogio($_POST);
if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    header('Location: create.php');
    exit;
}

// Obtém e limpa os dados do formulário
$marca = trim($_POST['marca']);
$cor = trim($_POST['cor_pulseira']);

// Verifica se já existe um relógio com essa combinação de marca+cor
// Isso previne duplicatas baseado em business rules
if (check_marca_cor_exists($marca, $cor)) {
    set_flash('Já existe um relógio com essa combinação de marca e cor.', 'error');
    header('Location: create.php');
    exit;
}

try {
    // Insere novo relógio no banco de dados usando prepared statement
    $stmt = $pdo->prepare('INSERT INTO relogios (marca, cor_pulseira, tipo, preco, quantidade_estoque) VALUES (:marca, :cor_pulseira, :tipo, :preco, :quantidade_estoque)');
    $stmt->execute([
        ':marca' => $marca,
        ':cor_pulseira' => $cor,
        ':tipo' => trim($_POST['tipo']),
        ':preco' => floatval($_POST['preco']),
        ':quantidade_estoque' => intval($_POST['quantidade_estoque']),
    ]);
    set_flash('Relógio criado com sucesso.', 'success');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    set_flash('Erro ao criar o relógio. Tente novamente.', 'error');
    header('Location: create.php');
    exit;
}
