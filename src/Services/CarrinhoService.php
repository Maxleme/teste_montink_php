<?php
namespace App\Services;

use App\Repositories\CarrinhoRepository;
use App\Services\CarrinhoServiceInterface;

class CarrinhoService implements CarrinhoServiceInterface {
    private $repo;
    public function __construct() {
        $this->repo = new CarrinhoRepository();
    }
    public function adicionarItem($item) {
        $this->repo->adicionarItem($item);
    }
    public function removerItem($indice) {
        $this->repo->removerItem($indice);
    }
    public function listarItens() {
        return $this->repo->listarItens();
    }
    public function limpar() {
        $this->repo->limpar();
    }
    public function calcularSubtotal() {
        return $this->repo->calcularSubtotal();
    }
} 