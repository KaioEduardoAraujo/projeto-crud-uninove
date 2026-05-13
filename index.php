<?php
/**
 * Página Principal - Listagem de Relógios
 * 
 * Exibe a tabela de todos os relógios com suporte a:
 * - Busca por marca (filtro textual)
 * - Filtro por tipo (select)
 * - Paginação (ordenado por ID DESC)
 * - Ações de edição e exclusão
 */

require_once __DIR__ . '/header.php';
require_login();

// Recupera parâmetros de filtro da URL
$marca = isset($_GET['marca']) ? trim($_GET['marca']) : '';
$tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

try {
    // Constrói query dinamicamente baseado nos filtros
    $query = 'SELECT id, marca, cor_pulseira, tipo, preco, quantidade_estoque FROM relogios WHERE 1=1';
    $params = [];
    
    // Adiciona filtro de marca (busca parcial com LIKE)
    if ($marca !== '') {
        $query .= ' AND marca LIKE :marca';
        $params[':marca'] = '%' . $marca . '%';
    }
    
    // Adiciona filtro de tipo (busca exata)
    if ($tipo !== '') {
        $query .= ' AND tipo = :tipo';
        $params[':tipo'] = $tipo;
    }
    
    // Ordena por ID decrescente (mais recentes primeiro)
    $query .= ' ORDER BY id DESC';
    
    // Executa a query com prepared statement (segurança contra SQL Injection)
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $relogios = $stmt->fetchAll();
} catch (PDOException $e) {
    set_flash('Erro ao carregar os relógios. Tente novamente mais tarde.', 'error');
    $relogios = [];
}

$tipos = ['smart' => 'Smart', 'analogico' => 'Analógico', 'digital' => 'Digital'];
?>
<div class="card">
    <h2>Lista de Relógios</h2>
    
    <div class="filter-section">
        <form method="get" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="marca">Filtrar por Marca:</label>
                    <input type="text" id="marca" name="marca" placeholder="Digite a marca..." value="<?= esc($marca) ?>">
                </div>
                <div class="filter-group">
                    <label for="tipo">Filtrar por Tipo:</label>
                    <select id="tipo" name="tipo">
                        <option value="">Todos os tipos</option>
                        <?php foreach ($tipos as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= $tipo === $value ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="button">🔍 Buscar</button>
                <a href="index.php" class="button secondary">Limpar Filtros</a>
            </div>
        </form>
    </div>
    
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
                    <th>Preço</th>
                    <th>Estoque</th>
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
                        <td>R$ <?= number_format($relogio['preco'], 2, ',', '.') ?></td>
                        <td><?= esc((string)$relogio['quantidade_estoque']) ?></td>
                        <td class="actions-cell">
                            <a class="button icon-btn" href="edit.php?id=<?= esc((string)$relogio['id']) ?>" title="Editar">✏️ Editar</a>
                            <?php if (is_admin()): ?>
                                <a class="button danger icon-btn" href="#" onclick="openDeleteModal(<?= esc((string)$relogio['id']) ?>); return false;" title="Excluir">🗑️ Excluir</a>
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
