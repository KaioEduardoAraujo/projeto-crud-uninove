<?php
// Processa o login do usuário
require_once __DIR__ . '/functions.php';

// Só processa POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Valida os dados
$errors = validate_login($_POST);
if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    header('Location: login.php');
    exit;
}

// Pega os dados
$email = trim($_POST['email']);
$senha = $_POST['senha'];

try {
    // Busca o usuário no banco
    $stmt = $pdo->prepare('SELECT id, email, senha, classe FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // Verifica se existe e se a senha está correta
    if (!$user || !password_verify($senha, $user['senha'])) {
        set_flash('E-mail ou senha inválidos.', 'error');
        header('Location: login.php');
        exit;
    }

    // Se tudo ok, cria a sessão
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'classe' => $user['classe'],
    ];

    set_flash('Login realizado com sucesso.', 'success');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    set_flash('Erro ao autenticar. Tente novamente mais tarde.', 'error');
    header('Location: login.php');
    exit;
}
