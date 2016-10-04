<?php

namespace AGR\Entity;

class Cliente
{
    private $id;
    private $nome;
    private $documento;
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
}

?>