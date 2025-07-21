<?php
namespace App\Controllers;

use App\Services\ProdutoService;
use App\Repositories\ProdutoRepository;

class ProdutoController {
    private $produtoService;
    private $produtoRepository;
    public function __construct() {
        $this->produtoService = new ProdutoService();
        $this->produtoRepository = new ProdutoRepository();
    }
    public function index() {
        $produtos = (new ProdutoRepository())->listarTodos();
        require __DIR__ . '/../../views/produtos.php';
    }
    public function cadastrar() {
        $modo = 'cadastrar';
        include __DIR__ . '/../../views/produto_form.php';
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->produtoService->cadastrarProdutoComVariacoes($_POST);
            if (!empty($result['erros'])) {
                $msg = implode('<br>', $result['erros']);
                require __DIR__ . '/../../views/produto_form.php';
                return;
            }
        }
        $this->index();
    }
    public function editar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /produtos');
            exit;
        }
        $produto = $this->produtoRepository->buscarPorId($id);
        $variacoes = $this->produtoRepository->listarVariacoes($id);
        $modo = 'editar';
        include __DIR__ . '/../../views/produto_form.php';
    }
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $result = $this->produtoService->atualizarProdutoComVariacoes($id, $_POST);
            if (!empty($result['erros'])) {
                $msg = implode('<br>', $result['erros']);
                $produto = (new ProdutoRepository())->buscarPorId($id);
                $variacoes = (new ProdutoRepository())->listarVariacoes($id);
                $modo = 'editar';
                include __DIR__ . '/../../views/produto_form.php';
                return;
            }
            header('Location: /produtos');
            exit;
        }
        $this->index();
    }
    public function comprar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo 'ID do produto não informado.';
            return;
        }
        $produto = (new ProdutoRepository())->buscarPorId($id);
        if (!$produto) {
            echo 'Produto não encontrado.';
            return;
        }
        $variacoes = (new ProdutoRepository())->listarVariacoes($id);
        require __DIR__ . '/../../views/produto_comprar.php';
    }
    public function addCarrinho() {
        session_start();
        $produto_id = $_POST['produto_id'] ?? null;
        $variacao_id = $_POST['variacao_id'] ?? null;
        $quantidade = intval($_POST['quantidade'] ?? 1);
        if (!$produto_id || !$variacao_id || $quantidade < 1) {
            header('Location: /produtos');
            exit;
        }
        $produto = (new ProdutoRepository())->buscarPorId($produto_id);
        $variacoes = (new ProdutoRepository())->listarVariacoes($produto_id);
        $variacao = null;
        foreach ($variacoes as $v) {
            if ($v['id'] == $variacao_id) {
                $variacao = $v;
                break;
            }
        }
        if (!$produto || !$variacao) {
            header('Location: /produtos');
            exit;
        }
        if ($variacao['estoque'] < 1) {
            $_SESSION['msg_erro'] = 'Esta variação do produto está sem estoque.';
            header('Location: /produto/comprar?id=' . $produto_id);
            exit;
        }
        // Monta item do carrinho
        $item = [
            'produto_id' => $produto_id,
            'nome' => $produto['nome'],
            'preco' => $produto['preco'],
            'variacao_id' => $variacao_id,
            'variacao_nome' => $variacao['nome'],
            'acrescimo_preco' => $variacao['acrescimo_preco'],
            'quantidade' => $quantidade
        ];
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        $_SESSION['carrinho'][] = $item;
        header('Location: /carrinho');
        exit;
    }
} 