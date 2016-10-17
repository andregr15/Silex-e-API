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

$produtos->get('/fixture', function(Silex\Application $app) use($em) {
  try{
    $app['produto_service']->fixture($app['produtos'], $em->getConnection());
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro fixture: '.  $e->getMessage(). "\n");
  }

  return new Response("Fixture executada", 200);
})
  ->bind("_produtos");

$produtos->get('/', function(Silex\Application $app)  {
    try{
        $produtos = $app['produto_service']->findAll();
    }
    catch(Exception $e) {
        $app->abort(500, 'Erro exibir todos os produtos: '.  $e->getMessage(). "\n");
    }
    return $app['twig']->render('produtos.twig', array("produtos" => $produtos));
})
  ->bind("produtos");

$produtos->get('/novo', function(Silex\Application $app) {
  return $app['twig']->render('novo_produto.twig', array());
})
  ->bind("novoProduto");

$produtos->post('/new', function(Request $request, Silex\Application $app) use($em) {
  try{
    $dados['nome'] = $request->request->get('nome');
    $dados['descricao'] = $request->request->get('descricao');
    $dados['valor'] = $request->request->get('valor'); 
    
    $produto = $app['produto_service']->inserirProduto($dados);
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro ao incluir um produto: '.  $e->getMessage(). "\n");
  }

  return $app['twig']->render('produto_incluido.twig', array("id" => $produto->getId()));
})
  ->bind('incluirProduto');


$produtos->get('/editar/{id}', function(Silex\Application $app, $id) use($em) {
  try{
    if(!isset($id))
    {
      $app->abort(500, "O id não pode ser nulo");
    }

    $produto = $app['produto_service']->buscarProdutoPeloId($id);

    if(!isset($produto))
    {
      $app->abort(500, "Não existe um produto com o id solicitado");
    }
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro ao editar um produto: '.  $e->getMessage(). "\n");
  }

  return $app['twig']->render('editar_produto.twig', array('produto' => $produto));
})
  ->bind("editarProduto");

$produtos->post('/update/{id}', function(Request $request, Silex\Application $app) use($em) {
  try{
    $dados['id'] = $request->request->get('id');
    $dados['nome'] = $request->request->get('nome');
    $dados['descricao'] = $request->request->get('descricao');
    $dados['valor'] = $request->request->get('valor'); 
    
    $produto = $app['produto_service']->atualizarProduto($dados);
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro ao atualizar um produto: '.  $e->getMessage(). "\n");
  }

  return $app['twig']->render('produto_atualizado.twig', array("id" => $produto->getId()));
})
->bind("atualizarProduto");

$produtos->get('/excluir/{id}', function(Silex\Application $app, $id) use($em) {
  try{
    if(!isset($id))
    {
      $app->abort(500, "O id não pode ser nulo");
    }

    $produto = $app['produto_service']->excluirProduto($id);
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro ao excluir um produto: '.  $e->getMessage(). "\n");
  }

  return $app['twig']->render('produto_excluido.twig', array());
})
  ->bind("excluirProduto");

return $produtos;

?>
