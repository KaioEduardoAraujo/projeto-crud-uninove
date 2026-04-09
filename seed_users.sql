-- seed_users.sql
-- Inserir usuários padrão no banco loja_relogio

INSERT INTO usuarios (email, senha, classe) VALUES
('admin@loja.com', '$2y$10$yJDdv4EFKwkqFi2W8yET4OynCF7N5cpDBEBHpe975VeYdX4aLDuBu', 'admin'),
('lojista@loja.com', '$2y$10$0HeAc/x5CCXu.zb5KG3YouZ1VHqryX9JRvAnvqtOAnR0kbk4WDoxm', 'lojista');