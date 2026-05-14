<?php
session_start();
require_once __DIR__ . '/db.php';

// Verifica se o usuário está logado
function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

// Força o usuário fazer login antes de acessar a página
function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('Acesso restrito. Faça login para continuar.', 'error');
        header('Location: login.php');
        exit;
    }
}

// Só permite acesso se for admin
function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        set_flash('Ação não permitida. Apenas administradores podem acessar.', 'error');
        header('Location: index.php');
        exit;
    }
}

// Verifica se o usuário é admin
function is_admin(): bool
{
    return is_logged_in() && isset($_SESSION['user']['classe']) && $_SESSION['user']['classe'] === 'admin';
}

// Salva uma mensagem para mostrar na próxima página
function set_flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

// Pega a mensagem e a apaga da sessão
function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// Protege o texto contra ataques XSS (deixa seguro pra mostrar no HTML)
function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Valida os dados do relógio (marca, cor, tipo, preço, estoque)
function validate_relogio(array $data): array
{
    $errors = [];
    $data['marca'] = trim($data['marca'] ?? '');
    $data['cor_pulseira'] = trim($data['cor_pulseira'] ?? '');
    $data['tipo'] = trim($data['tipo'] ?? '');
    $data['preco'] = trim($data['preco'] ?? '');
    $data['quantidade_estoque'] = trim($data['quantidade_estoque'] ?? '');

    // Valida marca
    if ($data['marca'] === '') {
        $errors[] = 'O campo marca é obrigatório.';
    } elseif (!in_array($data['marca'], get_marcas())) {
        $errors[] = 'A marca selecionada não é válida.';
    }

    // Valida cor da pulseira
    if ($data['cor_pulseira'] === '') {
        $errors[] = 'O campo cor da pulseira é obrigatório.';
    } elseif (!in_array($data['cor_pulseira'], get_cores())) {
        $errors[] = 'A cor selecionada não é válida.';
    }

    // Valida tipo
    $allowedTipos = ['smart', 'analogico', 'digital'];
    if (!in_array($data['tipo'], $allowedTipos, true)) {
        $errors[] = 'O tipo selecionado não é válido.';
    }

    // Valida preço
    if ($data['preco'] === '') {
        $errors[] = 'O campo preço é obrigatório.';
    } elseif (!is_numeric($data['preco'])) {
        $errors[] = 'O preço deve ser um número válido.';
    } elseif (floatval($data['preco']) <= 0) {
        $errors[] = 'O preço deve ser maior que zero.';
    }

    // Valida quantidade em estoque
    if ($data['quantidade_estoque'] === '') {
        $errors[] = 'O campo quantidade em estoque é obrigatório.';
    } elseif (!is_numeric($data['quantidade_estoque']) || floatval($data['quantidade_estoque']) != intval($data['quantidade_estoque'])) {
        $errors[] = 'A quantidade deve ser um número inteiro válido.';
    } elseif (intval($data['quantidade_estoque']) < 0) {
        $errors[] = 'A quantidade em estoque não pode ser negativa.';
    }

    return $errors;
}

// Valida email e senha do formulário de login
function validate_login(array $data): array
{
    $errors = [];
    $data['email'] = trim($data['email'] ?? '');
    $data['senha'] = $data['senha'] ?? '';

    // Valida email
    if ($data['email'] === '') {
        $errors[] = 'O campo e-mail é obrigatório.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'O e-mail informado não é válido.';
    }

    // Valida senha
    if ($data['senha'] === '') {
        $errors[] = 'O campo senha é obrigatório.';
    }

    return $errors;
}

// Retorna as marcas de relógio disponíveis
function get_marcas(): array
{
    return [
        'Casio', 'Timex', 'Orient', 'Citizen', 'Seiko',
        'Apple', 'Samsung', 'Garmin', 'Xiaomi', 'Huawei', 'Amazfit'
    ];
}

// Retorna as cores de pulseira disponíveis
function get_cores(): array
{
    return [
        'Preto', 'Marrom', 'Azul escuro', 'Prata', 'Dourado',
        'Branco', 'Verde', 'Vermelho'
    ];
}

// Verifica se já existe um relógio com essa marca e cor
// $exclude_id é usado na edição para não comparar o relógio com ele mesmo
function check_marca_cor_exists(string $marca, string $cor, ?int $exclude_id = null): bool
{
    global $pdo;
    
    if ($exclude_id === null) {
        $stmt = $pdo->prepare('SELECT id FROM relogios WHERE marca = :marca AND cor_pulseira = :cor LIMIT 1');
        $stmt->execute([':marca' => $marca, ':cor' => $cor]);
    } else {
        // Na edição, exclui o relógio atual para não conflitar consigo mesmo
        $stmt = $pdo->prepare('SELECT id FROM relogios WHERE marca = :marca AND cor_pulseira = :cor AND id != :id LIMIT 1');
        $stmt->execute([':marca' => $marca, ':cor' => $cor, ':id' => $exclude_id]);
    }
    
    return $stmt->fetch() !== false;
}
