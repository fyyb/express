<?php

namespace App\Middlewares;

use Fyyb\Request;
use Fyyb\Response;

class TesteMiddleware
{
  
  public function route1(Request $req, Response $res, $next)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    echo 'Mid 1 da Rota: '.$route.'<br/>';
    return $next($req, $res);
  }
  
  public function route2(Request $req, Response $res, $next)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    echo 'Mid 2 da Rota: '.$route.'<br/>';
    return $next($req, $res);
  }
  
  public function group1(Request $req, Response $res, $next)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    echo 'Mid 1 do Grupo: '.$route.'<br/>';
    return $next($req, $res);
  }
  
  public function group2(Request $req, Response $res, $next)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    echo 'Mid 2 do Grupo: '.$route.'<br/>';
    return $next($req, $res);
  }

  public function subgroup1(Request $req, Response $res, $next)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    echo 'Mid 1 do Subgrupo: '.$route.'<br/>';
    return $next($req, $res);
  }

  public function subgroup2(Request $req, Response $res, $next)
  {
    $route = str_replace('//', '/', '/'.$req->getURI());
    echo 'Mid 2 do Subgrupo: '.$route.'<br/>';
    return $next($req, $res);
  }
}