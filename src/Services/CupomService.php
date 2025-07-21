<?php
namespace App\Services;

use App\Repositories\CupomRepository;
use App\Repositories\PedidoRepository;
use App\Services\CupomServiceInterface;

class CupomService implements CupomServiceInterface {
    private $repo;
    public function __construct() {
        $this->repo = new CupomRepository();
    }
    public function cadastrarCupom(array $dados) {
        $codigo = trim($dados['codigo'] ?? '');
        $desconto = floatval($dados['desconto'] ?? 0);
        $validade = $dados['validade'] ?? '';
        $valor_minimo = floatval($dados['valor_minimo'] ?? 0);
        $erros = [];
        if (!$codigo || $desconto <= 0 || !$validade || $valor_minimo < 0) {
            $erros[] = 'Preencha todos os campos corretamente.';
        }
        $hoje = date('Y-m-d');
        if ($validade < $hoje) {
            $erros[] = 'A validade do cupom não pode ser anterior à data de hoje.';
        }
        if ($this->repo->buscarPorCodigo($codigo)) {
            $erros[] = 'Já existe um cupom com esse código.';
        }
        if ($erros) return ['erros' => $erros];
        $this->repo->criar($codigo, $desconto, $validade, $valor_minimo);
        return ['sucesso' => true];
    }
    public function removerCupom($id) {
        $pedidoRepo = new PedidoRepository();
        if ($pedidoRepo->existePedidoComCupom($id)) {
            return ['erros' => ['Não é possível remover: este cupom já foi utilizado em pelo menos um pedido.']];
        }
        $this->repo->remover($id);
        return ['sucesso' => true];
    }
    public function validarCupomParaCarrinho($codigo, $subtotal) {
        $cupom = $this->repo->buscarPorCodigo($codigo);
        if (!$cupom) {
            return ['erro' => 'Cupom inválido ou expirado.'];
        }
        if (($subtotal - $cupom['desconto']) < $cupom['valor_minimo']) {
            return ['erro' => 'Cupom só pode ser usado se o subtotal menos o desconto for igual ou maior que R$ ' . number_format($cupom['valor_minimo'],2,',','.')];
        }
        return ['cupom' => $cupom];
    }
} 