<?php

namespace App\Middlewares;

use Fyyb\Request;
use Fyyb\Response;

class TesteMiddleware
{

    public function route1(Request $req, Response $res, $next)
    {
        $route = str_replace('//', '/', '/' . $req->getURI());
        $mids = $req->mids ?? [];
        $mids[] = 'Mid 1 - Route: ' . $route;
        $req->mids = $mids;
        return $next($req, $res);
    }

    public function route2(Request $req, Response $res, $next)
    {
        $route = str_replace('//', '/', '/' . $req->getURI());
        $mids = $req->mids ?? [];
        $mids[] = 'Mid 2 - Route: ' . $route;
        $req->mids = $mids;
        return $next($req, $res);
    }

    public function group1(Request $req, Response $res, $next)
    {
        $route = str_replace('//', '/', '/' . $req->getURI());
        $mids = $req->mids ?? [];
        $mids[] = 'Mid 1 - Group: ' . $route;
        $req->mids = $mids;
        return $next($req, $res);
    }

    public function group2(Request $req, Response $res, $next)
    {
        $route = str_replace('//', '/', '/' . $req->getURI());
        $mids = $req->mids ?? [];
        $mids[] = 'Mid 2 - Group: ' . $route;
        $req->mids = $mids;
        return $next($req, $res);
    }

    public function subgroup1(Request $req, Response $res, $next)
    {
        $route = str_replace('//', '/', '/' . $req->getURI());
        $mids = $req->mids ?? [];
        $mids[] = 'Mid 1 - Subgroup: ' . $route;
        $req->mids = $mids;
        return $next($req, $res);}

    public function subgroup2(Request $req, Response $res, $next)
    {
        $route = str_replace('//', '/', '/' . $req->getURI());
        $mids = $req->mids ?? [];
        $mids[] = 'Mid 2 - Subgroup: ' . $route;
        $req->mids = $mids;
        return $next($req, $res);
    }
}
