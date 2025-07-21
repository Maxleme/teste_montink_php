<?php
$title = 'Cupons';
ob_start(); ?>
<h1 class="mb-4">Cupons</h1>
<a href="/produtos" class="btn btn-outline-primary mb-3">Voltar para produtos</a>
<a href="/cupom/cadastrar" class="btn btn-success mb-3">Cadastrar Cupom</a>
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle bg-white">
    <thead class="table-light">
    <tr>
        <th>ID</th>
        <th>Código</th>
        <th>Desconto</th>
        <th>Validade</th>
        <th>Valor Mínimo</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($cupons as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= htmlspecialchars($c['codigo']) ?></td>
            <td>R$ <?= number_format($c['desconto'],2,',','.') ?></td>
            <td><?= date('d-m-Y', strtotime($c['validade'])) ?></td>
            <td>R$ <?= number_format($c['valor_minimo'],2,',','.') ?></td>
            <td><a href="/cupom/remover?id=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Remover este cupom?')">Remover</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 