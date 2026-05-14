<?php
session_start();
require_once __DIR__ . '/db.php';

/**
 * Verifica se um usuário está autenticado
 * @return bool True se há usuário na sessão
 */
function is_logged_in(): bool
{
    return !empty($_SESSION['user']);
}

/**
 * Força autenticação - Redireciona para login se não autenticado
 */
function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('Acesso restrito. Faça login para continuar.', 'error');
        header('Location: login.php');
        exit;
    }
}

/**
 * Força acesso de administrador - Redireciona se não for admin
 */
function require_admin(): void
{
    require_login();
    if (!is_admin()) {
        set_flash('Ação não permitida. Apenas administradores podem acessar.', 'error');
        header('Location: index.php');
        exit;
    }
}

/**
 * Verifica se o usuário logado é administrador
 * @return bool True se classe == 'admin'
 */
function is_admin(): bool
{
    return is_logged_in() && isset($_SESSION['user']['classe']) && $_SESSION['user']['classe'] === 'admin';
}

/**
 * Define uma mensagem flash para exibir uma única vez
 * @param string $message Mensagem a exibir
 * @param string $type Tipo: 'success' ou 'error'
 */
function set_flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type,
    ];
}

/**
 * Recupera e limpa a mensagem flash
 * @return array|null Array com 'message' e 'type' ou null
 */
function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Sanitiza valor para segurança XSS
 * @param string $value Valor a sanitizar
 * @return string Valor escapado para saída em HTML
 */
function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Valida dados de um relógio antes de inserir/atualizar
 * 
 * Valida:
 * - Marca: obrigatória e deve estar na lista pré-definida
 * - Cor: obrigatória e deve estar na lista pré-definida
 * - Tipo: deve ser 'smart', 'analogico' ou 'digital'
 * - Preço: numérico e maior que zero
 * - Quantidade em estoque: inteiro não-negativo
 * 
 * @param array $data Dados do formulário
 * @return array Array de mensagens de erro (vazio se válido)
 */
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

/**
 * Valida dados de login
 * 
 * Valida:
 * - Email: obrigatório e formato válido
 * - Senha: obrigatória
 * 
 * @param array $data Dados do formulário de login
 * @return array Array de mensagens de erro (vazio se válido)
 */
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

/**
 * Retorna lista de marcas pré-definidas
 * @return array Lista de marcas disponíveis
 */
function get_marcas(): array
{
    return [
        'Casio', 'Timex', 'Orient', 'Citizen', 'Seiko',
        'Apple', 'Samsung', 'Garmin', 'Xiaomi', 'Huawei', 'Amazfit'
    ];
}

/**
 * Retorna lista de cores de pulseira pré-definidas
 * @return array Lista de cores disponíveis
 */
function get_cores(): array
{
    return [
        'Preto', 'Marrom', 'Azul escuro', 'Prata', 'Dourado',
        'Branco', 'Verde', 'Vermelho'
    ];
}

/**
 * Verifica se já existe um relógio com essa combinação de marca + cor
 * 
 * Usada para validar a constraint de unicidade (UNIQUE KEY unique_marca_cor).
 * O parâmetro $exclude_id permite verificar sem contar o próprio relógio ao editar.
 * 
 * @param string $marca Marca do relógio
 * @param string $cor Cor da pulseira
 * @param int|null $exclude_id ID do relógio atual (null em criação, ID em edição)
 * @return bool True se a combinação já existe
 */
function check_marca_cor_exists(string $marca, string $cor, ?int $exclude_id = null): bool
{
    global $pdo;
    
    // Se não há ID para excluir, apenas verifica a existência
    if ($exclude_id === null) {
        $stmt = $pdo->prepare('SELECT id FROM relogios WHERE marca = :marca AND cor_pulseira = :cor LIMIT 1');
        $stmt->execute([':marca' => $marca, ':cor' => $cor]);
    } else {
        // Se há ID, verifica excluindo esse ID (para não validar contra ele mesmo em edições)
        $stmt = $pdo->prepare('SELECT id FROM relogios WHERE marca = :marca AND cor_pulseira = :cor AND id != :id LIMIT 1');
        $stmt->execute([':marca' => $marca, ':cor' => $cor, ':id' => $exclude_id]);
    }
    
    return $stmt->fetch() !== false;
}
