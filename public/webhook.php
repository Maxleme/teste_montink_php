<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;
if (!$id || !$status) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID e status são obrigatórios']);
    exit;
}
require_once __DIR__ . '/../config/Database.php';
$db = Database::getInstance()->getConnection();
if (strtolower($status) === 'cancelado') {
    $stmt = $db->prepare('DELETE FROM pedidos WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['sucesso' => true, 'acao' => 'removido']);
    exit;
} else {
    $stmt = $db->prepare('UPDATE pedidos SET status = ? WHERE id = ?');
    $stmt->execute([$status, $id]);
    echo json_encode(['sucesso' => true, 'acao' => 'atualizado']);
    exit;
} 