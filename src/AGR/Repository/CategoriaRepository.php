<?php
namespace AGR\Repository;

use AGR\Entity\Categoria;
use Doctrine\ORM\EntityRepository;

class CategoriaRepository extends EntityRepository
{
  public function loadCategoriaById($id){
    if(!$id){
      throw new InvalidArgumentException("Id inválido");
    }

    $categoria = $this->findOneById($id);

    return $categoria;
  }

  public function findCategoriaByNome($nome){
    return $this->findByNome($nome);
  }
  
  public function findAllSortedById(){
    return $this
                ->getEntityManager()
                ->createQuery('select c from AGR\Entity\Categoria c order by c.id asc')
                ->getResult();
  }

   public function findPaged($pages, $numByPage){
      $query = $this
                    ->getEntityManager()
                    ->createQuery('select c from AGR\Entity\Categoria c ')
                    ->setFirstResult( ( $numByPage * ($pages-1) ) )
                    ->setMaxResults( $numByPage );
      return new \Doctrine\ORM\Tools\Pagination\Paginator($query);  
  }

  public function supportsClass($class){
    return $class === 'AGR\Entity\Categoria';
  }

  public function insertUpdate(Categoria $categoria){
    $this->getEntityManager()->persist($categoria);
    $this->getEntityManager()->flush();
    return $categoria;
  }

  public function delete(Categoria $categoria){
    $this->getEntityManager()->remove($categoria);
    $this->getEntityManager()->flush();
    return $categoria;
  }

  public function clearBd($connection){
    $platform = $connection->getDatabasePlatform();
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
    $connection->executeUpdate($platform->getTruncateTableSQL("categoria","true"));
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
  }

  public function getReference($path, $id){
    return $this->getEntityManager()->getReference($path, $id);
  }
}

?>
