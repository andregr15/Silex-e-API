<?php

namespace AGR\Service;

use AGR\Entity\Categoria;
use AGR\Repository\CategoriaRepository;

class CategoriaService{

    private $categoria;
    private $categoriaRepository;

    public function __construct(Categoria $categoria, CategoriaRepository $categoriaRepository){
        $this->categoria = $categoria;
        $this->categoriaRepository = $categoriaRepository;
    }

    public function findAll(){
        return $this->categoriaRepository->findAllSortedById();        
    }

    public function findByNome($nome){
        return $this->categoriaRepository->findByNome($nome);
    }

    public function findPaged($pages, $numByPage){
        return $this->categoriaRepository->findPaged($pages, $numByPage);
    }

    public function inserirCategoria(array $dados){
        $this->categoria->setNome($dados['nome']);
        return $this->categoriaRepository->insertUpdate($this->categoria);
    }

    public function atualizarCategoria(array $dados){    
        $categoria = $this->categoriaRepository->getReference('AGR\Entity\Categoria', $dados['id']);
        $categoria->setNome($dados['nome']);

        return $this->categoriaRepository->insertUpdate($categoria);
    }

     public function excluirCategoria($id){    
        $categoria = $this->categoriaRepository->getReference('AGR\Entity\Categoria', $id);
        return $this->categoriaRepository->delete($categoria);
    }

    public function buscarCategoriaPeloId($id){    
        return $this->categoriaRepository->loadCategoriaById($id);
    }

    public function fixture(array $categorias, $connection){
        
        $this->categoriaRepository->clearBd($connection);
        foreach($categorias as $categoria)
        {
            $this->categoriaRepository->insertUpdate($categoria);
        }
    }

}


?>