<?php
require_once __DIR__.'/../vendor/autoload.php';

use AGR\Entity\Cliente;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\SerializerServiceProvider());

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

return $app;
?>
