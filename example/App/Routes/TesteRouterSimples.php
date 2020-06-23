<?php
    use Fyyb\Request;
use Fyyb\Response;

    // Load Controllers
    use App\Controllers\TesteController as Teste;
    // Load Middlewares
    use App\Middlewares\TesteMiddleware as MidTeste;

    // Rota Simples
    $this->any('/', Teste::class.":index");
    
    // Rota Simples + 1 Mid
    $this->any('/1mid', Teste::class.":index")
        ->add(MidTeste::class.":route1");

    // Rota Simples + n Mid
    $this->any('/nmid', Teste::class.":index")
        ->add(MidTeste::class.":route1", MidTeste::class.":route2");

    // Rota teste middlewares
    $this->any('/teste', function (Request $req, Response $res) {
        echo $req->teste;
        echo '<br>';
        echo $res->teste;
        echo '<br>';
        $res->send('Rota de Teste');
    })->add(function (Request $req, Response $res, $next) {
        $req->teste = 'req teste';
        $res->teste = 'res teste';
        return $next($req, $res);
    }, function (Request $req, Response $res, $next) {
        if ($req->teste === 'req teste') {
            $req->teste = 'Req Passou pelo 2º Mid';
        }
        if ($res->teste === 'res teste') {
            $res->teste = 'Res Passou pelo 2º Mid';
        }
        return $next($req, $res);
    });
