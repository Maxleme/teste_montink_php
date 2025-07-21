<?php
namespace App\Repositories;

interface PedidoRepositoryInterface {
    public function listarTodos();
    public function buscarPorId($id);
    public function listarItens($pedido_id);
    public function criar($dados);
    public function adicionarItem($pedido_id, $item);
    public function atualizarStatus($id, $status);
    public function remover($id);
} 