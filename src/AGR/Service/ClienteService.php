<?php

namespace AGR\Service;

use AGR\Entity\Cliente;
use AGR\Mapper\ClienteMapper;

class ClienteService{

    private $cliente;
    private $clienteMapper;

    public function __construct(Cliente $cliente, ClienteMapper $clienteMapper){
        $this->cliente = $cliente;
        $this->clienteMapper = $clienteMapper;
    }

    public function findAll(){
        return $this->clienteMapper->findAll();
    }

    public function inserirCliente(array $dados){
        $this->cliente->setNome($dados['nome']);
        $this->cliente->setDocumento($dados['documento']);
        $this->cliente->setEmail($dados['email']);
        return $this->clienteMapper->insert($this->cliente);
    }

    public function atualizarCliente(array $dados){    
        $this->cliente = $this->clienteMapper->loadClienteById($dados['id']);

        $this->cliente->setNome($dados['nome']);
        $this->cliente->setDocumento($dados['documento']);
        $this->cliente->setEmail($dados['email']);

        return $this->clienteMapper->update( $this->cliente);
    }

     public function excluirCliente($id){    
        $this->cliente = $this->clienteMapper->loadClienteById($id);

        return $this->clienteMapper->delete($this->cliente);
    }

    public function buscarClientePeloId($id){    
        return $this->clienteMapper->loadClienteById($id);
    }

    public function fixture(array $clientes, $connection){
        
        $this->clienteMapper->clearBd($connection);
        foreach($clientes as $cliente)
        {
            $this->clienteMapper->insert($cliente);
        }
    }

}


?>