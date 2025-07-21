<?php
namespace App\Services;

use App\Repositories\PedidoRepository;
use App\Services\PedidoServiceInterface;
use App\Config\Database;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Config\Config;

class PedidoService implements PedidoServiceInterface {
    private $repo;
    public function __construct() {
        $this->repo = new PedidoRepository();
    }
    public function criarPedidoCompleto(array $itens, array $dadosCliente, $cupom = null) {
        $erros = [];
        $nome = trim($dadosCliente['nome'] ?? '');
        $email = trim($dadosCliente['email'] ?? '');
        $endereco = trim($dadosCliente['endereco'] ?? '');
        $numero = trim($dadosCliente['numero'] ?? '');
        $bairro = trim($dadosCliente['bairro'] ?? '');
        $cidade = trim($dadosCliente['cidade'] ?? '');
        $uf = trim($dadosCliente['uf'] ?? '');
        $cep = trim($dadosCliente['cep'] ?? '');
        if (!$nome || !$email || !$endereco || !$numero || !$bairro || !$cidade || !$uf || !$cep) {
            return ['ok' => false, 'msg' => 'Preencha todos os campos.'];
        }
        if (empty($itens)) {
            return ['ok' => false, 'msg' => 'Carrinho vazio.'];
        }
        $endereco_completo = $endereco . ', ' . $numero . ' - ' . $bairro . ', ' . $cidade . ' - ' . $uf;
        // Validação de CEP via ViaCEP
        $cep_limpo = preg_replace('/[^0-9]/', '', $cep);
        $viacep = json_decode(@file_get_contents('https://viacep.com.br/ws/' . $cep_limpo . '/json/'), true);
        if (!$viacep || isset($viacep['erro'])) {
            return ['ok' => false, 'msg' => 'CEP inválido.'];
        }
        // Cálculo de valores
        $subtotal = 0;
        foreach ($itens as $item) {
            $preco_final = $item['preco'] + $item['acrescimo_preco'];
            $subtotal += $preco_final * $item['quantidade'];
        }
        $frete = $this->calcularFrete($subtotal);
        $cupom_id = $cupom['id'] ?? null;
        $desconto = $cupom['desconto'] ?? 0;
        $total = max($subtotal + $frete - $desconto, 0);
        // Montar dados do pedido
        $dados = [
            'cliente_nome' => $nome,
            'cliente_email' => $email,
            'endereco' => $endereco_completo,
            'cep' => $cep,
            'subtotal' => $subtotal,
            'frete' => $frete,
            'total' => $total,
            'cupom_id' => $cupom_id,
            'status' => 'PENDENTE'
        ];
        $pedido_id = $this->repo->criar($dados);
        foreach ($itens as $item) {
            $preco_final = $item['preco'] + $item['acrescimo_preco'];
            $item['preco_unitario'] = $preco_final;
            $this->repo->adicionarItem($pedido_id, $item);
            // Dar baixa no estoque da variação
            $db = Database::getInstance()->getConnection();
            $stmtEstoque = $db->prepare('UPDATE variacoes SET estoque = estoque - ? WHERE id = ? AND estoque >= ?');
            $stmtEstoque->execute([$item['quantidade'], $item['variacao_id'], $item['quantidade']]);
            // Atualizar estoque total do produto
            $stmtSoma = $db->prepare('SELECT SUM(estoque) as total FROM variacoes WHERE produto_id = ?');
            $stmtSoma->execute([$item['produto_id']]);
            $totalEstoque = $stmtSoma->fetchColumn();
            $stmtUpdateEstoque = $db->prepare('UPDATE estoque SET quantidade_total = ? WHERE produto_id = ?');
            $stmtUpdateEstoque->execute([$totalEstoque, $item['produto_id']]);
        }
        // Enviar e-mail de confirmação com PHPMailer
        $mailCfg = Config::mail();
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $mailCfg['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mailCfg['username'];
            $mail->Password = $mailCfg['password'];
            $mail->SMTPSecure = $mailCfg['secure'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $mailCfg['port'];
            $mail->setFrom($mailCfg['from'], $mailCfg['from_name']);
            $mail->addAddress($email, $nome);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmação do Pedido - Loja';
            $mensagemHtml = "<h2>Olá, $nome!</h2>"
                . "<p>Seu pedido foi realizado com sucesso.</p>"
                . "<p><b>Endereço de entrega:</b> $endereco_completo, CEP: $cep</p>"
                . "<h3>Itens do pedido:</h3><ul>";
            foreach ($itens as $item) {
                $preco_final = $item['preco'] + $item['acrescimo_preco'];
                $mensagemHtml .= "<li>{$item['nome']} ({$item['variacao_nome']}) x {$item['quantidade']} - R$ " . number_format($preco_final,2,',','.') . " cada</li>";
            }
            $mensagemHtml .= "</ul>"
                . "<p>Subtotal: <b>R$ " . number_format($subtotal,2,',','.') . "</b><br>"
                . "Frete: <b>R$ " . number_format($frete,2,',','.') . "</b><br>";
            if ($desconto > 0) {
                $mensagemHtml .= "Desconto: <b>-R$ " . number_format($desconto,2,',','.') . "</b><br>";
            }
            $mensagemHtml .= "Total: <b>R$ " . number_format($total,2,',','.') . "</b></p>"
                . "<p>Obrigado por comprar conosco!</p>";
            $mail->Body = $mensagemHtml;
            $mail->AltBody = strip_tags(str_replace(['<br>','<li>','</li>','<ul>','</ul>'], ["\n","- ","\n","",""], $mensagemHtml));
            $mail->send();
        } catch (Exception $e) {
            // Logar erro se necessário
        }
        return ['ok' => true, 'pedido_id' => $pedido_id];
    }
    public function calcularFrete($subtotal) {
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15.00;
        } elseif ($subtotal > 200) {
            return 0.00;
        } else {
            return 20.00;
        }
    }
    public function listarPedidos() {
        return $this->repo->listarTodos();
    }
    public function buscarPedido($id) {
        return $this->repo->buscarPorId($id);
    }
    public function listarItens($pedido_id) {
        return $this->repo->listarItens($pedido_id);
    }
    public function atualizarStatus($id, $status) {
        $this->repo->atualizarStatus($id, $status);
    }
    public function remover($id) {
        $this->repo->remover($id);
    }
} 