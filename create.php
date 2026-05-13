<?php
/**
 * Formulário de Criação de Relógio
 * 
 * Exibe o formulário para adicionar um novo relógio ao sistema.
 * Valida autenticação antes de permitir acesso.
 */

require_once __DIR__ . '/header.php';
require_login();

// Opções de tipos de relógios disponíveis
$tipos = ['smart' => 'Smart', 'analogico' => 'Analógico', 'digital' => 'Digital'];

// Funções que retornam as opções de marca e cor pré-definidas
$marcas = get_marcas();
$cores = get_cores();
?>
<div class="card">
    <h2>Novo Relógio</h2>
    <form action="store.php" method="post">
        <div class="form-group">
            <label for="marca">Marca</label>
            <select id="marca" name="marca" required>
                <option value="">Selecione a marca</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= esc($marca) ?>"><?= esc($marca) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="cor_pulseira">Cor da Pulseira</label>
            <select id="cor_pulseira" name="cor_pulseira" required>
                <option value="">Selecione a cor</option>
                <?php foreach ($cores as $cor): ?>
                    <option value="<?= esc($cor) ?>"><?= esc($cor) ?></option>
                <?php endforeach; ?>
            </select>
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
            <input type="text" id="preco" name="preco" placeholder="0,00" required data-mask="currency">
        </div>
        <div class="form-group">
            <label for="quantidade_estoque">Quantidade em Estoque</label>
            <input type="number" id="quantidade_estoque" name="quantidade_estoque" min="0" step="1" required>
        </div>
        <input type="submit" value="Salvar" class="button">
    </form>
</div>
</main>
</body>
</html>
