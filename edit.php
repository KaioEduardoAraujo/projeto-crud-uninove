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
    $stmt = $pdo->prepare('SELECT id, marca, cor_pulseira, tipo FROM relogios WHERE id = :id LIMIT 1');
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
?>
<div class="card">
    <h2>Editar Relógio</h2>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?= esc((string)$relogio['id']) ?>">
        <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" id="marca" name="marca" maxlength="100" required value="<?= esc($relogio['marca']) ?>">
        </div>
        <div class="form-group">
            <label for="cor_pulseira">Cor da Pulseira</label>
            <input type="text" id="cor_pulseira" name="cor_pulseira" maxlength="50" required value="<?= esc($relogio['cor_pulseira']) ?>">
        </div>
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select id="tipo" name="tipo" required>
                <?php foreach ($tipos as $value => $label): ?>
                    <option value="<?= esc($value) ?>" <?= $relogio['tipo'] === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="submit" value="Atualizar" class="button">
    </form>
</div>
</main>
</body>
</html>
