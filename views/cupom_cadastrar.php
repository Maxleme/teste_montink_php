<?php
$title = 'Cadastrar Cupom';
ob_start(); ?>
<h1 class="mb-4">Cadastrar Cupom</h1>
<?php if (!empty($msg)): ?>
    <p class="text-danger"> <?= htmlspecialchars($msg) ?> </p>
<?php endif; ?>
<form method="post" action="/cupom/create" class="bg-white p-4 rounded shadow-sm">
    <div class="mb-3">
        <label class="form-label">Código:</label>
        <input type="text" name="codigo" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Desconto (R$):</label>
        <input type="number" step="0.01" name="desconto" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Validade:</label>
        <input type="date" name="validade" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Valor Mínimo (R$):</label>
        <input type="number" step="0.01" name="valor_minimo" class="form-control" required>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/cupom" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 