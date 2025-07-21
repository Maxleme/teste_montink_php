<?php
$title = 'Finalizar Pedido';
ob_start(); ?>
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <h1 class="mb-4">Finalizar Pedido</h1>
        
        <?php if (isset($_GET['ok'])): ?>
            <div class="position-absolute top-50 start-50 translate-middle w-100" style="max-width: 100vw;">
                <div class="card shadow p-5 text-center border-0 mx-auto" style="max-width: 420px;">
                    <div class="mb-3">
                        <span class="text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                                <path d="M10.97 5.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L5.324 10.384a.75.75 0 1 1 1.06-1.06l1.094 1.093 3.492-4.438z"/>
                            </svg>
                        </span>
                    </div>
                    <a href="/produtos" class="btn btn-success btn-lg px-5">Voltar para a loja</a>
                </div>
            </div>
            <?php return; ?>
        <?php endif; ?>
        <form method="post" action="/carrinho/salvarPedido" id="form-finalizar" class="bg-white p-4 rounded shadow-sm mb-4">
            <div class="mb-3">
                <label class="form-label">Nome:</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3 row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">CEP:</label>
                    <input type="text" name="cep" id="cep" class="form-control" required pattern="\d{5}-?\d{3}" maxlength="9">
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-secondary w-100" onclick="buscarCep()">Buscar CEP</button>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Endereço:</label>
                <input type="text" name="endereco" id="endereco" class="form-control" required readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Número:</label>
                <input type="text" name="numero" id="numero" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Bairro:</label>
                <input type="text" name="bairro" id="bairro" class="form-control" required readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Cidade:</label>
                <input type="text" name="cidade" id="cidade" class="form-control" required readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">UF:</label>
                <input type="text" name="uf" id="uf" class="form-control" required maxlength="2" readonly>
            </div>
            <div class="bg-light border rounded p-3 mb-3">
                <h4 class="mb-3">Resumo do Pedido</h4>
                <ul class="list-group mb-0">
                    <li class="list-group-item">Subtotal: R$ <?= number_format($subtotal,2,',','.') ?></li>
                    <li class="list-group-item">Frete: R$ <?= number_format($frete,2,',','.') ?></li>
                    <?php if ($desconto > 0): ?>
                        <li class="list-group-item">Desconto: - R$ <?= number_format($desconto,2,',','.') ?></li>
                    <?php endif; ?>
                    <li class="list-group-item"><b>Total: R$ <?= number_format($total,2,',','.') ?></b></li>
                </ul>
            </div>
            <div class="d-flex gap-2 justify-content-end">
                <a href="/carrinho" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
            </div>
        </form>
    </div>
</div>
<script>
function buscarCep() {
    var cep = document.getElementById('cep').value.replace(/\D/g, '');
    if (cep.length !== 8) {
        alert('Digite um CEP válido com 8 dígitos.');
        return;
    }
    fetch('https://viacep.com.br/ws/' + cep + '/json/')
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                alert('CEP não encontrado.');
            } else {
                document.getElementById('endereco').value = data.logradouro || '';
                document.getElementById('bairro').value = data.bairro || '';
                document.getElementById('cidade').value = data.localidade || '';
                document.getElementById('uf').value = data.uf || '';
            }
        })
        .catch(() => alert('Erro ao consultar o CEP.'));
}
</script>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 