<?php
$title = 'Produtos';
ob_start(); ?>
<div class="d-flex justify-content-end mb-3">
    <a href="/carrinho" class="btn btn-outline-primary me-2">🛒 Ver Carrinho</a>
    <a href="/pedido" class="btn btn-outline-secondary me-2">📦 Pedidos</a>
    <a href="/cupom" class="btn btn-outline-success">🏷️ Cupons</a>
</div>
<h1 class="mb-4">Produtos Cadastrados</h1>
<a href="/produto/cadastrar" class="btn btn-primary mb-3">Cadastrar Produto</a>
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-light">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Estoque Total</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($produtos)): ?>
        <?php foreach ($produtos as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['id']) ?></td>
                <td><?= htmlspecialchars($p['nome']) ?></td>
                <td>R$ <?= number_format($p['preco'],2,',','.') ?></td>
                <td><?= isset($p['estoque_total']) ? (int)$p['estoque_total'] : 0 ?></td>
                <td>
                    <a href="/produto/editar?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-sm btn-warning">Editar</a>
                    <a href="/produto/comprar?id=<?= htmlspecialchars($p['id']) ?>" class="btn btn-sm btn-success">Comprar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5" class="text-center">Nenhum produto cadastrado.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
</div>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 