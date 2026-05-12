<?php
// header.php
// Cabeçalho e menu de navegação usados em todas as páginas.
require_once __DIR__ . '/functions.php';
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Watch</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div>
                <h1>Tech Watch</h1>
                <p>Seja bem-vindo à Tech Watch!</p>
            </div>
            <?php if (is_logged_in()): ?>
                <div class="user-info">
                    <span>Olá, <?= esc($_SESSION['user']['email']) ?> (<?= esc($_SESSION['user']['classe']) ?>)</span>
                    <a href="logout.php" class="button secondary">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <nav class="main-nav">
        <div class="container">
            <?php if (is_logged_in()): ?>
                <a href="index.php">Relógios</a>
                <a href="create.php">Novo Relógio</a>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <main class="container">
        <?php if ($flash): ?>
            <div class="flash <?= esc($flash['type']) ?>"> <?= esc($flash['message']) ?> </div>
        <?php endif; ?>
