<?php

namespace AGR\Service;

use AGR\Entity\Cliente;
use AGR\Repository\ClienteRepository;

class ClienteService{

    private $cliente;
    private $clienteRepository;

    public function __construct(Cliente $cliente, ClienteRepository $clienteRepository){
        $this->cliente = $cliente;
        $this->clienteRepository = $clienteRepository;
    }

    public function findAll(){
        return $this->clienteRepository->findAllShortedById();        
    }

     public function findByNome($nome){
        return $this->clienteRepository->findByNome($nome);
    }

    public function findPaged($pages, $numByPage){
        return $this->clienteRepository->findPaged($pages, $numByPage);
    }

    public function inserirCliente(array $dados){
        $this->cliente->setNome($dados['nome']);
        $this->cliente->setDocumento($dados['documento']);
        $this->cliente->setEmail($dados['email']);
        return $this->clienteRepository->insertUpdate($this->cliente);
    }

    public function atualizarCliente(array $dados){    
        $cliente = $this->clienteRepository->getReference('AGR\Entity\Cliente', $dados['id']);
        $cliente->setNome($dados['nome']);
        $cliente->setDocumento($dados['documento']);
        $cliente->setEmail($dados['email']);

        return $this->clienteRepository->insertUpdate($cliente);
    }

     public function excluirCliente($id){    
        $cliente = $this->clienteRepository->getReference('AGR\Entity\Cliente', $id);
        return $this->clienteRepository->delete($cliente);
    }

    public function buscarClientePeloId($id){    
        return $this->clienteRepository->loadClienteById($id);
    }

    public function fixture(array $clientes, $connection){
        
        $this->clienteRepository->clearBd($connection);
        foreach($clientes as $cliente)
        {
            $this->clienteRepository->insert($cliente);
        }
    }

}


?>