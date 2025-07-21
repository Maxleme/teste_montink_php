<?php
namespace App\Repositories;

interface CupomRepositoryInterface {
    public function listarTodos();
    public function buscarPorCodigo($codigo);
    public function buscarPorId($id);
    public function criar($codigo, $desconto, $validade, $valor_minimo);
    public function remover($id);
} 