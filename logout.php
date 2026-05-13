<?php
/**
 * Página de Logout
 * 
 * Encerra a sessão do usuário, destruindo todas as variáveis de sessão
 * e redirecionando para a página de login.
 */

require_once __DIR__ . '/functions.php';

// Limpa todas as variáveis da sessão
session_unset();

// Destroi a sessão
session_destroy();

// Remove o cookie de sessão do navegador
setcookie(session_name(), '', time() - 3600, '/');

// Redireciona para login
header('Location: login.php');
exit;
