<?php
namespace App\Repositories;

use App\Repositories\CupomRepositoryInterface;
use App\Config\Database;
use PDO;

class CupomRepository implements CupomRepositoryInterface {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function listarTodos() {
        $stmt = $this->db->query('SELECT * FROM cupons ORDER BY validade DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function buscarPorCodigo($codigo) {
        $stmt = $this->db->prepare('SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE()');
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function buscarPorId($id) {
        $stmt = $this->db->prepare('SELECT * FROM cupons WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function criar($codigo, $desconto, $validade, $valor_minimo) {
        $stmt = $this->db->prepare('INSERT INTO cupons (codigo, desconto, validade, valor_minimo) VALUES (?, ?, ?, ?)');
        $stmt->execute([$codigo, $desconto, $validade, $valor_minimo]);
        return $this->db->lastInsertId();
    }
    public function remover($id) {
        $stmt = $this->db->prepare('DELETE FROM cupons WHERE id = ?');
        $stmt->execute([$id]);
    }
} 