# PROJETO PARA AULA DE ADS UNINOVE
# Sistema CRUD - Loja de Relógios

Sistema CRUD completo em PHP para gerenciamento de uma loja de relógios, utilizando PDO e MySQL/MariaDB.

## 🚀 Funcionalidades v2.0

- ✅ Autenticação de usuários (Admin e Lojista)
- ✅ CRUD completo de relógios (com preço e estoque)
- ✅ Campos adicionais: preço e quantidade em estoque
- ✅ Busca e filtro por marca
- ✅ Filtro por tipo de relógio (Smart, Analógico, Digital)
- ✅ Confirmação de exclusão de produtos
- ✅ Controle de acesso baseado em roles
- ✅ Interface moderna com espaçamento melhorado
- ✅ Ícones nos botões de ação (✏️ editar, 🗑️ excluir)
- ✅ Cards com bordas arredondadas
- ✅ Validações de entrada
- ✅ Mensagens de sucesso/erro
- ✅ Design responsivo para mobile

## 📋 Requisitos

- PHP 7.4 ou superior
- MySQL/MariaDB 5.7 ou superior
- XAMPP (recomendado) ou qualquer servidor local

## ✨ Melhorias Implementadas (v2.0)

### Novos Campos
- **Preço**: Campo decimal para registrar o preço de cada relógio
- **Quantidade em Estoque**: Controle de inventário dos produtos

### Sistema de Busca e Filtros
- Filtro por marca (busca textual)
- Filtro por tipo (dropdown com opções)
- Botão "Buscar" para aplicar filtros
- Botão "Limpar Filtros" para resetar a busca

### Melhorias de UI/UX
- Cards com bordas mais arredondadas (border-radius: 16px)
- Espaçamento aumentado em todos os elementos
- Padding melhorado em formulários e inputs
- Ícones nos botões (✏️ Editar, 🗑️ Excluir, 🔍 Buscar)
- Hover effects suaves em botões
- Confirmação aprimorada ao excluir: "Tem certeza que deseja excluir este produto?"
- Design responsivo otimizado para mobile


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

#### ⚠️ Se você já tem a versão anterior
Se você já possui o banco de dados com a versão anterior, execute estes comandos SQL para atualizar:

```sql
ALTER TABLE relogios ADD COLUMN preco DECIMAL(10, 2) NOT NULL DEFAULT 0;
ALTER TABLE relogios ADD COLUMN quantidade_estoque INT NOT NULL DEFAULT 0;
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

### Operações disponíveis:
- **Listar Relógios**: Visualize todos os relógios com preço e estoque
- **Filtrar por Marca**: Use a caixa de busca para encontrar relógios de uma marca específica
- **Filtrar por Tipo**: Selecione o tipo (Smart, Analógico ou Digital) no dropdown
- **Novo Relógio**: Clique em "Novo Relógio" para cadastrar um novo item com preço e quantidade
- **Editar Relógio**: Clique em ✏️ para editar marca, cor, tipo, preço e estoque
- **Excluir Relógio**: Clique em 🗑️ (apenas administradores) - será solicitada confirmação

## 📁 Estrutura do projeto

```
loja-relogios/
├── db.php              # Conexão com banco de dados
├── functions.php       # Funções auxiliares
├── header.php          # Cabeçalho e navegação
├── styles.css          # Estilos CSS
├── schema.sql          # Estrutura do banco
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
- `preco` (DECIMAL 10,2) - Preço do relógio
- `quantidade_estoque` (INT) - Quantidade disponível em estoque


## 🆘 Suporte

Se encontrar problemas:
1. Verifique se o XAMPP está rodando
2. Confirme se o banco foi criado corretamente
3. Verifique se não há nenhum outro software utilizando as mesmas portas que o banco de dados e o servidor local

---

Projeto desenvolvido para trabalho da Universidade Nove de Julho