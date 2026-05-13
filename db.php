<?php
/**
 * Arquivo de Configuração e Conexão com o Banco de Dados
 * 
 * Define os parâmetros de conexão MySQL/MariaDB usando PDO.
 * PDO oferece uma camada de abstração segura contra SQL Injection.
 */

$host = 'localhost';
$db   = 'loja_relogio';
$user = 'root';
$pass = ''; // Senha padrão do XAMPP
$charset = 'utf8mb4';

// String de conexão (DSN - Data Source Name)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opções do PDO para melhor controle de erros e resultados
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays associativos
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}