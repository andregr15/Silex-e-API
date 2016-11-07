<?php

use AGR\Entity\Cliente;
use AGR\Service\ClienteService;
use AGR\Validator\ClienteValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once 'bootstrap.php';

$clientes = $app['controllers_factory'];

$app['cliente_service'] = function() use ($em){
      $service = new ClienteService(new Cliente(null, null, null, null), $em->getRepository('AGR\Entity\Cliente'));
      return $service;
};

$app['cliente_validator'] = function($app){
    $validator = new ClienteValidator($app['validator']);
    return $validator;
};

//fixture
$clientes->get('/fixture', function(Silex\Application $app) use($em) {
  try{
    $app['cliente_service']->fixture($app['clientes'], $em->getConnection());
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro fixture: '.  $e->getMessage(). "\n");
  }

  return new Response("Fixture executada", 200);
})
  ->bind("_clientes");

//listando todos os clientes
$clientes->get('/', function(Silex\Application $app){
   try{
        $clientes = $app['cliente_service']->findAll();
        $response = new Response($app['serializer']->serialize($clientes, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('clientes api' => 'Erro ao exibir todos os clientes: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarClientes");

//listando apenas um cliente
$clientes->get('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['cliente_validator']->validateId($id);
        if (count($errors) > 0) {
          return $app->json($errors);
        }
        
        $cliente = $app['cliente_service']->buscarClientePeloId($id);
        if(!isset($cliente)){
          return $app->json(array('clientes api' => 'não existe cliente cadastrado com o id '.$id.'!'));
        }

        $response = new Response($app['serializer']->serialize($cliente, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('clientes api' => 'Erro ao exibir um cliente: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarCliente");

//atualizar apenas um cliente
$clientes->put('/{id}', function(Silex\Application $app, Request $request, $id){
   try{
        $errors = $app['cliente_validator']->validateInsertData($request, $id);
        if (count($errors) > 0) {
          return $app->json($errors);
        }

        $cliente = $app['cliente_service']->buscarClientePeloId($id);
        if(!isset($cliente)){
          return $app->json(array('clientes api' => 'não existe cliente cadastrado com o id '.$id.'!'));
        }

        $cliente = $app['cliente_service']->atualizarCliente($app['cliente_validator']->getDados());

        if(isset($cliente)){
          return $app->json(array('clientes api' => 'cliente de id '.$id.' atualizado com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('clientes api' => 'Erro ao atualizar um cliente: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("atualizarCliente");

//inserir um cliente
$clientes->post('/', function(Silex\Application $app, Request $request){
   try{
        $errors = $app['cliente_validator']->validateUpdateData($request);
        if (count($errors) > 0) {
          return $app->json($errors);
        }

        $cliente = $app['cliente_service']->inserirCliente($app['cliente_validator']->getDados());

        if(isset($cliente)){
          return $app->json(array('clientes api' => 'cliente de id '.$cliente->getId().' inserido com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('clientes api' => 'Erro ao inserir um cliente: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("inserirCliente");

//excluindo um cliente
$clientes->delete('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['cliente_validator']->validateId($id);
        if (count($errors) > 0) {
          return $app->json($errors);
        }
        
        $cliente = $app['cliente_service']->buscarClientePeloId($id);
        if(!isset($cliente)){
          return $app->json(array('clientes api' => 'não existe cliente cadastrado com o id '.$id.'!'));
        }

        $cliente = $app['cliente_service']->excluirCliente($id);

        if(isset($cliente)){
          return $app->json(array('clientes api' => 'cliente de id '.$id.' excluido com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('clientes api' => 'Erro ao excluir um cliente: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("excluirCliente");

return $clientes;

?>
