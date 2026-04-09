<?php
require_once __DIR__ . '/header.php';
require_login();

try {
    $stmt = $pdo->query('SELECT id, marca, cor_pulseira, tipo FROM relogios ORDER BY id DESC');
    $relogios = $stmt->fetchAll();
} catch (PDOException $e) {
    set_flash('Erro ao carregar os relógios. Tente novamente mais tarde.', 'error');
    $relogios = [];
}
?>
<div class="card">
    <h2>Lista de Relógios</h2>
    <?php if (empty($relogios)): ?>
        <p>Nenhum relógio cadastrado ainda.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Cor da Pulseira</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($relogios as $relogio): ?>
                    <tr>
                        <td><?= esc((string)$relogio['id']) ?></td>
                        <td><?= esc($relogio['marca']) ?></td>
                        <td><?= esc($relogio['cor_pulseira']) ?></td>
                        <td><?= esc($relogio['tipo']) ?></td>
                        <td>
                            <a class="button" href="edit.php?id=<?= esc((string)$relogio['id']) ?>">Editar</a>
                            <?php if (is_admin()): ?>
                                <a class="button danger" href="delete.php?id=<?= esc((string)$relogio['id']) ?>" onclick="return confirm('Tem certeza que deseja excluir este relógio?');">Excluir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</main>
</body>
</html>
