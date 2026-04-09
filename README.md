# PROJETO PARA AULA DE ADS UNINOVE
# Sistema CRUD - Loja de Relógios

Sistema CRUD completo em PHP para gerenciamento de uma loja de relógios, utilizando PDO e MySQL/MariaDB.

## 🚀 Funcionalidades

- ✅ Autenticação de usuários (Admin e Lojista)
- ✅ CRUD completo de relógios
- ✅ Controle de acesso baseado em roles
- ✅ Interface simples em HTML/CSS
- ✅ Validações de entrada
- ✅ Mensagens de sucesso/erro

## 📋 Requisitos

- PHP 7.4 ou superior
- MySQL/MariaDB 5.7 ou superior
- XAMPP (recomendado) ou qualquer servidor local

## 🛠️ Instalação

### 1. Clonar o repositório
```bash
git clone https://github.com/SEU_USUARIO/loja-relogios.git
cd loja-relogios
```

### 2. Configurar o banco de dados

#### Opção A: Usando phpMyAdmin
1. Abra `http://localhost/phpmyadmin`
2. Crie um banco chamado `loja_relogio`
3. Importe o arquivo `schema.sql`

#### Opção B: Usando linha de comando
```bash
mysql -u root -p < schema.sql
```

### 3. Configurar usuários
Execute o arquivo `seed_users.sql` no phpMyAdmin ou via linha de comando:
```bash
mysql -u root -p loja_relogio < seed_users.sql
```

## 👥 Usuários padrão

| Email | Senha | Tipo |
|-------|-------|------|
| admin@loja.com | admin123 | Admin (pode excluir) |
| lojista@loja.com | lojista123 | Lojista (não pode excluir) |

## 🌐 Como usar

1. Inicie o Apache e MySQL no XAMPP
2. Acesse `http://localhost/loja-relogios/login.php`
3. Faça login com um dos usuários acima
4. Gerencie os relógios através da interface

## 📁 Estrutura do projeto

```
loja-relogios/
├── db.php              # Conexão com banco de dados
├── functions.php       # Funções auxiliares
├── header.php          # Cabeçalho e navegação
├── styles.css          # Estilos CSS
├── schema.sql          # Estrutura do banco
├── seed_users.sql      # Dados iniciais
├── login.php           # Página de login
├── autenticar.php      # Processamento do login
├── logout.php          # Logout
├── index.php           # Lista de relógios
├── create.php          # Formulário de criação
├── store.php           # Salvar novo relógio
├── edit.php            # Formulário de edição
├── update.php          # Atualizar relógio
└── delete.php          # Excluir relógio
```

## 🔒 Segurança

- Senhas criptografadas com `password_hash()`
- Prepared statements para prevenir SQL Injection
- Validação de entrada
- Controle de sessão

## 📊 Banco de dados

### Tabela `usuarios`
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `email` (VARCHAR 100, UNIQUE)
- `senha` (VARCHAR 255)
- `classe` (ENUM: 'admin', 'lojista')

### Tabela `relogios`
- `id` (INT, PRIMARY KEY, AUTO_INCREMENT)
- `marca` (VARCHAR 100)
- `cor_pulseira` (VARCHAR 50)
- `tipo` (ENUM: 'smart', 'analogico', 'digital')

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/nova-feature`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova feature'`)
4. Push para a branch (`git push origin feature/nova-feature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 🆘 Suporte

Se encontrar problemas:
1. Verifique se o XAMPP está rodando
2. Confirme se o banco foi criado corretamente
3. Verifique os logs de erro do PHP
4. Abra uma issue no GitHub
5. Verifique se não há nenhum outro software utilizando as mesmas portas que o banco de dados e o servidor local

---

Desenvolvido em PHP puro para o projeto da universidade