<?php
$title = 'Pedidos';
ob_start(); ?>
<h1 class="mb-4">Pedidos Realizados</h1>
<a href="/produtos" class="btn btn-outline-primary mb-3">Voltar para a loja</a>
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-light">
    <tr>
        <th>ID</th>
        <th>Cliente</th>
        <th>E-mail</th>
        <th>Endereço</th>
        <th>Status</th>
        <th>Data</th>
        <th>Total</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($pedidos as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['cliente_nome']) ?></td>
            <td><?= htmlspecialchars($p['cliente_email']) ?></td>
            <td><?= htmlspecialchars($p['endereco']) ?>, CEP: <?= htmlspecialchars($p['cep']) ?></td>
            <td><?= htmlspecialchars($p['status']) ?></td>
            <td><?= htmlspecialchars($p['criado_em']) ?></td>
            <td>R$ <?= number_format($p['total'],2,',','.') ?></td>
            <td><a href="/pedido/detalhes?id=<?= $p['id'] ?>" class="btn btn-sm btn-info">Ver Detalhes</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 