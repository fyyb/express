<?php
use App\Controllers\TesteController as Teste;
use App\Middlewares\TesteMiddleware as MidTeste;
use Fyyb\Request;
use Fyyb\Response;

// Rota Simples
$app->any('/', Teste::class . ":index");

// Rota Simples + 1 Mid
$app->any('/1mid', Teste::class . ":index")
    ->add(MidTeste::class . ":route1");

// Rota Simples + n Mid
$app->any('/nmid', Teste::class . ":index")
    ->add(MidTeste::class . ":route1", MidTeste::class . ":route2");

// Passagem de Parêmetros entre Request e Responde dentro dis Middlewares
$app->any('/teste', function (Request $req, Response $res) {
    echo '<pre>';
    echo '$req->teste';
    echo '<br>';
    print_r($req->teste);
    echo '<br>';
    echo '<br>';
    echo '$res->teste';
    echo '<br>';
    print_r($res->teste);
    echo '<br>';
    $res->send('Rota de Teste');
})->add(function (Request $req, Response $res, $next) {
    $dataReq = $req->teste ?? [];
    $dataReq[] = '1º Mid Req';
    $req->teste = $dataReq;

    $dataRes = $res->teste ?? [];
    $dataRes[] = '1º Mid Res';
    $res->teste = $dataRes;

    return $next($req, $res);
}, function (Request $req, Response $res, $next) {
    $dataReq = $req->teste ?? [];
    $dataReq[] = '2º Mid Req';
    $req->teste = $dataReq;

    $dataRes = $res->teste ?? [];
    $dataRes[] = '2º Mid Res';
    $res->teste = $dataRes;

    return $next($req, $res);
});

$app->any('/where/:id', function (Request $req, Response $res) {
    $data = $req->getParams();
    $res->json([
        "route" => $req->getURI(),
        "data" => $data,
    ]);
})->where([
    'id' => '[0-9]+',
]);

$app->any('/where/:texto', function (Request $req, Response $res) {
    $data = $req->getParams();
    $res->json([
        "route" => $req->getURI(),
        "data" => $data,
    ]);
})->where(['texto' => '[a-zA-Z]+']);
