<?php
$title = 'Carrinho de Compras';
ob_start(); ?>
<h1 class="mb-4">Carrinho de Compras</h1>
<?php if (empty($carrinho)): ?>
    <div class="alert alert-info">Seu carrinho está vazio.</div>
    <div class="d-flex justify-content-between align-items-center mt-3">
        <a href="/produtos" class="btn btn-outline-primary">Continuar comprando</a>
    </div>
<?php else: ?>
<div class="table-responsive mb-3">
    <table class="table table-bordered table-hover align-middle bg-white">
        <thead class="table-light">
        <tr>
            <th>Produto</th>
            <th>Variação</th>
            <th>Preço Base</th>
            <th>Acréscimo</th>
            <th>Preço Final</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php $total = 0; ?>
        <?php foreach ($carrinho as $i => $item): ?>
            <?php $preco_final = $item['preco'] + $item['acrescimo_preco']; ?>
            <?php $subtotal = $preco_final * $item['quantidade']; ?>
            <?php $total += $subtotal; ?>
            <tr>
                <td><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= htmlspecialchars($item['variacao_nome']) ?></td>
                <td>R$ <?= number_format($item['preco'],2,',','.') ?></td>
                <td>R$ <?= number_format($item['acrescimo_preco'],2,',','.') ?></td>
                <td>R$ <?= number_format($preco_final,2,',','.') ?></td>
                <td><?= (int)$item['quantidade'] ?></td>
                <td>R$ <?= number_format($subtotal,2,',','.') ?></td>
                <td><a href="/carrinho/remover?indice=<?= $i ?>" class="btn btn-sm btn-danger">Remover</a></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="6" class="text-end"><b>Subtotal:</b></td>
            <td colspan="2"><b>R$ <?= number_format($total,2,',','.') ?></b></td>
        </tr>
        <tr>
            <td colspan="6" class="text-end"><b>Frete:</b></td>
            <td colspan="2"><b>R$ <?= number_format($frete,2,',','.') ?></b></td>
        </tr>
        <?php $desconto = !empty($_SESSION['cupom']) ? $_SESSION['cupom']['desconto'] : 0; ?>
        <?php if ($desconto > 0): ?>
        <tr>
            <td colspan="6" class="text-end"><b>Desconto:</b></td>
            <td colspan="2"><b>- R$ <?= number_format($desconto,2,',','.') ?></b></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td colspan="6" class="text-end"><b>Total:</b></td>
            <td colspan="2"><b>R$ <?= number_format(max($total - $desconto + $frete,0),2,',','.') ?></b></td>
        </tr>
        </tbody>
    </table>
</div>
<?php if (!empty($_SESSION['msg_cupom'])): ?>
    <p class="<?= isset($_SESSION['cupom']) ? 'text-success' : 'text-danger' ?> fw-bold">
        <?= htmlspecialchars($_SESSION['msg_cupom']) ?>
    </p>
    <?php unset($_SESSION['msg_cupom']); ?>
<?php endif; ?>
<?php if (!empty($_SESSION['cupom'])): ?>
    <p><b>Cupom aplicado:</b> <?= htmlspecialchars($_SESSION['cupom']['codigo']) ?> (Desconto: R$ <?= number_format($_SESSION['cupom']['desconto'],2,',','.') ?>)</p>
<?php endif; ?>
<form method="post" action="/carrinho/aplicarCupom" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="cupom" class="form-control" placeholder="Cupom" required>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-success">Aplicar</button>
    </div>
</form>
<div class="d-flex justify-content-between align-items-center mt-3">
    <a href="/produtos" class="btn btn-outline-primary">Continuar comprando</a>
    <form method="get" action="/carrinho/finalizar" class="mb-0">
        <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
    </form>
</div>
<?php endif; ?>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 