# 📖 Explicação Detalhada do Projeto - Tech Watch

## 🎯 O que é este projeto?

É um **sistema de gerenciamento de relógios** desenvolvido em **PHP com MySQL**. O projeto permite:
- **Login de usuários** (autenticação)
- **Listar relógios** (READ)
- **Criar novo relógio** (CREATE)
- **Editar relógio** (UPDATE)
- **Deletar relógio** (DELETE - apenas admin)
- **Filtrar relógios** por marca e tipo

---

## 📂 Estrutura de Arquivos

```
projeto-crud-uninove/
├── schema.sql          → Estrutura do banco de dados
├── db.php              → Conexão entre PHP e MySQL
├── functions.php       → Funções reutilizáveis
├── header.php          → Template HTML base
├── login.php           → Página de login
├── autenticar.php      → Processa login
├── logout.php          → Encerra sessão
├── index.php           → Lista relógios
├── create.php          → Formulário novo relógio
├── store.php           → Salva novo relógio
├── edit.php            → Formulário editar relógio
├── update.php          → Atualiza relógio
├── delete.php          → Deleta relógio
├── styles.css          → Estilos do site
└── README.md           → Documentação
```

---

## 🗄️ Banco de Dados (schema.sql)

### Tabela `usuarios`
```sql
CREATE TABLE IF NOT EXISTS usuarios ( --- Cria tabela usuarios caso ela nao exista
    id INT AUTO_INCREMENT PRIMARY KEY, ---Define o id automaticamente
    email VARCHAR(100) NOT NULL UNIQUE,      -- Email único para login
    senha VARCHAR(255) NOT NULL,              -- Senha
    classe ENUM('admin', 'lojista') NOT NULL -- Tipo de acesso
)
```
**Login para acesso:**
- **admin@loja.com** / **admin123** → Acesso total (pode deletar produtos)
- **lojista@loja.com** / **lojista123** → Acesso limitado (não pode deletar)

### Tabela `relogios`
```sql
CREATE TABLE IF NOT EXISTS relogios ( --- Cria a tabela relogio caso ela nao exista
    id INT AUTO_INCREMENT PRIMARY KEY, --- Define o id automaticamente
    marca VARCHAR(100) NOT NULL,              -- Ex: Casio, Apple, Samsung
    cor_pulseira VARCHAR(50) NOT NULL,        -- Ex: Preto, Azul, Dourado
    tipo ENUM('smart', 'analogico', 'digital') NOT NULL,  -- Tipo do relógio
    preco DECIMAL(10, 2) NOT NULL,            -- Preço em reais
    quantidade_estoque INT NOT NULL,          -- Quantidade disponível
    UNIQUE KEY unique_marca_cor (marca, cor_pulseira) -- Não permite duplicar produto da mesma marca e cor
)
```

---

## 🔐 Arquivo: db.php
**O que faz:** Conecta o PHP com o banco de dados

---

## ⚙️ Arquivo: functions.php
**O que faz:** Centraliza todas as funções reutilizáveis

### Funções de Autenticação

#### `is_logged_in()` - Verifica se usuário está logado
```php
return !empty($_SESSION['user']);
```
Retorna `true` se existe usuário na sessão.

#### `require_login()` - Força autenticação
```php
if (!is_logged_in()) {
    set_flash('Acesso restrito...', 'error'); ///mensagem temporaria
    header('Location: login.php');
    exit;
}
```

#### `is_admin()` - Verifica se é administrador
```php
return $_SESSION['user']['classe'] === 'admin';
```

#### `require_admin()` - Força acesso admin
Checa login + verifica se é admin, senão redireciona.

### Funções de Mensagens (Flash Messages)

#### `set_flash()` - Guarda mensagem para próxima página
```php
$_SESSION['flash'] = [
    'message' => 'Relógio criado!',
    'type' => 'success' // ou 'error'
];
```
**Uso:** Salva mensagem que desaparece após ser exibida (tipo um aviso único).

#### `get_flash()` - Recupera e limpa a mensagem
```php
$flash = get_flash(); // Lê e deleta da sessão
```

### Funções de Segurança

#### `esc()` - Protege contra XSS
```php
htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
```
Converte caracteres especiais `<` e `>` para `&lt;` e `&gt;`.
**Exemplo:** `<script>alert('hack')</script>` vira `&lt;script&gt;...`

### Funções de Validação

