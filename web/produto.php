<?php

use AGR\Entity\Produto;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once 'bootstrap.php';

$produtos = $app['controllers_factory'];

$app['produto_service'] = function() use ($em){
      $repo = $em->getRepository('AGR\Entity\Produto');
      return $repo;
};

$produtos->get('/fixture', function(Silex\Application $app) use($em) {
  $prods = $app['produtos'];

  try{
    $produtoMapper = $app['produto_service'];
    $produtoMapper->clearBd($em->getConnection());
    foreach($prods as $produto)
    {
      $produtoMapper->insert($produto);
    }
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro fixture: '.  $e->getMessage(). "\n");
  }

  return new Response("Fixture executada", 200);
})
  ->bind("_produtos");

$produtos->get('/', function(Silex\Application $app)  {
    try{
        $produtoMapper = $app['produto_service'];
        $produtos = $produtoMapper->findAll();
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
    $nome = $request->request->get('nome');
    $descricao = $request->request->get('descricao');
    $valor = $request->request->get('valor');

    $produto = new Produto(null, $nome, $descricao, $valor);

    $produtoMapper = $app['produto_service'];
    $produto = $produtoMapper->insert($produto);
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

    $produtoMapper = $app['produto_service'];
    $produto = $produtoMapper->loadProdutoById($id);

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
    $id = $request->request->get('id');
    $nome = $request->request->get('nome');
    $descricao = $request->request->get('descricao');
    $valor = $request->request->get('valor');

    $produtoMapper = $app['produto_service'];
    $produto = $produtoMapper->loadProdutoById($id);

    $produto->setNome($nome);
    $produto->setDescricao($descricao);
    $produto->setValor($valor);

    $produto = $produtoMapper->update($produto);
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

    $produtoMapper = $app['produto_service'];
    $produto = $produtoMapper->loadProdutoById($id);

    if(!isset($produto))
    {
      $app->abort(500, "Não existe um produto com o id solicitado");
    }

    $produtoMapper->delete($produto);
  }
  catch(Exception $e) {
    $app->abort(500, 'Erro ao excluir um produto: '.  $e->getMessage(). "\n");
  }

  return $app['twig']->render('produto_excluido.twig', array());
})
  ->bind("excluirProduto");

return $produtos;

?>
