-- schema.sql
-- Estrutura da base de dados para a loja_relogio

CREATE DATABASE IF NOT EXISTS loja_relogio CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE loja_relogio;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    classe ENUM('admin', 'lojista') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS relogios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    marca VARCHAR(100) NOT NULL,
    cor_pulseira VARCHAR(50) NOT NULL,
    tipo ENUM('smart', 'analogico', 'digital') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO usuarios (email, senha, classe) VALUES
('admin@loja.com', '$2y$10$yJDdv4EFKwkqFi2W8yET4OynCF7N5cpDBEBHpe975VeYdX4aLDuBu', 'admin'),
('lojista@loja.com', '$2y$10$0HeAc/x5CCXu.zb5KG3YouZ1VHqryX9JRvAnvqtOAnR0kbk4WDoxm', 'lojista');

-- Usuário admin: admin@loja.com / admin123
-- Usuário lojista: lojista@loja.com / lojista123
