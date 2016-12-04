<?php

namespace AGR\Service;

use AGR\Entity\Produto;
use AGR\Repository\ProdutoRepository;
use AGR\Repository\CategoriaRepository;
use AGR\Repository\TagRepository;

class ProdutoService{

    private $produto;
    private $produtoRepository;
    private $categoriaRepository;
    private $tagRepository;

    public function __construct(Produto $produto, ProdutoRepository $produtoRepository, CategoriaRepository $categoriaRepository, TagRepository $tagRepository){
        $this->produto = $produto;
        $this->produtoRepository = $produtoRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->tagRepository = $tagRepository;
    }

    public function findAll(){
        return $this->produtoRepository->findAllSortedById();
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

        $this->produto->setCategoria($this->categoriaRepository->getReference('AGR\Entity\Categoria', (int)$dados['categoria']));
        
        $tags = explode(",", $dados['tags']);

        foreach($tags as $tag){
            $this->produto->addTag($this->tagRepository->getReference('AGR\Entity\Tag', $tag));
        }
                
        return $this->produtoRepository->insertUpdate($this->produto);
    }

    public function atualizarProduto(array $dados){    
        $produto = $this->produtoRepository->getReference('AGR\Entity\Produto', $dados['id']);
        $produto ->setNome($dados['nome']);
        $produto ->setDescricao($dados['descricao']);
        $produto ->setValor($dados['valor']);

        $produto->setCategoria($this->categoriaRepository->getReference('AGR\Entity\Categoria', (int)$dados['categoria']));
        
        $tags = explode(",", $dados['tags']);

        foreach($tags as $tag){
            $produto->addTag($this->tagRepository->getReference('AGR\Entity\Tag', $tag));
        }

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