<?php

namespace AGR\Service;

use AGR\Entity\Produto;
use AGR\Mapper\ProdutoMapper;

class ProdutoService{

    private $produto;
    private $produtoMapper;

    public function __construct(Produto $produto, ProdutoMapper $produtoMapper){
        $this->produto = $produto;
        $this->produtoMapper = $produtoMapper;
    }

    public function findAll(){
        return $this->produtoMapper->findAll();
    }

    public function inserirProduto(array $dados){
        $this->produto->setNome($dados['nome']);
        $this->produto->setDescricao($dados['descricao']);
        $this->produto->setValor($dados['valor']);
        return $this->produtoMapper->insert($this->produto);
    }

    public function atualizarProduto(array $dados){    
        $this->produto = $this->produtoMapper->loadProdutoById($dados['id']);

        $this->produto->setNome($dados['nome']);
        $this->produto->setDescricao($dados['descricao']);
        $this->produto->setValor($dados['valor']);

        return $this->produtoMapper->update($this->produto);
    }

     public function excluirProduto($id){    
        $this->produto = $this->produtoMapper->loadProdutoById($id);

        return $this->produtoMapper->delete( $this->produto);
    }

    public function buscarProdutoPeloId($id){    
        return $this->produtoMapper->loadProdutoById($id);
    }

    public function fixture(array $produtos, $connection){
        
        $this->produtoMapper->clearBd($connection);
        foreach($produtos as $produto)
        {
            $this->produtoMapper->insert($produto);
        }
    }

}


?>