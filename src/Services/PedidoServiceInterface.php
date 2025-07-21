<?php
namespace App\Services;

interface PedidoServiceInterface {
    public function criarPedidoCompleto(array $dados, array $itens);
    public function listarPedidos();
    public function buscarPedido($id);
    public function listarItens($pedido_id);
    public function atualizarStatus($id, $status);
    public function remover($id);
} 