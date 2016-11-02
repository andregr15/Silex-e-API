<?php

use AGR\Entity\Produto;
use AGR\Service\ProdutoService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once 'bootstrap.php';

$produtos = $app['controllers_factory'];

$app['produto_service'] = function() use ($em){
      $service = new ProdutoService(new Produto(null, null, null, null), $em->getRepository('AGR\Entity\Produto'));
      return $service;
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

//listando apenas um produto
$produtos->get('/{id}', function(Silex\Application $app, $id){
   try{
        if(is_numeric($id)){
          $produto = $app['produto_service']->buscarProdutoPeloId($id);
          if(!isset($produto)){
            return $app->json(array('produtos api' => 'não existe produto cadastrado com o id '.$id.'!'));
          }

          $response = new Response($app['serializer']->serialize($produto, 'json'));
          $response->headers->set('Content-Type', 'application/json');
          return $response;
        }
        return $app->json(array('produtos api' => 'o id deve ser um número inteiro e positivo'));
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao exibir um produto: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("listarProduto");


//atualizar apenas um produto
$produtos->put('/{id}', function(Silex\Application $app, Request $request, $id){
   try{
        if(is_numeric($id)){
          $produto = $app['produto_service']->buscarProdutoPeloId($id);
          
          if(!isset($produto)){
            return $app->json(array('produtos api' => 'não existe produto cadastrado com o id '.$id.'!'));
          }

          $dados['id'] = $id;
          $dados['nome'] = $request->get('nome');
          $dados['descricao'] = $request->get('descricao');
          $dados['valor'] = $request->get('valor');

          if(!isset($dados['nome'])){
            return $app->json(array('produtos api' => 'parâmetro nome é obrigatório!'));
          } 
          
          if(!isset($dados['descricao'])){
            return $app->json(array('produtos api' => 'parâmetro descricao é obrigatório!'));
          } 
            
          if(!isset($dados['valor'])){
            return $app->json(array('produtos api' => 'parâmetro valor é obrigatório!'));
          } 

          if(!is_numeric($dados['valor'])){
            return $app->json(array('produtos api' => 'parâmetro valor deve ser um número decimal positivo!'));
          } 

          $produto = $app['produto_service']->atualizarProduto($dados);
        
          if(isset($produto)){
            return $app->json(array('produtos api' => 'produto de id '.$id.' atualizado com sucesso!'));
          }
        }
        return $app->json(array('produtos api' => 'o id deve ser um número inteiro e positivo'));
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao atualizar um produto: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("atualizarProduto");

//inserir um produto
$produtos->post('/', function(Silex\Application $app, Request $request){
   try{
            $dados['nome'] = $request->request->get('nome');
            $dados['descricao'] = $request->request->get('descricao');
            $dados['valor'] = $request->request->get('valor'); 
    
            if(!isset($dados['nome'])){
                return $app->json(array('produtos api' => 'parâmetro nome é obrigatório!'));
            } 
            
            if(!isset($dados['descricao'])){
                return $app->json(array('produtos api' => 'parâmetro descricao é obrigatório!'));
            } 
                
            if(!isset($dados['valor'])){
                return $app->json(array('produtos api' => 'parâmetro valor é obrigatório!'));
            } 

            if(!is_numeric($dados['valor'])){
                return $app->json(array('produtos api' => 'parâmetro valor deve ser um número decimal positivo!'));
            } 

            $produto = $app['produto_service']->inserirProduto($dados);
            
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
        if(is_numeric($id)){
          $produto = $app['produto_service']->buscarProdutoPeloId($id);
          if(!isset($produto)){
            return $app->json(array('produtos api' => 'não existe produto cadastrado com o id '.$id.'!'));
          }

          $produto = $app['produto_service']->excluirProduto($id);
        
          if(isset($produto)){
            return $app->json(array('produtos api' => 'produto de id '.$id.' excluido com sucesso!'));
          }
        }
        return $app->json(array('produtos api' => 'o id deve ser um número inteiro e positivo'));
    }
    catch(Exception $e) {
        $app->json(array('produtos api' => 'Erro ao excluir um produto: '.  $e->getMessage(). "\n"), 500);
    }
})->bind("excluirProduto");

return $produtos;

?>