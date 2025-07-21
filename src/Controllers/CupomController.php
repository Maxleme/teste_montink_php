<?php
namespace App\Controllers;

use App\Services\CupomService;
use App\Repositories\CupomRepository;

class CupomController {
    private $cupomService;
    public function __construct() {
        $this->cupomService = new CupomService();
    }
    public function index() {
        $cupons = (new CupomRepository())->listarTodos();
        require __DIR__ . '/../../views/cupons.php';
    }
    public function cadastrar() {
        require __DIR__ . '/../../views/cupom_cadastrar.php';
    }
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->cupomService->cadastrarCupom($_POST);
            if (!empty($result['erros'])) {
                $msg = implode('<br>', $result['erros']);
                require __DIR__ . '/../../views/cupom_cadastrar.php';
                return;
            }
        }
        header('Location: /cupom');
        exit;
    }
    public function remover() {
        session_start();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $result = $this->cupomService->removerCupom($id);
            if (!empty($result['erros'])) {
                $_SESSION['msg_erro'] = implode('<br>', $result['erros']);
            } else {
                $_SESSION['msg_sucesso'] = 'Cupom removido com sucesso!';
            }
        }
        header('Location: /cupom');
        exit;
    }
} 