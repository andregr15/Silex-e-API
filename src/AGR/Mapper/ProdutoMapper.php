<?php

namespace AGR\Mapper;

use AGR\Entity\Produto;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

use Doctrine\ORM\EntityRepository;

class ProdutoMapper extends EntityRepository
{
  public function loadProdutoById($id){
    if(!$id){
      throw new InvalidArgumentException("Id inválido");
    }

    $post = $this->findOneById($id);

    return $post;
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
    $this->getEntityManager()->merge($produto);
    $this->getEntityManager()->flush($produto);
    return $produto;
  }

  public function delete(Produto $produto){
    $this->getEntityManager()->remove($produto);
    $this->getEntityManager()->flush($produto);
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
