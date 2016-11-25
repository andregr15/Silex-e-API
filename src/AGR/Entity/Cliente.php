<?php

namespace AGR\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cliente")
 * @ORM\Entity(repositoryClass="AGR\Repository\ClienteRepository")
 */
class Cliente
{
    /**
    * @ORM\Id
    * @ORM\Column(type="integer")
    * @ORM\GeneratedValue
    */
    private $id;

    /** @ORM\Column(length=100) */
    private $nome;

    /** @ORM\Column(length=20) */
    private $documento;

    /** @ORM\Column(length=100) */
    private $email;

    public function __construct($id, $nome, $documento, $email) {
        $this->id = $id;
        $this->nome = $nome;
        $this->documento = $documento;
        $this->email = $email;
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function getDocumento(){
        return $this->documento;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function setDocumento($documento){
        $this->documento = $documento;
    }

    public function setEmail($email){
        $this->email = $email;
    }
}

?>