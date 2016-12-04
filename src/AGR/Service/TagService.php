<?php

namespace AGR\Service;

use AGR\Entity\Tag;
use AGR\Repository\TagRepository;

class TagService{

    private $tag;
    private $tagRepository;

    public function __construct(Tag $tag, TagRepository $tagRepository){
        $this->tag = $tag;
        $this->tagRepository = $tagRepository;
    }

    public function findAll(){
        return $this->tagRepository->findAllSortedById();        
    }

    public function findByNome($nome){
        return $this->tagRepository->findByNome($nome);
    }

    public function findPaged($pages, $numByPage){
        return $this->tagRepository->findPaged($pages, $numByPage);
    }

    public function inserirTag(array $dados){
        $this->tag->setNome($dados['nome']);
        return $this->tagRepository->insertUpdate($this->tag);
    }

    public function atualizarTag(array $dados){    
        $tag = $this->tagRepository->getReference('AGR\Entity\Tag', $dados['id']);
        $tag->setNome($dados['nome']);

        return $this->tagRepository->insertUpdate($tag);
    }

     public function excluirTag($id){    
        $tag = $this->tagRepository->getReference('AGR\Entity\Tag', $id);
        return $this->tagRepository->delete($tag);
    }

    public function buscarTagPeloId($id){    
        return $this->tagRepository->loadTagById($id);
    }

    public function fixture(array $tags, $connection){
        
        $this->tagRepository->clearBd($connection);
        foreach($tags as $tag)
        {
            $this->tagRepository->insertUpdate($tag);
        }
    }

}


?>