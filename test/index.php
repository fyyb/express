<?php

require __DIR__ . "./settings.php";
require __DIR__ . "./vendor/autoload.php";

use Fyyb\Router;
use Fyyb\Request;
use Fyyb\Response;

$app = Router::getInstance();

$app->get('/', function(Request $req, Response $res) {
    $res->send('Index');
});

$app->run();

