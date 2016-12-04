<?php

use AGR\Entity\Produto;
use AGR\Service\ProdutoService;
use AGR\Validator\ProdutoValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

require_once 'bootstrap.php';

$produtos = $app['controllers_factory'];

$app['produto_service'] = function() use ($em){
    $service = new ProdutoService(new Produto(null, null, null, null), $em->getRepository('AGR\Entity\Produto'), $em->getRepository('AGR\Entity\Categoria'), $em->getRepository('AGR\Entity\Tag'));
    return $service;
};

$app['produto_validator'] = function($app){
    $validator = new ProdutoValidator($app['validator']);
    return $validator;
};

//listando todos os produtos
$produtos->get('/', function(Silex\Application $app){
   try{
        $produtos = $app['produto_service']->findAll();
        $response = new Response($app['serializer']->serialize($produtos, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao exibir todos os produtos: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarProdutos");

//listando todos os produtos paged
$produtos->get('/paginado/{pages}/{qtd}', function(Silex\Application $app, $pages, $qtd){
   try{
        $produtos = $app['produto_service']->findPaged($pages, $qtd);
        $response = new Response($app['serializer']->serialize($produtos, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao exibir todos os produtos: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarProdutosPaged");

//listando produto by nome
$produtos->get('/nome/{nome}', function(Silex\Application $app, $nome){
   try{
        $produtos = $app['produto_service']->findByNome($nome);
        $response = new Response($app['serializer']->serialize($produtos, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao exibir todos os produtos: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarProdutosByNome");


//listando apenas um produto
$produtos->get('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['produto_validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
        
        $produto = $app['produto_service']->buscarProdutoPeloId($id);
        if(!isset($produto)){
            return $app->json(array('produtos api' => 'não existe produto cadastrado com o id '.$id.'!'));
        }
        $response = new Response($app['serializer']->serialize($produto, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao exibir um produto: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarProduto");

//atualizar apenas um produto
$produtos->put('/{id}', function(Silex\Application $app, Request $request, $id){
   try{
        $errors = $app['produto_validator']->validateInsertData($request, $id);
        
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $produto = $app['produto_service']->buscarProdutoPeloId($id);
        if(!isset($produto)){
            return $app->json(array('produtos api' => 'não existe produto cadastrado com o id '.$id.'!'));
        }

        $produto = $app['produto_service']->atualizarProduto($app['produto_validator']->getDados());
        
        if(isset($produto)){
          return $app->json(array('produtos api' => 'produto de id '.$id.' atualizado com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao atualizar um produto: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("atualizarProduto");

//inserir um produto
$produtos->post('/', function(Silex\Application $app, Request $request){
   try{
        $errors = $app['produto_validator']->validateUpdateData($request);
        
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $produto = $app['produto_service']->inserirProduto($app['produto_validator']->getDados());
        
        if(isset($produto)){
            return $app->json(array('produtos api' => 'produto de id '.$produto->getId().' inserido com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao inserir um produto: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("inserirProduto");

//excluindo um produto
$produtos->delete('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['produto_validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
       
        $produto = $app['produto_service']->buscarProdutoPeloId($id);
        if(!isset($produto)){
            return $app->json(array('produtos api' => 'não existe produto cadastrado com o id '.$id.'!'));
        }

        $produto = $app['produto_service']->excluirProduto($id);
    
        if(isset($produto)){
            return $app->json(array('produtos api' => 'produto de id '.$id.' excluido com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao excluir um produto: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("excluirProduto");

return $produtos;

?>