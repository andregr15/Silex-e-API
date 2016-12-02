<?php

namespace AGR\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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

    /**
    * Muitos Produtos tem uma Categoria.
    * @ORM\ManyToOne(targetEntity="AGR\Entity\Categoria")
    * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
    */
    private $categoria;

    /**
    * Muitos Produtos tem muitas Tags.
    * @ORM\ManyToMany(targetEntity="AGR\Entity\Tag")
    * @ORM\JoinTable(name="produtos_tags",
    *      joinColumns={@ORM\JoinColumn(name="produto_id", referencedColumnName="id")},
    *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
    *      )
    */
    private $tags;

    public function __construct($id, $nome, $descricao, $valor) {
        $this->id = $id;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->valor = $valor;
        $this->tags =  new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getCategoria(){
        return $this->categoria;
    }

    public function getTags(){
        return $this->tags;
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

    public function setCategoria($categoria){
        $this->categoria = $categoria;
    }

    public function addTag($tag){
        $this->tags->add($tag);
    }
}

?>