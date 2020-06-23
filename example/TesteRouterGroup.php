<?php

  // Load Controllers
  use App\Controllers\TesteController as Teste;
  // Load Middlewares
  use App\Middlewares\TesteMiddleware as MidTeste;

  // Grupo Simples
  $app->group('/group', function ($group) {
      $group->any('/', Teste::class.":index");
      
      $group->any('/1mid', Teste::class.":index")
          ->add(MidTeste::class.":route1");
      
      $group->any('/nmid', Teste::class.":index")
          ->add(MidTeste::class.":route1", MidTeste::class.":route2");
  });

  $app->any('/group/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/group/nmid/conflito', Teste::class.":conflitoRoute");

  // Grupo Simples + 1 Mid
  $app->group('/group-1mid', function ($group) {
      $group->any('/', Teste::class.":index");
  
      $group->any('/1mid', Teste::class.":index")
          ->add(MidTeste::class.":route1");
      
          
      $group->any('/nmid', Teste::class.":index")
          ->add(MidTeste::class.":route1", MidTeste::class.":route2");
  })->add(MidTeste::class.":group1");
  
  $app->any('/group-1mid/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/group-1mid/nmid/conflito', Teste::class.":conflitoRoute");
      
  // Grupo Simples + n Mid
  $app->group('/group-nmid', function ($group) {
      $group->any('/', Teste::class.":index");
  
      $group->any('/1mid', Teste::class.":index")
          ->add(MidTeste::class.":route1");
  
      $group->any('/nmid', Teste::class.":index")
          ->add(MidTeste::class.":route1", MidTeste::class.":route2");
  })->add(MidTeste::class.":group1", MidTeste::class.":group2");

  $app->any('/group-nmid/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/group-nmid/nmid/conflito', Teste::class.":conflitoRoute");
