<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once 'bootstrap.php';

$app->mount("/api/clientes", include 'cliente.php');
$app->mount("/produtos", include 'produto.php');

$app->mount("/api/produtos", include 'produtoApi.php');
$app->mount("/api/tags", include 'tagApi.php');
$app->mount("/api/categorias", include 'categoriaApi.php');

$app->run();

?>
