<?php

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once 'bootstrap.php';

$clientes = $app['controllers_factory'];

$clientes->get('/', function(Silex\Application $app)  {
  $response = new Response($app['serializer']->serialize($app['clientes'], 'json'));
  $response->headers->set('Content-Type', 'application/json');

  return $response;
})
  ->bind("clientes");

return $clientes;

?>
