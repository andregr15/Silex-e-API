<?php

namespace AGR\Mapper;

use AGR\Entity\Produto;
use Doctrine\ORM\EntityRepository;

class ProdutoMapper extends EntityRepository
{
  public function loadProdutoById($id){
    if(!$id){
      throw new InvalidArgumentException("Id inválido");
    }

    $produto = $this->findOneById($id);

    return $produto;
  }

  public function supportsClass($class){
    return $class === 'AGR\Entity\Produto';
  }

  public function insert(Produto $produto){
    $this->getEntityManager()->persist($produto);
    $this->getEntityManager()->flush();
    return $produto;
  }

  public function update(Produto $produto){
    if($this->getEntityManager()->getUnitOfWork()->getEntityState($produto) != UnitOfWork::STATE_MANAGED){
      $this->getEntityManager()->merge($produto);
    }
    $this->getEntityManager()->flush();
    return $produto;
  }

  public function delete(Produto $produto){
    $this->getEntityManager()->remove($produto);
    $this->getEntityManager()->flush();
    return $produto;
  }

  public function clearBd($connection){
    $platform = $connection->getDatabasePlatform();
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
    $connection->executeUpdate($platform->getTruncateTableSQL("produto","true"));
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
  }
}

?>
