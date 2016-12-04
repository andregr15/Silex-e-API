<?php
require_once __DIR__.'/../vendor/autoload.php';

use AGR\Entity\Cliente;
use AGR\Entity\Produto;
use AGR\Entity\Categoria;
use AGR\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\SerializerServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$paths = array(__DIR__. DIRECTORY_SEPARATOR . '../src/');
$isDevMode = false;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '1234',
    'dbname'   => 'silex',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$em = EntityManager::create($dbParams, $config);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/../views'
));

$app['clientes'] = function() {
  return array(
    new Cliente(1, "André", "111.000.333-10", "andre@gmail.com"),
    new Cliente(2, "Carlos", "223.154.315-15", "carlos@gmail.com"),
    new Cliente(3, "José", "01.368.548/0154-01", "jose@gmail.com"),
    new Cliente(4, "Maria", "10.000.587/0001-10", "maria@gmail.com"),
    new Cliente(5, "Lúcia", "589.224.321-50", "lucia@gmail.com"),
    new Cliente(6, "Sandra", "11.587.879/0023-85", "sandra@gmail.com")
   );
};

$app['produtos'] = function() {
  return array(
    new Produto(1, "Notebook xpto", "notebook para jogos", 1500.00),
    new Produto(2, "Computador zte", "computador para empresas", 1000.00),
    new Produto(3, "Mouse usb", "mouse usb", 30.00),
    new Produto(4, "Teclado usb", "teclado usb", 30.00),
    new Produto(5, "Leitor de DVD", "Leitor de DVD", 40.00),
    new Produto(6, "Gravador de DVD", "Grava dvd e cd", 60.00)
   );
};

$app['categorias'] = function() {
  return array(
    new Categoria(1, "Categoria 1"),
    new Categoria(2, "Categoria 2"),
    new Categoria(3, "Categoria 3"),
    new Categoria(4, "Categoria 4"),
   );
};

$app['tags'] = function() {
  return array(
    new Tag(1, "Tag 1"),
    new Tag(2, "Tag 2"),
    new Tag(3, "Tag 3"),
    new Tag(4, "Tag 4"),
   );
};

return $app;
?>
