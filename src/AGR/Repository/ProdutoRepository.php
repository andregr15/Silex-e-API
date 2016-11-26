<?php
namespace AGR\Repository;

use AGR\Entity\Produto;
use Doctrine\ORM\EntityRepository;

class ProdutoRepository extends EntityRepository
{
  public function loadProdutoById($id){
    if(!$id){
      throw new InvalidArgumentException("Id inválido");
    }

    $produto = $this->findOneById($id);

    return $produto;
  }

  public function findProdutoByNome($nome){
    return $this->findByNome($nome);
  }
  
  public function findAllShortedById(){
    return $this
                ->getEntityManager()
                ->createQuery('select p from AGR\Entity\Produto p order by p.id asc')
                ->getResult();
  }

   public function findPaged($pages, $numByPage){
      $query = $this
                    ->getEntityManager()
                    ->createQuery('select p from AGR\Entity\Produto p ')
                    ->setFirstResult( ( $numByPage * ($pages-1) ) )
                    ->setMaxResults( $numByPage );
      return new \Doctrine\ORM\Tools\Pagination\Paginator($query);  
  }

  public function supportsClass($class){
    return $class === 'AGR\Entity\Produto';
  }

  public function insertUpdate(Produto $produto){
    $this->getEntityManager()->persist($produto);
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

  public function getReference($path, $id){
    return $this->getEntityManager()->getReference($path, $id);
  }
}

?>
