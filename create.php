<?php
require_once __DIR__ . '/header.php';
require_login();
$tipos = ['smart' => 'Smart', 'analogico' => 'Analógico', 'digital' => 'Digital'];
?>
<div class="card">
    <h2>Novo Relógio</h2>
    <form action="store.php" method="post">
        <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" id="marca" name="marca" maxlength="100" required>
        </div>
        <div class="form-group">
            <label for="cor_pulseira">Cor da Pulseira</label>
            <input type="text" id="cor_pulseira" name="cor_pulseira" maxlength="50" required>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo" required>
                <option value="">Selecione o tipo</option>
                <?php foreach ($tipos as $value => $label): ?>
                    <option value="<?= esc($value) ?>"><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="preco">Preço (R$)</label>
            <input type="number" id="preco" name="preco" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label for="quantidade_estoque">Quantidade em Estoque</label>
            <input type="number" id="quantidade_estoque" name="quantidade_estoque" min="0" required>
        </div>
        <input type="submit" value="Salvar" class="button">
    </form>
</div>
</main>
</body>
</html>
