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
    private $errors;

    public function __construct(Produto $produto, ProdutoRepository $produtoRepository, CategoriaRepository $categoriaRepository, TagRepository $tagRepository){
        $this->produto = $produto;
        $this->produtoRepository = $produtoRepository;
        $this->categoriaRepository = $categoriaRepository;
        $this->tagRepository = $tagRepository;
        $this->errrors = array();
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
        $this->errors = array();
        $this->produto->setNome($dados['nome']);
        $this->produto->setDescricao($dados['descricao']);
        $this->produto->setValor($dados['valor']);

        $this->setCategoria($dados['categoria']);        
        $this->setTags($dados['tags']);
        
        if($this->errors){
            return $this->errors;
        }

        return $this->produtoRepository->insertUpdate($this->produto);
    }

    public function atualizarProduto(array $dados){    
        $produto = $this->produtoRepository->getReference('AGR\Entity\Produto', $dados['id']);
        $produto ->setNome($dados['nome']);
        $produto ->setDescricao($dados['descricao']);
        $produto ->setValor($dados['valor']);

        $this->setCategoria($dados['categoria']);        
        $this->setTags($dados['tags']);
        
        if($this->errors){
            return $this->errors;
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

    private function setCategoria($dado){
        $categoria = $this->categoriaRepository->loadCategoriaById($dado);

        if(!$categoria){
            $this->errors[] = array("produtos api" =>"nao existe categoria cadastrada com o id {$dado}!");
            return;
        }

        $this->produto->setCategoria($this->categoriaRepository->getReference('AGR\Entity\Categoria', (int)$dado));
    }

    private function setTags($dados){
        $tags = explode(",", $dados);

        foreach($tags as $t){
            $tag = $this->tagRepository->loadTagById($t);

            if(!$tag){
                $this->errors[] = array("produtos api" =>"nao existe tag cadastrada com o id {$t}!");
                continue;
            }

            $this->produto->addTag($this->tagRepository->getReference('AGR\Entity\Tag', $t));
        }
    }

}


?>