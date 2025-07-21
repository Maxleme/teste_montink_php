<?php
namespace App\Repositories;

interface CarrinhoRepositoryInterface {
    public function adicionarItem($item);
    public function removerItem($indice);
    public function listarItens();
    public function limpar();
    public function calcularSubtotal();
} 