<?php
namespace App\Repositories;

interface ProdutoRepositoryInterface {
    public function criar($nome, $preco);
    public function atualizar($id, $nome, $preco);
    public function buscarPorId($id);
    public function listarTodos();
    public function listarVariacoes($produto_id);
    public function adicionarVariacao($produto_id, $nome, $acrescimo_preco, $estoque);
    public function atualizarVariacao($id, $nome, $acrescimo_preco, $estoque);
    public function removerVariacao($id);
    public function atualizarEstoqueTotal($produto_id);
    public function consultarEstoqueTotal($produto_id);
} 