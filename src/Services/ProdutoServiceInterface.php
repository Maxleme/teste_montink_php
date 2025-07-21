<?php
namespace App\Services;

interface ProdutoServiceInterface {
    public function cadastrarProdutoComVariacoes(array $dados);
    public function atualizarProdutoComVariacoes($id, array $dados);
} 