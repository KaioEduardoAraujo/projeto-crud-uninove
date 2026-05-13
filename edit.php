<?php
require_once __DIR__ . '/header.php';
require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    set_flash('ID de relógio inválido.', 'error');
    header('Location: index.php');
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id, marca, cor_pulseira, tipo, preco, quantidade_estoque FROM relogios WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $relogio = $stmt->fetch();

    if (!$relogio) {
        set_flash('Relógio não encontrado.', 'error');
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    set_flash('Erro ao buscar o relógio. Tente novamente.', 'error');
    header('Location: index.php');
    exit;
}

$tipos = ['smart' => 'Smart', 'analogico' => 'Analógico', 'digital' => 'Digital'];
$marcas = get_marcas();
$cores = get_cores();
?>
<div class="card">
    <h2>Editar Relógio</h2>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?= esc((string)$relogio['id']) ?>">
        <div class="form-group">
            <label for="marca">Marca</label>
            <select id="marca" name="marca" required>
                <option value="">Selecione a marca</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= esc($marca) ?>" <?= $relogio['marca'] === $marca ? 'selected' : '' ?>><?= esc($marca) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="cor_pulseira">Cor da Pulseira</label>
            <select id="cor_pulseira" name="cor_pulseira" required>
                <option value="">Selecione a cor</option>
                <?php foreach ($cores as $cor): ?>
                    <option value="<?= esc($cor) ?>" <?= $relogio['cor_pulseira'] === $cor ? 'selected' : '' ?>><?= esc($cor) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo" required>
                <?php foreach ($tipos as $value => $label): ?>
                    <option value="<?= esc($value) ?>" <?= $relogio['tipo'] === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="preco">Preço (R$)</label>
            <input type="text" id="preco" name="preco" placeholder="0,00" required data-mask="currency" value="<?= esc(str_replace('.', ',', (string)$relogio['preco'])) ?>">
        </div>
        <div class="form-group">
            <label for="quantidade_estoque">Quantidade em Estoque</label>
            <input type="number" id="quantidade_estoque" name="quantidade_estoque" min="0" step="1" required value="<?= esc((string)$relogio['quantidade_estoque']) ?>">
        </div>
        <input type="submit" value="Atualizar" class="button">
    </form>
</div>
</main>
</body>
</html>
