<?php
  // Load Controllers
  use App\Controllers\TesteController as Teste;
  // Load Middlewares
  use App\Middlewares\TesteMiddleware as MidTeste;
  
  // Grupo sem Mid e SUBGRUPOS
  $app->group('/groups', function($group) {
      $group->any('/', Teste::class.":index");

      $group->any('/1mid', Teste::class.":index")
          ->add(MidTeste::class.":route1");
      
      $group->any('/nmid', Teste::class.":index")
          ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      
      // Subgrupo sem mid
      $group->group('/sub', function($sub1) {
          $sub1->any('/semmid', Teste::class.":index");

          $sub1->any('/1mid', Teste::class.":index")
              ->add(MidTeste::class.":route1");
      
          $sub1->any('/nmid', Teste::class.":index")
              ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      });

      // Subgrupo com 1 mid
      $group->group('/sub-1mid', function($sub2) {
          $sub2->any('/semmid', Teste::class.":index");

          $sub2->any('/1mid', Teste::class.":index")
              ->add(MidTeste::class.":route1");
      
          $sub2->any('/nmid', Teste::class.":index")
              ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      })->add(MidTeste::class.":subgroup1");

      // Subgrupo com n mid
      $group->group('/sub-nmid', function($sub3) {
          $sub3->any('/semmid', Teste::class.":index");

          $sub3->any('/1mid', Teste::class.":index")
              ->add(MidTeste::class.":route1");
      
          $sub3->any('/nmid', Teste::class.":index")
              ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      })->add(MidTeste::class.":subgroup1", MidTeste::class.":subgroup2");
  });

  $app->any('/groups/sub/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups/sub/nmid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups/sub-1mid/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups/sub-1mid/nmid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups/sub-nmid/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups/sub-nmid/nmid/conflito', Teste::class.":conflitoRoute");

  // Grupo com 1 Mid e SUBGRUPOS
  $app->group('/groups-1mid', function($group) {
      $group->any('/', Teste::class.":index");

      $group->any('/1mid', Teste::class.":index")
          ->add(MidTeste::class.":route1");
      
      $group->any('/nmid', Teste::class.":index")
          ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      
      // Subgrupo sem mid
      $group->group('/sub', function($sub1) {
          $sub1->any('/semmid', Teste::class.":index");

          $sub1->any('/1mid', Teste::class.":index")
              ->add(MidTeste::class.":route1");
      
          $sub1->any('/nmid', Teste::class.":index")
              ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      });

      // Subgrupo com 1 mid
      $group->group('/sub-1mid', function($sub2) {
          $sub2->any('/semmid', Teste::class.":index");

          $sub2->any('/1mid', Teste::class.":index")
              ->add(MidTeste::class.":route1");
      
          $sub2->any('/nmid', Teste::class.":index")
              ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      })->add(MidTeste::class.":subgroup1");

      // // Subgrupo com n mid
      // $group->group('/sub-nmid', function($sub2) {
      //     $sub2->any('/semmid', Teste::class.":index");

      //     $sub2->any('/1mid', Teste::class.":index")
      //         ->add(MidTeste::class.":route1");
      
      //     $sub2->any('/nmid', Teste::class.":index")
      //         ->add(MidTeste::class.":route1", MidTeste::class.":route2");
      // })->add(MidTeste::class.":subgroup1", MidTeste::class.":subgroup2");
  })->add(MidTeste::class.":group1");

  $app->any('/groups-1mid/sub/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups-1mid/sub/nmid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups-1mid/sub-1mid/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups-1mid/sub-1mid/nmid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups-1mid/sub-nmid/1mid/conflito', Teste::class.":conflitoRoute");
  $app->any('/groups-1mid/sub-nmid/nmid/conflito', Teste::class.":conflitoRoute");