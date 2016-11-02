<?php

require_once __DIR__.'/../vendor/autoload.php';
require_once 'bootstrap.php';

use Symfony\Component\HttpFoundation\Response;

$app->mount("/api/clientes", include 'cliente.php');
$app->mount("/produtos", include 'produto.php');

$app->mount("/api/produtos", include 'produtoApi.php');

$app->run();

?>
