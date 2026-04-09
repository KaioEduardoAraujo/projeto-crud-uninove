<?php
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$errors = validate_login($_POST);
if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    header('Location: login.php');
    exit;
}

$email = trim($_POST['email']);
$senha = $_POST['senha'];

try {
    $stmt = $pdo->prepare('SELECT id, email, senha, classe FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($senha, $user['senha'])) {
        set_flash('E-mail ou senha inválidos. Verifique e tente novamente.', 'error');
        header('Location: login.php');
        exit;
    }

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
