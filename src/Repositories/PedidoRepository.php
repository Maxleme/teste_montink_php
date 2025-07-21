<?php
namespace App\Repositories;

use App\Repositories\PedidoRepositoryInterface;
use App\Config\Database;
use PDO;

class PedidoRepository implements PedidoRepositoryInterface {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function listarTodos() {
        $stmt = $this->db->query('SELECT * FROM pedidos ORDER BY criado_em DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarPorId($id) {
        $stmt = $this->db->prepare('SELECT * FROM pedidos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function listarItens($pedido_id) {
        $stmt = $this->db->prepare('SELECT pi.*, p.nome as produto_nome, v.nome as variacao_nome FROM pedido_itens pi LEFT JOIN produtos p ON pi.produto_id = p.id LEFT JOIN variacoes v ON pi.variacao_id = v.id WHERE pi.pedido_id = ?');
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function criar($dados) {
        $stmt = $this->db->prepare('INSERT INTO pedidos (cliente_nome, cliente_email, endereco, cep, subtotal, frete, total, cupom_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $dados['cliente_nome'], $dados['cliente_email'], $dados['endereco'], $dados['cep'],
            $dados['subtotal'], $dados['frete'], $dados['total'], $dados['cupom_id'], $dados['status'] ?? 'PENDENTE'
        ]);
        return $this->db->lastInsertId();
    }
    public function adicionarItem($pedido_id, $item) {
        $stmt = $this->db->prepare('INSERT INTO pedido_itens (pedido_id, produto_id, variacao_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$pedido_id, $item['produto_id'], $item['variacao_id'], $item['quantidade'], $item['preco_unitario']]);
    }
    public function atualizarStatus($id, $status) {
        $stmt = $this->db->prepare('UPDATE pedidos SET status = ? WHERE id = ?');
        $stmt->execute([$status, $id]);
    }
    public function remover($id) {
        $stmt = $this->db->prepare('DELETE FROM pedidos WHERE id = ?');
        $stmt->execute([$id]);
    }
    public function existePedidoComCupom($cupom_id) {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM pedidos WHERE cupom_id = ?');
        $stmt->execute([$cupom_id]);
        return $stmt->fetchColumn() > 0;
    }
} 