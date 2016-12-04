<?php

use AGR\Entity\Tag;
use AGR\Service\TagService;
use AGR\Validator\CategoriaTagValidator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

require_once 'bootstrap.php';

$tag = $app['controllers_factory'];

$app['tag_service'] = function() use ($em){
    $service = new TagService(new Tag(null, null), $em->getRepository('AGR\Entity\Tag'));
    return $service;
};

$app['tag_validator'] = function($app){
    $validator = new CategoriaTagValidator($app['validator']);
    return $validator;
};

//fixture
$tag->get('/fixture', function(Silex\Application $app) use($em) {
  try{
    $app['tag_service']->fixture($app['tags'], $em->getConnection());
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro fixture: '.  $e->getMessage(). "\n");
  }

  return new Response("Fixture executada", 200);
})
  ->bind("_tags");

//listando todas as tags
$tag->get('/', function(Silex\Application $app){
   try{
        $tags = $app['tag_service']->findAll();
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
        $tags = $app['tag_service']->findPaged($pages, $qtd);
        $response = new Response($app['serializer']->serialize($tags, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao exibir todas as tags: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarTagsPaged");

//listando tag by nome
$tag->get('/nome/{nome}', function(Silex\Application $app, $nome){
   try{
        $tags = $app['tag_service']->findByNome($nome);
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
        $errors = $app['tag_validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
       
        $tag = $app['tag_service']->buscarTagPeloId($id);
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
        $errors = $app['tag_validator']->validateInsertData($request, $id);
        
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $tag = $app['tag_service']->buscarTagPeloId($id);
        if(!isset($tag)){
            return $app->json(array('tag api' => 'não existe tag cadastrada com o id '.$id.'!'));
        }

        $tag = $app['tag_service']->atualizarTag($app['tag_validator']->getDados());
        
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
        $errors = $app['tag_validator']->validateUpdateData($request);
        if (count($errors) > 0) {
            return $app->json($errors);
        }

        $tag = $app['tag_service']->inserirTag($app['tag_validator']->getDados());
        
        if(isset($tag)){
            return $app->json(array('tag api' => 'tag de id '.$tag->getId().' inserida com sucesso!'));
        }
    }
    catch(Exception $e) {
        $app->json(array('tag api' => 'Erro ao inserir uma tag: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("inserirTag");

//excluindo uma tag
$tag->delete('/{id}', function(Silex\Application $app, $id){
   try{
        $errors = $app['tag_validator']->validateId($id);
        if (count($errors) > 0) {
            return $app->json($errors);
        }
       
        $tag = $app['tag_service']->buscarTagPeloId($id);
        if(!isset($tag)){
            return $app->json(array('tag api' => 'não existe tag cadastrada com o id '.$id.'!'));
        }

        $tag = $app['tag_service']->excluirTag($id);
    
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