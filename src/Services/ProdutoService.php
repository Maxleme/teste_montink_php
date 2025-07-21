<?php
namespace App\Services;

use App\Repositories\ProdutoRepository;
use App\Services\ProdutoServiceInterface;

class ProdutoService implements ProdutoServiceInterface {
    private $repo;
    public function __construct() {
        $this->repo = new ProdutoRepository();
    }
    private function extrairVariacoes(array $dados) {
        $variacoes = [];
        $nomes = $dados['variacoes_nome'] ?? [];
        $acrescimos = $dados['variacoes_acrescimo'] ?? [];
        $estoques = $dados['variacoes_estoque'] ?? [];
        $ids = $dados['variacoes_id'] ?? [];
        foreach ($nomes as $i => $nome) {
            $variacoes[] = [
                'id' => $ids[$i] ?? '',
                'nome' => trim($nome),
                'acrescimo' => floatval($acrescimos[$i] ?? 0),
                'estoque' => intval($estoques[$i] ?? 0)
            ];
        }
        return $variacoes;
    }
    public function cadastrarProdutoComVariacoes(array $dados) {
        $nome = $dados['nome'] ?? '';
        $preco = $dados['preco'] ?? 0;
        $variacoes = $this->extrairVariacoes($dados);
        $erros = [];
        if (!$nome) $erros[] = 'Nome obrigatório';
        if ($preco <= 0) $erros[] = 'Preço inválido';
        if (empty($variacoes) || empty($variacoes[0]['nome'])) $erros[] = 'Adicione ao menos uma variação.';
        if ($erros) return ['erros' => $erros];
        $produto_id = $this->repo->criar($nome, $preco);
        foreach ($variacoes as $v) {
            if ($v['nome'] !== '') {
                $this->repo->adicionarVariacao($produto_id, $v['nome'], $v['acrescimo'], $v['estoque']);
            }
        }
        return ['produto_id' => $produto_id];
    }
    public function atualizarProdutoComVariacoes($id, array $dados) {
        $nome = $dados['nome'] ?? '';
        $preco = $dados['preco'] ?? 0;
        // Buscar variações atuais do produto
        $variacoesAtuais = $this->repo->listarVariacoes($id);
        $idsAtuais = array_column($variacoesAtuais, 'id');
        
        $remover_variacoes = array_filter(array_unique($dados['remover_variacoes'] ?? []), function($id) {
            return !empty($id);
        });
        // Só faz a verificação se houver variações atuais
        if (count($idsAtuais) > 0) {
            $restantes = array_diff($idsAtuais, $remover_variacoes);
            if (count($restantes) < 1) {
                return ['erros' => ['O produto deve ter pelo menos uma variação.']];
            }
        }
        $variacoes = $this->extrairVariacoes($dados);
        $erros = [];
        if (!$id) $erros[] = 'ID não informado';
        if (!$nome) $erros[] = 'Nome obrigatório';
        if ($preco <= 0) $erros[] = 'Preço inválido';
        if ($erros) return ['erros' => $erros];
        $this->repo->atualizar($id, $nome, $preco);
        foreach ($remover_variacoes as $vid) {
            $this->repo->removerVariacao($vid);
        }
        foreach ($variacoes as $v) {
            if ($v['nome'] !== '') {
                if (empty($v['id'])) {
                    $this->repo->adicionarVariacao($id, $v['nome'], $v['acrescimo'], $v['estoque']);
                } elseif (!empty($v['id'])) {
                    $this->repo->atualizarVariacao($v['id'], $v['nome'], $v['acrescimo'], $v['estoque']);
                }
            }
        }
        return ['produto_id' => $id];
    }
} 