<?php
// functions.php
// Funções comuns para sessão, validação e proteção de páginas.
session_start();
require_once __DIR__ . '/db.php';

function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('Acesso restrito. Faça login para continuar.', 'error');
        header('Location: login.php');
        exit;
    }
}

function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        set_flash('Ação não permitida. Apenas administradores podem acessar.', 'error');
        header('Location: index.php');
        exit;
    }
}

function is_admin(): bool
{
    return is_logged_in() && isset($_SESSION['user']['classe']) && $_SESSION['user']['classe'] === 'admin';
}

function set_flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function validate_relogio(array $data): array
{
    $errors = [];
    $data['marca'] = trim($data['marca'] ?? '');
    $data['cor_pulseira'] = trim($data['cor_pulseira'] ?? '');
    $data['tipo'] = trim($data['tipo'] ?? '');

    if ($data['marca'] === '') {
        $errors[] = 'O campo marca é obrigatório.';
    } elseif (mb_strlen($data['marca']) > 100) {
        $errors[] = 'A marca não pode ter mais de 100 caracteres.';
    }

    if ($data['cor_pulseira'] === '') {
        $errors[] = 'O campo cor da pulseira é obrigatório.';
    } elseif (mb_strlen($data['cor_pulseira']) > 50) {
        $errors[] = 'A cor da pulseira não pode ter mais de 50 caracteres.';
    }

    $allowedTipos = ['smart', 'analogico', 'digital'];
    if (!in_array($data['tipo'], $allowedTipos, true)) {
        $errors[] = 'O tipo selecionado não é válido.';
    }

    return $errors;
}

function validate_login(array $data): array
{
    $errors = [];
    $data['email'] = trim($data['email'] ?? '');
    $data['senha'] = $data['senha'] ?? '';

    if ($data['email'] === '') {
        $errors[] = 'O campo e-mail é obrigatório.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'O e-mail informado não é válido.';
    }

    if ($data['senha'] === '') {
        $errors[] = 'O campo senha é obrigatório.';
    }

    return $errors;
}
