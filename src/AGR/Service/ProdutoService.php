<?php

namespace AGR\Service;

use AGR\Entity\Produto;
use AGR\Repository\ProdutoRepository;

class ProdutoService{

    private $produto;
    private $produtoRepository;

    public function __construct(Produto $produto, ProdutoRepository $produtoRepository){
        $this->produto = $produto;
        $this->produtoRepository = $produtoRepository;
    }

    public function findAll(){
        return $this->produtoRepository->findAllShortedById();
    }

    public function findByNome($nome){
        return $this->produtoRepository->findByNome($nome);
    }

    public function findPaged($pages, $numByPage){
        return $this->produtoRepository->findPaged($pages, $numByPage);
    }

    public function inserirProduto(array $dados){
        $this->produto->setNome($dados['nome']);
        $this->produto->setDescricao($dados['descricao']);
        $this->produto->setValor($dados['valor']);
        return $this->produtoRepository->insertUpdate($this->produto);
    }

    public function atualizarProduto(array $dados){    
        $produto = $this->produtoRepository->getReference('AGR\Entity\Produto', $dados['id']);
        $produto ->setNome($dados['nome']);
        $produto ->setDescricao($dados['descricao']);
        $produto ->setValor($dados['valor']);

        return $this->produtoRepository->insertUpdate($produto);
    }

     public function excluirProduto($id){    
        $produto = $this->produtoRepository->getReference('AGR\Entity\Produto', $id);
        return $this->produtoRepository->delete($produto);
    }

    public function buscarProdutoPeloId($id){    
        return $this->produtoRepository->loadProdutoById($id);
    }

    public function fixture(array $produtos, $connection){
        
        $this->produtoRepository->clearBd($connection);
        foreach($produtos as $produto)
        {
            $this->produtoRepository->insert($produto);
        }
    }

}


?>