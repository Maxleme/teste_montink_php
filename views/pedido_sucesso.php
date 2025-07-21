<?php
$title = 'Pedido Finalizado';
ob_start(); ?>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-5 text-center border-0" style="max-width: 420px; width: 100%;">
        <div class="mb-3">
            <span class="text-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                    <path d="M10.97 5.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L5.324 10.384a.75.75 0 1 1 1.06-1.06l1.094 1.093 3.492-4.438z"/>
                </svg>
            </span>
        </div>
        <h2 class="text-success mb-3">Pedido realizado com sucesso!</h2>
        <div class="mb-3">Status do pedido: <span class="fw-bold text-primary"><?= htmlspecialchars($pedido['status']) ?></span></div>
        <a href="/produtos" class="btn btn-success btn-lg px-5">Voltar para a loja</a>
    </div>
</div>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 