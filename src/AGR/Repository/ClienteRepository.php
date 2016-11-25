<?php

namespace AGR\Repository;

use AGR\Entity\Cliente;
use Doctrine\ORM\EntityRepository;

class ClienteRepository extends EntityRepository
{
  public function loadClienteById($id){
    if(!$id){
      throw new InvalidArgumentException("Id inválido");
    }

    $post = $this->findOneById($id);

    return $post;
  }

  public function findAllShortedById(){
    return $this
                ->getEntityManager()
                ->createQuery('select c from AGR\Entity\Cliente c order by c.id asc')
                ->getResult();
  }

   public function findPaged($pages, $numByPage){
      $query = $this
                    ->getEntityManager()
                    ->createQuery('select c from AGR\Entity\Cliente c ')
                    ->setFirstResult( ( $numByPage * ($pages-1) ) )
                    ->setMaxResults( $numByPage );
      return new \Doctrine\ORM\Tools\Pagination\Paginator($query);                
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
    $this->getEntityManager()->persist($cliente);
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

  public function getReference($path, $id){
    return $this->getEntityManager()->getReference($path, $id);
  }
}

?>
