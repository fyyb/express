<?php

require __DIR__ . DIRECTORY_SEPARATOR . "settings.php";
require __DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

use Fyyb\Cors;
use Fyyb\Request;
use Fyyb\Response;
use Fyyb\Router;

$app = Router::getInstance();
$app->setErrorResponseType('json');

require __DIR__ . "/TesteRouterGroups.php";
require __DIR__ . "/TesteRouterGroup.php";
require __DIR__ . "/TesteRouterSimples.php";

$app->use('/use', './App/Routes/TesteRouterGroups.php');
$app->use('/use', './App/Routes/TesteRouterGroup.php');
$app->use('/use', './App/Routes/TesteRouterSimples.php');

/**
 * Parametros pessados com Route Params
 */
$app->any('/params/route/:param1[/:param2]', function (Request $req, Response $res) {
    echo '<pre>';
    print_r($req->getParams());
    exit;
});

/**
 * Parametros pessados no Body com JSON
 * OU
 * Parametros pessados com Multipart Form via POST
 */
$app->any('/params/body', function (Request $req, Response $res) {
    echo '<pre>';
    print_r($req->getParsedBody());
    exit;
});

/**
 * Parametros pessados com Query String
 */
$app->any('/params/qs', function (Request $req, Response $res) {
    echo '<pre>';
    print_r($req->getQueryString());
    exit;
});

/**
 * Parametros pessados com Query String
 */
$app->any('/params/file', function (Request $req, Response $res) {
    echo '<pre>';
    print_r($req->getUploadedFiles());
    exit;
});

$app->fallback(function (Request $req, Response $res) {
    $res->json(['error' => 404], 404);
});

$cors = Cors::getInstance();
$cors->setOriginCors('*');
$cors->setHeadersCors('authorization');
$cors->setMethodsCors('*');

$app->run();