#### `validate_relogio()` - Valida dados do relógio
Verifica se:
- ✅ Marca está preenchida e na lista válida
- ✅ Cor está preenchida e na lista válida
- ✅ Tipo é 'smart', 'analogico' ou 'digital'
- ✅ Preço é número maior que zero
- ✅ Estoque é número inteiro não-negativo

Retorna array com erros (vazio = válido).

#### `validate_login()` - Valida credenciais
Verifica se:
- ✅ Email está preenchido e é válido
- ✅ Senha está preenchida

#### `get_marcas()` - Lista marcas disponíveis
```php
['Casio', 'Timex', 'Orient', 'Citizen', 'Seiko', 'Apple', 'Samsung', ...]
```

#### `get_cores()` - Lista cores disponíveis
```
['Preto', 'Marrom', 'Azul escuro', 'Prata', 'Dourado', ...]
```

#### `check_marca_cor_exists()` - Verifica duplicatas
```php
// Na criação
check_marca_cor_exists('Apple', 'Preto');

check_marca_cor_exists('Apple', 'Preto', 5);
```

---

## 🔑 Arquivo: login.php
**O que faz:** Exibe o formulário de login

```php
if (is_logged_in()) {
    header('Location: index.php');
    exit; // Se já está logado, vai direto para lista
}
```

---

## 🔐 Arquivo: autenticar.php
**O que faz:** Processa o login

**Fluxo:**
1. Valida dados com `validate_login()`
2. Se erro → exibe mensagem e volta para login
3. Busca usuário no BD por email
4. Compara senha com hash usando `password_verify()`
5. Se OK → cria sessão do usuário
6. Redireciona para index.php

```php
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = :email');
if (!password_verify($senha, $user['senha'])) {
    set_flash('Email ou senha inválidos', 'error');
    exit;
}
$_SESSION['user'] = ['id' => ..., 'email' => ..., 'classe' => ...];
```

**Por que usar `password_verify()`?** Porque as senhas são armazenadas com **hash criptográfico**, não em texto plano.

---

## 🚪 Arquivo: logout.php
**O que faz:** Encerra a sessão

```php
session_unset();      // Limpa variáveis de sessão
session_destroy();    // Destroi a sessão
setcookie(...);       // Remove cookie do navegador
header('Location: login.php'); // Volta para login
```

---

## 📖 Arquivo: index.php (READ - Listar)
**O que faz:** Exibe lista de relógios com filtros

**Fluxo:**
1. Requer login com `require_login()`
2. Recupera filtros da URL: `?marca=Apple&tipo=smart`
3. Constrói SQL dinâmica:
   ```php
   $query = 'SELECT * FROM relogios WHERE 1=1';
   if ($marca !== '') {
       $query .= ' AND marca LIKE :marca';
       $params[':marca'] = '%' . $marca . '%';
   }
   ```
4. Executa com prepared statement (seguro contra SQL Injection)
5. Exibe tabela com os resultados

---

## ➕ Arquivo: create.php (CREATE - Formulário)
**O que faz:** Exibe formulário para novo relógio

```php
require_login(); // Requer estar logado
$marcas = get_marcas(); // Busca lista de marcas
$cores = get_cores();   // Busca lista de cores
```

**Formulário:**
```html
<form action="store.php" method="post">
    <select name="marca" required>...</select>
    <select name="cor_pulseira" required>...</select>
    <select name="tipo" required>...</select>
    <input name="preco" required>
    <input name="quantidade_estoque" type="number" required>
    <button type="submit">Salvar</button>
</form>
```

---

## 💾 Arquivo: store.php (CREATE - Salvar)
**O que faz:** Processa criação de novo relógio

**Fluxo:**
1. Valida método POST
2. Valida dados com `validate_relogio()`
3. Se erro → exibe mensagem e volta para create.php
4. Verifica se marca+cor já existe com `check_marca_cor_exists()`
5. Se existe → erro (não permite duplicatas)
6. Se OK → insere no BD com prepared statement
7. Redireciona para index.php

```php
$stmt = $pdo->prepare('INSERT INTO relogios VALUES (...)');
$stmt->execute([
    ':marca' => $marca,
    ':cor_pulseira' => $cor,
    ':tipo' => $tipo,
    ':preco' => floatval($preco), // Converte para número
    ':quantidade_estoque' => intval($estoque) // Converte para inteiro
]);
```

---

## ✏️ Arquivo: edit.php (UPDATE - Formulário)
**O what faz:** Exibe formulário pré-preenchido para editar

**Fluxo:**
1. Requer login
2. Obtém ID da URL: `?id=5`
3. Valida ID com `filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)`
4. Busca relógio no BD
5. Se não encontrar → redireciona com erro
6. Preenche formulário com dados atuais
7. Envia para `update.php`

