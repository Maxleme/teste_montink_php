<?php
namespace App\Controllers;

use App\Services\PedidoService;

class PedidoController {
    private $pedidoService;
    public function __construct() {
        $this->pedidoService = new PedidoService();
    }
    public function index() {
        $pedidos = $this->pedidoService->listarPedidos();
        require __DIR__ . '/../../views/pedidos.php';
    }
    public function detalhes() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo 'ID do pedido não informado.';
            return;
        }
        $pedido = $this->pedidoService->buscarPedido($id);
        if (!$pedido) {
            echo 'Pedido não encontrado.';
            return;
        }
        $itens = $this->pedidoService->listarItens($id);
        require __DIR__ . '/../../views/pedido_detalhes.php';
    }
    public function sucesso() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo 'ID do pedido não informado.';
            return;
        }
        $pedido = $this->pedidoService->buscarPedido($id);
        if (!$pedido) {
            echo 'Pedido não encontrado.';
            return;
        }
        require __DIR__ . '/../../views/pedido_sucesso.php';
    }
} 