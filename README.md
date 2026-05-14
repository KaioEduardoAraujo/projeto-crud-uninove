# Tech Watch - Sistema CRUD de Gerenciamento de Relógios

Este é um projeto de **CRUD** (Create, Read, Update, Delete) desenvolvido em **PHP** com **MySQL/MariaDB** para o projeto da universidade. O sistema permite gerenciar uma loja de relógios com login de usuários, diferentes níveis de acesso e recursos padrões de CRUD.

## 📋 Requisitos

- **PHP 8**
- **XAMPP** ou servidor local equivalente

## 🛠️ Instalação

### 1. Posicionar os arquivos

Clone o projeto em:
```
C:\xampp\htdocs\
```

### 2. Criar o banco de dados

1. Acesse `http://localhost/phpmyadmin`
2. Clique em **SQL**
3. Cole o código do arquivo `schema.sql`
4. Clique em **Executar**

## 👥 Credenciais padrão

| Email | Senha | Tipo |
|-------|-------|------|
| admin@loja.com | admin123 | Admin (pode excluir) |
| lojista@loja.com | lojista123 | Lojista (sem acesso a exclusão) |

## 🌐 Como usar

1. Inicie Apache e MySQL no XAMPP
2. Acesse `http://localhost/projeto-crud-uninove/`
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

### Permissões
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
