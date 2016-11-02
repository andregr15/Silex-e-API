<?php

namespace AGR\Mapper;

use AGR\Entity\Cliente;
use Doctrine\ORM\EntityRepository;

class ClienteMapper extends EntityRepository
{
  public function loadClienteById($id){
    if(!$id){
      throw new InvalidArgumentException("Id inválido");
    }

    $post = $this->findOneById($id);

    return $post;
  }

  public function supportsClass($class){
    return $class ===  'AGR\Entity\Cliente';
  }

  public function insert(Cliente $cliente){
    $this->getEntityManager()->persist($cliente);
    $this->getEntityManager()->flush();
    return $cliente;
  }

  public function update(Cliente $cliente){
    $this->getEntityManager()->merge($cliente);
    $this->getEntityManager()->flush();
    return $cliente;
  }

  public function delete(Cliente $cliente){
    $this->getEntityManager()->remove($cliente);
    $this->getEntityManager()->flush();
    return $cliente;
  }

  public function clearBd($connection){
    $platform = $connection->getDatabasePlatform();
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
    $connection->executeUpdate($platform->getTruncateTableSQL("cliente","true"));
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
  }
}

?>
