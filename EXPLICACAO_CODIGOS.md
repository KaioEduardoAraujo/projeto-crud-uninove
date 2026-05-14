# 📖 Explicação Detalhada do Projeto - Tech Watch

## 🎯 O que é este projeto?

É um **sistema de gerenciamento de relógios** desenvolvido em **PHP com MySQL**. O projeto permite:
- **Login de usuários** (autenticação)
- **Listar relógios** (READ)
- **Criar novo relógio** (CREATE)
- **Editar relógio** (UPDATE)
- **Deletar relógio** (DELETE - apenas admin)
- **Filtrar relógios** por marca e tipo

## 📑 Índice

1. [Estrutura de Arquivos](#-estrutura-de-arquivos)
2. [Banco de Dados](#-banco-de-dados)
3. [Segurança Implementada](#-conceitos-de-segurança-implementados)
4. [Conceitos Importantes](#-conceitos-importantes)
5. [Explicação dos Arquivos](#-explicação-dos-arquivos)

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
Salva mensagem na sessão que será exibida uma única vez (veja seção de conceitos para mais detalhes).

#### `get_flash()` - Recupera e limpa a mensagem
Lê a mensagem da sessão e a remove.

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
Veja a seção **"Entendendo check_marca_cor_exists()"** mais adiante para detalhes.

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

### 1. **Prepared Statements (Evita SQL Injection)**
```php
// ❌ ERRADO (vulnerável):
$email = $_POST['email'];
$query = "SELECT * FROM usuarios WHERE email = '$email'";
// Se digitar: admin' OR '1'='1 ==> Query fica quebrada!

// ✅ CERTO (seguro):
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
$stmt->execute([':email' => $email]);
// Os dados ficam SEPARADOS da query SQL
```
**Por que funciona?** O `prepare()` apenas "configura" a query, depois `execute()` coloca os dados de forma segura, sem interpretar caracteres especiais.

### 2. **XSS Prevention (Evita scripts maliciosos)**
```php
// ❌ ERRADO:
echo "<h1>" . $_POST['nome'] . "</h1>";
// Se digitarem: <script>alert('hacked')</script>
// Isso executa o script!

// ✅ CORRETO:
echo "<h1>" . esc($_POST['nome']) . "</h1>";
// esc() converte < em &lt; e > em &gt;
// O navegador mostra o texto literal, não executa
```

### 3. **Password Hashing (Senhas com segurança)**
```php
// ❌ ERRADO:
$senha_hash = $_POST['senha']; // Texto plano no BD!

// ✅ CORRETO:
$senha_hash = password_hash($_POST['senha'], PASSWORD_BCRYPT);
// Gera hash impossível de reverter

// Ao fazer login:
if (password_verify('senha_digitada', $hash_do_BD)) {
    echo 'Login OK';
}
```
**Exemplo:** Se a senha `admin123` vira:
```
$2y$10$nOUIs5kJ7naTuTFkWK1Be.4kxDXrC6AJJQ1NwNvmhuMlL2MoQXikm
```
Nunca ninguém consegue descobrir que é `admin123` olhando o BD!

### 4. **Validação em Cascade (Um filtro após outro)**
Cada página que processa dados faz suas próprias validações:
```
create.php (página HTML)
    ↓
store.php (processa)
    ↓
validate_relogio() (valida cada campo)
    ↓
check_marca_cor_exists() (valida duplicata)
    ↓
PDO->prepare()->execute() (banco valida constraint UNIQUE)
```
Se qualquer validação falhar, volta com erro. Nada entra inválido no BD.

---

---

## 🔤 Entendendo filter_input()

Essa função filtra e valida dados de entrada:
```php
// De URL: ?id=5
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
// Retorna 5 se é inteiro válido
// Retorna FALSE se for: "abc", "5.5", "5a", etc

// De POST:
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
```

**Por que usar?** Para evitar erros. Se alguém digitar `/edit.php?id=abc`, o código não quebra, apenas não carrega o relógio.

---

## 💬 Entendendo Flash Messages

"Flash" significa que a mensagem aparece UMA ÚNICA VEZ e depois some:
```php
// Em store.php, após salvar:
set_flash('Relógio criado com sucesso!', 'success');
header('Location: index.php');

// Em index.php, carrega a mensagem:
$flash = get_flash();
if ($flash) {
    echo '<div class="flash ' . $flash['type'] . '">';
    echo $flash['message'];
    echo '</div>';
    // Depois que exibe, unset() na sessão, então não aparece mais
}
```
**Por que fazer assim?** Se recarregar a página, a mensagem não aparece de novo. É usada apenas uma vez.

---

## 🔍 Entendendo check_marca_cor_exists()

Essa função impede que dois relógios iguais (mesma marca E cor) sejam criados:

### Na criação (store.php):
```php
if (check_marca_cor_exists('Apple', 'Preto')) {
    erro('Já existe!');
    exit;
}
// Query: SELECT id FROM relogios WHERE marca = 'Apple' AND cor_pulseira = 'Preto'
```

### Na edição (update.php) - **CUIDADO!**
```php
// Se não passasse o $exclude_id, o relógio conflitaria com ele mesmo!
if (check_marca_cor_exists('Apple', 'Preto', 5)) { // 5 = ID atual
    erro('Já existe!');
    exit;
}
// Query: SELECT id FROM relogios 
//        WHERE marca = 'Apple' AND cor_pulseira = 'Preto' 
//        AND id != 5  // <-- Exclui ele mesmo
```
**Exemplo do problema:**
- Relógio ID 5: Apple, Preto
- Usuário edita e manda salvar mesmos dados
- Sem exclude_id: Encontraria o ID 5 e recusaria com erro!
- Com exclude_id=5: Ignora o ID 5 e deixa salvar

---

## 📄 Arquivo: index.php (READ - Listar)
**O que faz:** Exibe lista de relógios com filtros

### O código tricky: Filtros dinâmicos

```php
// Pega os filtros da URL: ?marca=Apple&tipo=smart
$marca = isset($_GET['marca']) ? trim($_GET['marca']) : '';
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

// Começa com query base (WHERE 1=1 é sempre true, é um truque)
$query = 'SELECT * FROM relogios WHERE 1=1';
$params = [];

// Só adiciona filtro de marca se preencheu
if ($marca !== '') {
    $query .= ' AND marca LIKE :marca';
    $params[':marca'] = '%' . $marca . '%';  // % = qualquer coisa
}

// Só adiciona filtro de tipo se selecionou
if ($tipo !== '') {
    $query .= ' AND tipo = :tipo';
    $params[':tipo'] = $tipo;
}

// Monta a query dinamicamente!
// Se marca vazia e tipo vazio:
//   SELECT * FROM relogios WHERE 1=1
// Se marca='Apple' e tipo vazio:
//   SELECT * FROM relogios WHERE 1=1 AND marca LIKE '%Apple%'
// Se marca='Apple' e tipo='smart':
//   SELECT * FROM relogios WHERE 1=1 AND marca LIKE '%Apple%' AND tipo = 'smart'

$stmt = $pdo->prepare($query);
$stmt->execute($params); // Passa os valores de forma segura
$relogios = $stmt->fetchAll();
```

**Por que `LIKE` na marca e `=` no tipo?**
- Marca: Busca parcial ("Cas" encontra "Casio")
- Tipo: Busca exata ("smart" ou nada)

**Por que `WHERE 1=1`?**
Porque assim sempre temos um WHERE válido, e podemos adicionar mais condições com AND sem se preocupar:
```
WHERE 1=1 AND marca='...' AND tipo='...'
```
Em vez de:
```
WHERE AND marca='...' AND tipo='...'  // Erro!
```

### Não confunda:
- **isset()**: Verifica se a variável existe
- **trim()**: Remove espaços em branco
- **LIKE**: Busca parcial com `%`
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
