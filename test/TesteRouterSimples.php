<?php
    // Load Controllers
    use App\Controllers\TesteController as Teste;
    // Load Middlewares
    use App\Middlewares\TesteMiddleware as MidTeste;
    
    // Rota Simples
    $app->any('/', Teste::class.":index");
    
    // Rota Simples + 1 Mid
    $app->any('/1mid', Teste::class.":index")
        ->add(MidTeste::class.":route1");

    // Rota Simples + n Mid
    $app->any('/nmid', Teste::class.":index")
        ->add(MidTeste::class.":route1", MidTeste::class.":route2");
