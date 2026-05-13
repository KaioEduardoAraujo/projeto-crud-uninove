<?php
/**
 * Processamento de Autenticação (Login)
 * 
 * Recebe credenciais do formulário de login, valida com o banco de dados
 * e cria a sessão do usuário.
 */

require_once __DIR__ . '/functions.php';

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Valida os dados de entrada
$errors = validate_login($_POST);
if ($errors) {
    set_flash(implode(' ', $errors), 'error');
    header('Location: login.php');
    exit;
}

// Obtém e limpa os dados do formulário
$email = trim($_POST['email']);
$senha = $_POST['senha'];

try {
    // Busca o usuário no banco pelo email
    $stmt = $pdo->prepare('SELECT id, email, senha, classe FROM usuarios WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    // Verifica se o usuário existe e valida a senha
    // password_verify() compara a senha com o hash armazenado
    if (!$user || !password_verify($senha, $user['senha'])) {
        set_flash('E-mail ou senha inválidos. Verifique e tente novamente.', 'error');
        header('Location: login.php');
        exit;
    }

    // Se credenciais são válidas, cria a sessão
    $_SESSION['user'] = [
        'id' => $user['id'],
        'email' => $user['email'],
        'classe' => $user['classe'], // 'admin' ou 'lojista'
    ];

    set_flash('Login realizado com sucesso.', 'success');
    header('Location: index.php');
    exit;
} catch (PDOException $e) {
    set_flash('Erro ao autenticar. Tente novamente mais tarde.', 'error');
    header('Location: login.php');
    exit;
}
