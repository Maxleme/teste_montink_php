<?php
namespace App\Models;

class Produto {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function criar($nome, $preco) {
        $stmt = $this->db->prepare('INSERT INTO produtos (nome, preco) VALUES (?, ?)');
        $stmt->execute([$nome, $preco]);
        return $this->db->lastInsertId();
    }

    public function listarTodos() {
        $sql = 'SELECT p.* FROM produtos p ORDER BY p.id DESC';
        $stmt = $this->db->query($sql);
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Para cada produto, buscar variações e estoque total
        foreach ($produtos as &$produto) {
            $produto['variacoes'] = $this->listarVariacoes($produto['id']);
            $produto['estoque_total'] = $this->consultarEstoqueTotal($produto['id']);
        }
        return $produtos;
    }

    public function buscarPorId($id) {
        $stmt = $this->db->prepare('SELECT * FROM produtos WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($produto) {
            $produto['variacoes'] = $this->listarVariacoes($id);
            $produto['estoque_total'] = $this->consultarEstoqueTotal($id);
        }
        return $produto;
    }

    public function atualizar($id, $nome, $preco) {
        $stmt = $this->db->prepare('UPDATE produtos SET nome = ?, preco = ? WHERE id = ?');
        $stmt->execute([$nome, $preco, $id]);
    }

    public function listarVariacoes($produto_id) {
        $stmt = $this->db->prepare('SELECT * FROM variacoes WHERE produto_id = ?');
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarEstoqueTotal($produto_id) {
        $stmt = $this->db->prepare('SELECT SUM(estoque) as total FROM variacoes WHERE produto_id = ?');
        $stmt->execute([$produto_id]);
        $total = $stmt->fetchColumn();
        $stmt2 = $this->db->prepare('SELECT id FROM estoque WHERE produto_id = ?');
        $stmt2->execute([$produto_id]);
        if ($stmt2->fetchColumn()) {
            $stmt3 = $this->db->prepare('UPDATE estoque SET quantidade_total = ? WHERE produto_id = ?');
            $stmt3->execute([$total, $produto_id]);
        } else {
            $stmt3 = $this->db->prepare('INSERT INTO estoque (produto_id, quantidade_total) VALUES (?, ?)');
            $stmt3->execute([$produto_id, $total]);
        }
    }

    public function consultarEstoqueTotal($produto_id) {
        $stmt = $this->db->prepare('SELECT quantidade_total FROM estoque WHERE produto_id = ?');
        $stmt->execute([$produto_id]);
        return $stmt->fetchColumn();
    }

    public function adicionarVariacao($produto_id, $nome, $acrescimo_preco, $estoque) {
        $stmt = $this->db->prepare('INSERT INTO variacoes (produto_id, nome, acrescimo_preco, estoque) VALUES (?, ?, ?, ?)');
        $stmt->execute([$produto_id, $nome, $acrescimo_preco, $estoque]);
        $this->atualizarEstoqueTotal($produto_id);
    }
    public function atualizarVariacao($id, $nome, $acrescimo_preco, $estoque) {
        $stmt = $this->db->prepare('UPDATE variacoes SET nome = ?, acrescimo_preco = ?, estoque = ? WHERE id = ?');
        $stmt->execute([$nome, $acrescimo_preco, $estoque, $id]);
        $stmt2 = $this->db->prepare('SELECT produto_id FROM variacoes WHERE id = ?');
        $stmt2->execute([$id]);
        $produto_id = $stmt2->fetchColumn();
        $this->atualizarEstoqueTotal($produto_id);
    }
    public function removerVariacao($id) {
        $stmt2 = $this->db->prepare('SELECT produto_id FROM variacoes WHERE id = ?');
        $stmt2->execute([$id]);
        $produto_id = $stmt2->fetchColumn();
        $stmt = $this->db->prepare('DELETE FROM variacoes WHERE id = ?');
        $stmt->execute([$id]);
        if ($produto_id) {
            $this->atualizarEstoqueTotal($produto_id);
        }
    }
} 