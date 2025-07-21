<?php
$title = 'Detalhes do Pedido';
ob_start(); ?>
<h1 class="mb-4">Detalhes do Pedido #<?= $pedido['id'] ?></h1>
<a href="/pedido" class="btn btn-outline-primary mb-3">Voltar para pedidos</a>
<h3>Dados do Cliente</h3>
<ul class="list-group mb-4">
    <li class="list-group-item"><b>Nome:</b> <?= htmlspecialchars($pedido['cliente_nome']) ?></li>
    <li class="list-group-item"><b>E-mail:</b> <?= htmlspecialchars($pedido['cliente_email']) ?></li>
    <li class="list-group-item"><b>Endereço:</b> <?= htmlspecialchars($pedido['endereco']) ?>, CEP: <?= htmlspecialchars($pedido['cep']) ?></li>
    <li class="list-group-item"><b>Status:</b> <?= htmlspecialchars($pedido['status']) ?></li>
    <li class="list-group-item"><b>Data:</b> <?= date('d-m-Y H:i:s', strtotime($pedido['criado_em'])) ?></li>
</ul>
<h3>Itens do Pedido</h3>
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle bg-white mb-4">
    <thead class="table-light">
    <tr>
        <th>Produto</th>
        <th>Variação</th>
        <th>Quantidade</th>
        <th>Preço Unitário</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    <?php $total = 0; foreach ($itens as $item): $subtotal = $item['preco_unitario'] * $item['quantidade']; $total += $subtotal; ?>
    <tr>
        <td><?= htmlspecialchars($item['produto_nome']) ?></td>
        <td><?= htmlspecialchars($item['variacao_nome']) ?></td>
        <td><?= (int)$item['quantidade'] ?></td>
        <td>R$ <?= number_format($item['preco_unitario'],2,',','.') ?></td>
        <td>R$ <?= number_format($subtotal,2,',','.') ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<ul class="list-group">
    <li class="list-group-item"><b>Subtotal:</b> R$ <?= number_format($pedido['subtotal'],2,',','.') ?></li>
    <li class="list-group-item"><b>Frete:</b> R$ <?= number_format($pedido['frete'],2,',','.') ?></li>
    <?php if (!empty($pedido['cupom_id'])): ?>
        <li class="list-group-item"><b>Desconto:</b> R$ <?= number_format($pedido['subtotal'] + $pedido['frete'] - $pedido['total'],2,',','.') ?></li>
    <?php endif; ?>
    <li class="list-group-item"><b>Total:</b> R$ <?= number_format($pedido['total'],2,',','.') ?></li>
</ul>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 