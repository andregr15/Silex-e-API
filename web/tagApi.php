<?php

use AGR\Entity\Tag;
use AGR\Service\TagService;
use AGR\Validator\CategoriaTagValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

require_once 'bootstrap.php';

$tag = $app['controllers_factory'];

$app['service'] = function() use ($em){
    $service = new TagService(new Tag(null, null), $em->getRepository('AGR\Entity\Tag'));
    return $service;
};

$app['validator'] = function($app){
    $validator = new CategoriaTagValidator($app['validator']);
    return $validator;
};

//listando todas as tags
$tag->get('/', function(Silex\Application $app){
   try{
        $tags = $app['service']->findAll();
        $response = new Response($app['serializer']->serialize($tags, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao exibir todas as tags: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarTags");

//listando todas as tags paged
$tag->get('/paginado/{pages}/{qtd}', function(Silex\Application $app, $pages, $qtd){
   try{
        $tags = $app['service']->findPaged($pages, $qtd);
        $response = new Response($app['serializer']->serialize($tags, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao exibir todas as tags: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarTagsPaged");

//listando tag by nome
$tag->get('/{nome}', function(Silex\Application $app, $nome){
   try{
        $tags = $app['service']->findByNome($nome);
        $response = new Response($app['serializer']->serialize($tags, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao exibir todas as tags: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarTagsByNome");


//listando apenas uma tag
$tag->get('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
       
        $tag = $app['service']->buscarTagPeloId($id);
        if(!isset($tag)){
            return $app->json(array('tag api' => 'não existe tag cadastrada com o id '.$id.'!'));
        }

        $response = new Response($app['serializer']->serialize($tag, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao exibir uma tag: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarTag");

//atualizar apenas uma tag
$tag->put('/{id}', function(Silex\Application $app, Request $request, $id){
   try{
        $errors = $app['validator']->validateInsertData($request, $id);
        
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $tag = $app['service']->buscarTagPeloId($id);
        if(!isset($tag)){
            return $app->json(array('tag api' => 'não existe tag cadastrada com o id '.$id.'!'));
        }

        $tag = $app['service']->atualizarTag($app['validator']->getDados());
        
        if(isset($tag)){
          return $app->json(array('tag api' => 'tag de id '.$id.' atualizada com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao atualizar uma tag: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("atualizarTag");

//inserir uma tag
$tag->post('/', function(Silex\Application $app, Request $request){
   try{
        $errors = $app['validator']->validateUpdateData($request);
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $tag = $app['service']->inserirTag($app['validator']->getDados());
        
        if(isset($tag)){
            return $app->json(array('tag api' => 'tag de id '.$produto->getId().' inserida com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao inserir uma tag: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("inserirTag");

//excluindo uma tag
$tag->delete('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
       
        $tag = $app['service']->buscarTagPeloId($id);
        if(!isset($tag)){
            return $app->json(array('tag api' => 'não existe tag cadastrada com o id '.$id.'!'));
        }

        $tag = $app['service']->excluirTag($id);
    
        if(isset($tag)){
            return $app->json(array('tag api' => 'tag de id '.$id.' excluida com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao excluir uma tag: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("excluirTag");

return $tag;

?>