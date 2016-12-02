<?php

use AGR\Entity\Categoria;
use AGR\Service\CategoriaService;
use AGR\Validator\CategoriaTagValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

require_once 'bootstrap.php';

$categoria = $app['controllers_factory'];

$app['service'] = function() use ($em){
    $service = new CategoriaService(new Categoria(null, null), $em->getRepository('AGR\Entity\Categoria'));
    return $service;
};

$app['validator'] = function($app){
    $validator = new CategoriaTagValidator($app['validator']);
    return $validator;
};

//listando todas as categorias
$categoria->get('/', function(Silex\Application $app){
   try{
        $categorias = $app['service']->findAll();
        $response = new Response($app['serializer']->serialize($categorias, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('categoria api' => 'Erro ao exibir todas as categorias: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarCategorias");

//listando todos os categorias paged
$categoria->get('/paginado/{pages}/{qtd}', function(Silex\Application $app, $pages, $qtd){
   try{
        $categorias = $app['service']->findPaged($pages, $qtd);
        $response = new Response($app['serializer']->serialize($categorias, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('categoria api' => 'Erro ao exibir todas as categorias: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarCategoriasPaged");

//listando categoria by nome
$categoria->get('/{nome}', function(Silex\Application $app, $nome){
   try{
        $categorias = $app['service']->findByNome($nome);
        $response = new Response($app['serializer']->serialize($categorias, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('categoria api' => 'Erro ao exibir todas as categorias: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarCategoriasByNome");


//listando apenas uma categoria
$categoria->get('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
       
        $categoria = $app['service']->buscarCategoriaPeloId($id);
        if(!isset($categoria)){
            return $app->json(array('categorias api' => 'não existe categoria cadastrada com o id '.$id.'!'));
        }

        $response = new Response($app['serializer']->serialize($categoria, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('categoria api' => 'Erro ao exibir uma categoria: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarCategoria");

//atualizar apenas uma categoria
$categoria->put('/{id}', function(Silex\Application $app, Request $request, $id){
   try{
        $errors = $app['validator']->validateInsertData($request, $id);
        
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $categoria = $app['service']->buscarCategoriaPeloId($id);
        if(!isset($categoria)){
            return $app->json(array('categoria api' => 'não existe categoria cadastrada com o id '.$id.'!'));
        }

        $categoria = $app['service']->atualizarCategoria($app['validator']->getDados());
        
        if(isset($categoria)){
          return $app->json(array('categoria api' => 'categoria de id '.$id.' atualizada com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('categoria api' => 'Erro ao atualizar uma categoria: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("atualizarCatgoria");

//inserir uma categoria
$categoria->post('/', function(Silex\Application $app, Request $request){
   try{
        $errors = $app['validator']->validateUpdateData($request);
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $categoria = $app['service']->inserirCategoria($app['validator']->getDados());
        
        if(isset($categoria)){
            return $app->json(array('categoria api' => 'categoria de id '.$produto->getId().' inserida com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('categoria api' => 'Erro ao inserir uma categoria: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("inserirCategoria");

//excluindo uma categoria
$categoria->delete('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
       
        $categoria = $app['service']->buscarCategoriaPeloId($id);
        if(!isset($categoria)){
            return $app->json(array('categoria api' => 'não existe categoria cadastrada com o id '.$id.'!'));
        }

        $categoria = $app['service']->excluirCategoria($id);
    
        if(isset($categoria)){
            return $app->json(array('categoria api' => 'categoria de id '.$id.' excluida com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('categoria api' => 'Erro ao excluir uma categoria: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("excluirCategoria");

return $categoria;

?>