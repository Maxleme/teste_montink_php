<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Comprar Produto</title>
</head>
<body>
<?php
$title = 'Comprar Produto';
ob_start(); ?>
<h1 class="mb-4">Comprar Produto</h1>
<h2><?= htmlspecialchars($produto['nome']) ?></h2>
<p>Preço base: R$ <?= number_format($produto['preco'],2,',','.') ?></p>
<form method="post" action="/produto/addCarrinho" class="bg-white p-4 rounded shadow-sm">
    <input type="hidden" name="produto_id" value="<?= htmlspecialchars($produto['id']) ?>">
    <div class="mb-3">
        <label class="form-label">Variação:</label>
        <select name="variacao_id" class="form-select" required>
            <option value="">Selecione</option>
            <?php foreach ($variacoes as $v): ?>
                <option value="<?= htmlspecialchars($v['id']) ?>">
                    <?= htmlspecialchars($v['nome']) ?> (Acréscimo: R$ <?= number_format($v['acrescimo_preco'],2,',','.') ?> | Estoque: <?= (int)$v['estoque'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Quantidade:</label>
        <input type="number" name="quantidade" value="1" min="1" class="form-control" required>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">Adicionar ao Carrinho</button>
        <a href="/produtos" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 