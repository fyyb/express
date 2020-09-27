<?php

declare(strict_types=1);

namespace Fyyb\Error;

use Fyyb\Request;
use Fyyb\Response;

class JsonErrorRenderer
{
    /**
     * @param Request $req
     * @param Response $res
     */
    public function __construct(Request $req, Response $res)
    {
        self::renderError($req, $res);
    }

    /**
     * @param Int $code
     * @param String $title
     * @param Array $details
     * @return void
     */
    private static function renderError($req, $res): void
    {
        if (count($req->error['details']) > 0) {
            $data = array('error' => $req->error['title'], 'details' => $req->error['details']);
        } else {
            $data = array('error' => $req->error['title']);
        };

        $res = new Response();
        $res->json($data, $req->error['code']);
    }
}