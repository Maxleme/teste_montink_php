<?php
namespace App\Services;

interface CarrinhoServiceInterface {
    public function adicionarItem($item);
    public function removerItem($indice);
    public function listarItens();
    public function limpar();
    public function calcularSubtotal();
} 