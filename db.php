<?php ///ARQUIVO DE CONEXÃO COM O BANCO DE DADOS
$host = 'localhost';
$db   = 'loja_relogio';
$user = 'root';
$pass = ''; // Senha padrão do XAMPP é vazia
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     die("Erro na conexão: " . $e->getMessage());
}