<?php
namespace AGR\Repository;

use AGR\Entity\Tag;
use Doctrine\ORM\EntityRepository;

class TagRepository extends EntityRepository
{
  public function loadTagById($id){
    if(!$id){
      throw new InvalidArgumentException("Id inválido");
    }

    $tag = $this->findOneById($id);

    return $tag;
  }

  public function findTagByNome($nome){
    return $this->findByNome($nome);
  }
  
  public function findAllSortedById(){
    return $this
                ->getEntityManager()
                ->createQuery('select t from AGR\Entity\Tag t order by t.id asc')
                ->getResult();
  }

   public function findPaged($pages, $numByPage){
      $query = $this
                    ->getEntityManager()
                    ->createQuery('select t from AGR\Entity\Tag t ')
                    ->setFirstResult( ( $numByPage * ($pages-1) ) )
                    ->setMaxResults( $numByPage );
      return new \Doctrine\ORM\Tools\Pagination\Paginator($query);  
  }

  public function supportsClass($class){
    return $class === 'AGR\Entity\Tag';
  }

  public function insertUpdate(Tag $tag){
    $this->getEntityManager()->persist($tag);
    $this->getEntityManager()->flush();
    return $tag;
  }

  public function delete(Tag $tag){
    $this->getEntityManager()->remove($tag);
    $this->getEntityManager()->flush();
    return $tag;
  }

  public function clearBd($connection){
    $platform = $connection->getDatabasePlatform();
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
    $connection->executeUpdate($platform->getTruncateTableSQL("tag","true"));
    $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
  }

  public function getReference($path, $id){
    return $this->getEntityManager()->getReference($path, $id);
  }
}

?>
