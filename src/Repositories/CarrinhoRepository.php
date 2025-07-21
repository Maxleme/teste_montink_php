<?php
namespace App\Repositories;

use App\Repositories\CarrinhoRepositoryInterface;
use App\Config\Database;

class CarrinhoRepository implements CarrinhoRepositoryInterface {
    public function adicionarItem($item) {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        $_SESSION['carrinho'][] = $item;
    }
    public function removerItem($indice) {
        if (isset($_SESSION['carrinho'][$indice])) {
            unset($_SESSION['carrinho'][$indice]);
            $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
        }
    }
    public function listarItens() {
        return $_SESSION['carrinho'] ?? [];
    }
    public function limpar() {
        unset($_SESSION['carrinho']);
    }

    public function calcularSubtotal() {
        $carrinho = $this->listarItens();
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $preco_final = $item['preco'] + $item['acrescimo_preco'];
            $subtotal += $preco_final * $item['quantidade'];
        }
        return $subtotal;
    }
} 