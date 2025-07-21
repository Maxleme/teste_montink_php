<?php
namespace App\Controllers;

use App\Services\CarrinhoService;
use App\Services\CupomService;
use App\Services\PedidoService;

class CarrinhoController {
    private $carrinhoService;
    private $cupomService;
    private $pedidoService;
    public function __construct() {
        $this->carrinhoService = new CarrinhoService();
        $this->cupomService = new CupomService();
        $this->pedidoService = new PedidoService();
    }
    public function index() {
        $carrinho = $this->carrinhoService->listarItens();
        $subtotal = $this->carrinhoService->calcularSubtotal();
        $frete = $this->pedidoService->calcularFrete($subtotal);
        $total = $subtotal + $frete;
        require __DIR__ . '/../../views/carrinho.php';
    }
    public function remover() {
        $indice = $_GET['indice'] ?? null;
        if ($indice !== null) {
            $this->carrinhoService->removerItem($indice);
            if (empty($this->carrinhoService->listarItens())) {
                unset($_SESSION['cupom']);
            }
        }
        header('Location: /carrinho');
        exit;
    }
    public function aplicarCupom() {
        $codigo = trim($_POST['cupom'] ?? '');
        if (!$codigo) {
            $_SESSION['msg_cupom'] = 'Informe o código do cupom.';
            header('Location: /carrinho');
            exit;
        }
        $subtotal = $this->carrinhoService->calcularSubtotal();
        $result = $this->cupomService->validarCupomParaCarrinho($codigo, $subtotal);
        if (isset($result['cupom'])) {
            $_SESSION['cupom'] = $result['cupom'];
            $_SESSION['msg_cupom'] = 'Cupom aplicado com sucesso!';
        } else {
            $_SESSION['msg_cupom'] = $result['erro'] ?? 'Erro ao aplicar cupom.';
        }
        header('Location: /carrinho');
        exit;
    }
    public function finalizar() {
        $carrinho = $this->carrinhoService->listarItens();
        $subtotal = $this->carrinhoService->calcularSubtotal();
        $frete = $this->pedidoService->calcularFrete($subtotal);
        $cupom = $_SESSION['cupom'] ?? null;
        $desconto = $cupom['desconto'] ?? 0;
        $total = max($subtotal + $frete - $desconto, 0);
        require __DIR__ . '/../../views/finalizar_pedido.php';
    }
    public function salvarPedido() {
        $carrinho = $this->carrinhoService->listarItens();
        if (empty($carrinho)) {
            $_SESSION['msg_finalizar'] = 'Carrinho vazio.';
            header('Location: /carrinho');
            exit;
        }
        $dadosCliente = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'endereco' => trim($_POST['endereco'] ?? ''),
            'numero' => trim($_POST['numero'] ?? ''),
            'bairro' => trim($_POST['bairro'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'uf' => trim($_POST['uf'] ?? ''),
            'cep' => trim($_POST['cep'] ?? ''),
        ];
        $cupom = $_SESSION['cupom'] ?? null;
        $result = $this->pedidoService->criarPedidoCompleto($carrinho, $dadosCliente, $cupom);
        if ($result['ok']) {
            $this->carrinhoService->limpar();
            unset($_SESSION['cupom']);
            $_SESSION['msg_finalizar'] = 'Pedido realizado com sucesso!';
            header('Location: /pedido/sucesso?id=' . $result['pedido_id']);
            exit;
        } else {
            $_SESSION['msg_finalizar'] = $result['msg'] ?? 'Erro ao finalizar pedido.';
            header('Location: /carrinho/finalizar');
            exit;
        }
    }
} 