<?php

namespace App\Controllers;

use Fyyb\Request;
use Fyyb\Response;

class TesteController {
  public function index(Request $req, Response $res)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    $res->send('Rota: '.$route);
  }
  
  public function conflitoRoute(Request $req, Response $res)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    $res->send('TESTE DE CONFLITO, NÃO PODE APARECER MID<br/>Rota: '.$route);
  }
}