```php
<form action="update.php" method="post">
    <input type="hidden" name="id" value="<?= $relogio['id'] ?>">
    ...
    <input name="marca" value="<?= $relogio['marca'] ?>">
</form>
```

---

## 🔄 Arquivo: update.php (UPDATE - Salvar)
**O que faz:** Processa atualização de relógio

**Fluxo:**
1. Valida POST
2. Valida ID e dados
3. Verifica se marca+cor não está sendo usada por **outro** relógio
   ```php
   check_marca_cor_exists($marca, $cor, $id) // $id = exclui ele mesmo
   ```
4. Se OK → UPDATE no BD
5. Redireciona para index.php

---

## 🗑️ Arquivo: delete.php (DELETE - Excluir)
**O que faz:** Deleta um relógio

**Segurança:**
```php
require_admin(); // SÓ ADMIN PODE DELETAR!
```

**Fluxo:**
1. Verifica se é admin
2. Obtém e valida ID da URL
3. Executa DELETE no BD
4. Redireciona para index.php

```php
$stmt = $pdo->prepare('DELETE FROM relogios WHERE id = :id');
$stmt->execute([':id' => $id]);
```

---

## 🎨 Arquivo: styles.css
**O que faz:** Estilos visuais do site

Principais classes:
- `.card` → caixa com bordas e sombra
- `.button` → botão padrão
- `.button.danger` → botão vermelho para deletar
- `.flash.success` → mensagem verde
- `.flash.error` → mensagem vermelha
- `.modal` → janela de diálogo
- `.table` → tabela de relógios

---

## 🔒 Conceitos de Segurança Implementados

### 1. **SQL Injection Prevention**
```php
// ❌ ERRADO (vulnerável):
$query = "SELECT * FROM usuarios WHERE email = '$email'";

// ✅ CERTO (seguro):
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
$stmt->execute([':email' => $email]);
```
**Prepared Statements** separam código SQL dos dados.

### 2. **XSS Prevention**
```php
<h1><?= esc($usuario_input) ?></h1>
```
A função `esc()` converte `<` em `&lt;`, impedindo scripts maliciosos.

### 3. **Password Hashing**
```php
$hash = password_hash('senha123', PASSWORD_BCRYPT);
if (password_verify('senha123', $hash)) {
    // Senha correta!
}
```
Senhas nunca são armazenadas em texto plano, sempre com hash criptográfico.

---

## 📊 Fluxo Completo: Criar um Relógio

```
1. Usuário acessa /create.php
   ↓
2. create.php valida login e carrega formulário
   ↓
3. Usuário preenche e clica "Salvar"
   ↓
4. Dados vão para /store.php (POST)
   ↓
5. store.php valida tudo
   - Marca existente?
   - Cor existente?
   - Preço positivo?
   - Marca+Cor não duplica?
   ↓
6. Se OK → INSERT no BD
   Se erro → volta para create.php com mensagem
   ↓
7. Redireciona para index.php com mensagem de sucesso
```

---

## 📊 Fluxo Completo: Login

```
1. Usuário acessa /login.php
   ↓
2. Já está logado?
   - SIM → redireciona para index.php
   - NÃO → exibe formulário
   ↓
3. Preenche email e senha, clica "Entrar"
   ↓
4. Dados vão para /autenticar.php (POST)
   ↓
5. autenticar.php valida
   - Email vazio?
   - Email é válido?
   - Senha vazia?
   ↓
6. Busca usuário no BD
   ↓
7. Verifica password_verify(senha, hash)
   - Errado → volta com mensagem
   - Certo → cria $_SESSION['user']
   ↓
8. Redireciona para index.php
```

---

## 🎓 Perguntas Que o Professor Pode Fazer

### 1. **Por que usar PDO?**
Resposta: PDO oferece proteção contra SQL Injection, abstração de banco de dados e tratamento de erros.

### 2. **O que é prepared statement?**
Resposta: Separa código SQL dos dados, evitando injeção. Os valores vêm depois com `execute()`.

### 3. **O que `esc()` faz?**
Resposta: Converte caracteres especiais para HTML entities, prevenindo XSS attacks.

### 4. **Por que usar `filter_input()`?**
Resposta: Valida e sanitiza dados de entrada (GET, POST, etc).

### 5. **O que é uma flash message?**
Resposta: Mensagem que aparece uma única vez e desaparece após refresh.

---

**Bom estudo! 🎉**
