<?php
require_once __DIR__ . '/functions.php';
if (is_logged_in()) {
    header('Location: index.php');
    exit;
}
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <div>
                <h1>Login</h1>
                <p>Acesse o sistema da Tech Watch.</p>
            </div>
        </div>
    </header>
    <main class="container">
        <?php if ($flash): ?>
            <div class="flash <?= esc($flash['type']) ?>"> <?= esc($flash['message']) ?> </div>
        <?php endif; ?>

        <div class="card">
            <form action="autenticar.php" method="post">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required>
                </div>
                <input type="submit" value="Entrar" class="button">
            </form>
        </div>
    </main>
</body>
</html>
