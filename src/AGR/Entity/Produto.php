<?php

namespace AGR\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="produto")
 * @ORM\Entity(repositoryClass="AGR\Repository\ProdutoRepository")
 */
class Produto
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    private $id;
   
    /** @ORM\Column(length=100) */
    private $nome;

    /** @ORM\Column(length=100) */
    private $descricao;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    private $valor;

    public function __construct($id, $nome, $descricao, $valor) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->valor = $valor;
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function getValor(){
        return $this->valor;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function setValor($valor){
        $this->valor = $valor;
    }
}

?>