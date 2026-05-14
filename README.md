# Tech Watch - Sistema CRUD de Loja de Relógios

Este é um projeto de **CRUD** (Create, Read, Update, Delete) desenvolvido em **PHP puro** com **MySQL/MariaDB**. O sistema permite gerenciar uma loja de relógios com login de usuários, diferentes níveis de acesso e validações de segurança.

## 🚀 Funcionalidades

- ✅ **Login de usuários** com senhas criptografadas
- ✅ **Dois tipos de acesso**: Admin (total) e Lojista (sem deletar)
- ✅ **CRUD completo**: Criar, Listar, Editar e Deletar relógios
- ✅ **Filtros inteligentes**: Buscar por marca ou tipo
- ✅ **Validações**: Todos os dados são validados antes de salvar
- ✅ **Segurança**: Proteção contra SQL Injection e XSS

## 📋 Requisitos

- **PHP** 8.0.30
- **XAMPP** ou servidor local equivalente (escolher versão com PHP 8.0.30 e mysql/mariadb)

## 🛠️ Instalação

### 1. Posicionar os arquivos

Coloque a pasta do projeto em:
```
C:\xampp\htdocs\projeto-crud-uninove\
```

### 2. Criar o banco de dados

#### Opção A: phpMyAdmin
1. Acesse `http://localhost/phpmyadmin`
2. Clique em **Importar**
3. Selecione o arquivo `schema.sql`
4. Clique em **Executar**

#### Opção B: Terminal MySQL
```bash
mysql -u root -p < schema.sql
```

## 👥 Credenciais padrão

| Email | Senha | Tipo |
|-------|-------|------|
| admin@loja.com | admin123 | Admin (pode excluir) |
| lojista@loja.com | lojista123 | Lojista (sem acesso a exclusão) |

## 🌐 Como usar

1. Inicie Apache e MySQL no XAMPP
2. Acesse `http://localhost/projeto-crud-uninove/login.php`
3. Faça login com uma das credenciais acima
4. Gerencie os relógios através da interface

### Operações

| Ação | Descrição |
|------|-----------|
| **Listar** | Visualize todos os relógios com preço e estoque |
| **Buscar** | Filtre por marca ou tipo de relógio |
| **Criar** | Cadastre novo relógio com marca, cor, tipo, preço e estoque |
| **Editar** | Modifique os dados do relógio |
| **Excluir** | Delete relógios (somente administradores) |

## 📁 Estrutura do projeto

```
projeto-crud-uninove/
├── db.php              # Configuração do banco de dados
├── functions.php       # Funções utilitárias e validações
├── header.php          # Cabeçalho, navegação e scripts
├── styles.css          # Estilos CSS
├── schema.sql          # Script de criação do banco
│
├── login.php           # Página de autenticação
├── autenticar.php      # Processamento de login
├── logout.php          # Encerramento de sessão
│
├── index.php           # Listagem e filtros de relógios
├── create.php          # Formulário de criação
├── store.php           # Processamento de criação
├── edit.php            # Formulário de edição
├── update.php          # Processamento de edição
├── delete.php          # Processamento de exclusão
│
└── README.md           # Este arquivo
```

## 📊 Banco de dados

### Tabela: `usuarios`
```sql
id              INT PRIMARY KEY AUTO_INCREMENT
email           VARCHAR(100) UNIQUE NOT NULL
senha           VARCHAR(255) NOT NULL
classe          ENUM('admin', 'lojista')
```

### Tabela: `relogios`
```sql
id                  INT PRIMARY KEY AUTO_INCREMENT
marca               VARCHAR(100) NOT NULL
cor_pulseira        VARCHAR(50) NOT NULL
tipo                ENUM('smart', 'analogico', 'digital') NOT NULL
preco               DECIMAL(10, 2) NOT NULL
quantidade_estoque  INT NOT NULL
UNIQUE KEY (marca, cor_pulseira)
```

## 🔒 Segurança

- **Senhas criptografadas** com `password_hash()` (bcrypt) - impossível recuperar a senha original
- **Prepared statements** na query SQL - previne ataques de SQL Injection
- **Sanitização XSS** com `htmlspecialchars()` - evita código malicioso no HTML
- **Validação no servidor** - não confia apenas na validação do navegador
- **Controle de sessão** - apenas usuários logados acessam as funções
- **Restrição de acesso** - admin e lojista têm permissões diferentes

## 📚 Conceitos Importantes

### O que é CRUD?
**CRUD** significa:
- **C**reate: Criar novo registro
- **R**ead: Ler/listar registros
- **U**pdate: Editar registro
- **D**elete: Deletar registro

Este projeto implementa todas essas operações em relação aos relógios.

### Prepared Statements
São comandos SQL especiais que separam os dados do código, impedindo SQL Injection.

```php
// SEM prepared statement (PERIGOSO!)
$query = "SELECT * FROM usuarios WHERE email = '" . $_POST['email'] . "'";

// COM prepared statement (SEGURO!)
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
$stmt->execute([':email' => $_POST['email']]);
```

### Duas Permissões
- **Admin**: Pode criar, editar e deletar relógios
- **Lojista**: Pode criar e editar, mas NÃO pode deletar

Esto é verificado na função `require_admin()` que impede acesso não autorizado.



## 🆘 Troubleshooting

| Problema | Solução |
|----------|---------|
| Erro de conexão com BD | Verifique se MySQL está rodando e senha está correta em `db.php` |
| Erro 404 em acesso | Certifique-se que a pasta está em `C:\xampp\htdocs\projeto-crud-uninove\` |
| Erro ao importar schema.sql | Verifique se o banco já existe; delete e importe novamente |
| Modal não abre | Verifique se JavaScript está habilitado no navegador |

---

**Desenvolvido para**: Universidade Nove de Julho (UNINOVE)  
**Disciplina**: Análise e Desenvolvimento de Sistemas