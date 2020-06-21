<?php

namespace App\Controllers;

use Fyyb\Request;
use Fyyb\Response;

class TesteController
{
    public function index(Request $req, Response $res)
    {
        $response['mids'] = $req->mids ?? null;
        $response['route'] = str_replace('//', '/', '/' . $req->getURI());
        $res->json(array_filter($response));
    }

    public function conflitoRoute(Request $req, Response $res)
    {
        $response['mids'] = $req->mids ?? null;
        $response['route'] = str_replace('//', '/', '/' . $req->getURI());
        $res->json(array_filter($response));
    }
}
