# Tech Watch - Sistema CRUD de Loja de Relógios

Sistema CRUD completo desenvolvido em **PHP puro** com **MySQL/MariaDB** para gerenciamento de uma loja de relógios. Implementa autenticação de usuários, controle de acesso baseado em roles e gestão completa de inventário.

## 🚀 Funcionalidades

- ✅ **Autenticação segura** com controle de acesso (Admin e Lojista)
- ✅ **CRUD completo** de relógios (Create, Read, Update, Delete)
- ✅ **Seleção validada** de marca e cor
- ✅ **Busca e filtros** por marca e tipo de relógio
- ✅ **Prepared statements** para segurança contra SQL Injection

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

- **Senhas criptografadas** com `password_hash()` (algoritmo bcrypt)
- **Prepared statements** para prevenir SQL Injection
- **Validação de entrada** no servidor
- **Controle de sessão** com proteção de acesso
- **Validação de tipo de dados** em formulários

## 🎨 Tecnologias utilizadas

- **Backend**: PHP 7.4+
- **Banco**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3
- **Segurança**: PDO, Prepared Statements

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