<?php
$title = $modo === 'editar' ? 'Editar Produto' : 'Cadastrar Produto';
ob_start(); ?>
<h1 class="mb-4"><?= $title ?></h1>
<form method="post" action="<?= $modo === 'editar' ? '/produto/update' : '/produto/create' ?>" id="form-produto" class="bg-white p-4 rounded shadow-sm">
    <?php if ($modo === 'editar'): ?>
        <input type="hidden" name="id" value="<?= htmlspecialchars($produto['id']) ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label">Nome:</label>
        <input type="text" name="nome" value="<?= $modo === 'editar' ? htmlspecialchars($produto['nome']) : '' ?>" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Preço:</label>
        <input type="number" step="0.01" name="preco" value="<?= $modo === 'editar' ? htmlspecialchars($produto['preco']) : '' ?>" class="form-control" required>
    </div>
    <fieldset class="mb-3 border rounded p-3">
        <legend class="float-none w-auto px-2">Variações</legend>
        <div id="variacoes-lista">
            <?php if ($modo === 'editar' && !empty($variacoes)): ?>
                <?php foreach ($variacoes as $v): ?>
                    <div class="row g-2 mb-2">
                        <input type="hidden" name="variacoes_id[]" value="<?= htmlspecialchars($v['id']) ?>">
                        <div class="col-md-4">
                            <input type="text" name="variacoes_nome[]" value="<?= htmlspecialchars($v['nome']) ?>" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" name="variacoes_acrescimo[]" value="<?= htmlspecialchars($v['acrescimo_preco']) ?>" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="variacoes_estoque[]" value="<?= htmlspecialchars($v['estoque']) ?>" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger w-100" onclick="removerVarExistente(this, <?= htmlspecialchars($v['id']) ?>)">Remover</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif ($modo === 'cadastrar'): ?>
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <input type="text" name="variacoes_nome[]" class="form-control" placeholder="Nome da variação (ex: P)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" step="0.01" name="variacoes_acrescimo[]" class="form-control" placeholder="Acréscimo (ex: 2.00)" required>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="variacoes_estoque[]" class="form-control" placeholder="Estoque" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger w-100" onclick="removerVar(this)">Remover</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button type="button" class="btn btn-secondary mt-2" onclick="adicionarVar()">Adicionar Variação</button>
    </fieldset>
    <?php if ($modo === 'editar'): ?>
        <input type="hidden" name="remover_variacoes[]" id="remover-variacoes">
    <?php endif; ?>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/produtos" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>
<script>
function adicionarVar() {
    const div = document.createElement('div');
    div.className = 'row g-2 mb-2';
    div.innerHTML = `<?php if ($modo === 'editar'): ?><input type='hidden' name='variacoes_id[]' value=''><?php endif; ?>
        <div class='col-md-4'><input type='text' name='variacoes_nome[]' class='form-control' placeholder='Nome da variação (ex: P)' required></div>
        <div class='col-md-3'><input type='number' step='0.01' name='variacoes_acrescimo[]' class='form-control' placeholder='Acréscimo (ex: 2.00)' required></div>
        <div class='col-md-3'><input type='number' name='variacoes_estoque[]' class='form-control' placeholder='Estoque' required></div>
        <div class='col-md-2'><button type='button' class='btn btn-danger w-100' onclick='removerVar(this)'>Remover</button></div>`;
    document.getElementById('variacoes-lista').appendChild(div);
}
function removerVar(btn) {
    btn.parentNode.parentNode.remove();
}
function removerVarExistente(btn, id) {
    let input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'remover_variacoes[]';
    input.value = id;
    document.getElementById('form-produto').appendChild(input);
    btn.parentNode.parentNode.remove();
}
</script>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; 