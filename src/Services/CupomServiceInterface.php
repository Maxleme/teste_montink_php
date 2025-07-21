<?php
namespace App\Services;

interface CupomServiceInterface {
    public function cadastrarCupom(array $dados);
    public function removerCupom($id);
    public function validarCupomParaCarrinho($codigo, $subtotal);
} 