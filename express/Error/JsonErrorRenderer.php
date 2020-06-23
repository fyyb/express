<?php

declare(strict_types = 1);

namespace Fyyb\Error;

use Fyyb\Response;

class JsonErrorRenderer
{
    /**
     * @param Int $code
     * @param String $title
     * @param Array $details
     */
    public function __construct(Int $code, String $title, array $details = [])
    {
        self::renderError($code, $title, $details);
    }

    /**
     * @param Int $code
     * @param String $title
     * @param Array $details
     * @return void
     */
    private static function renderError(Int $code, String $title, array $details): void
    {
        if (count($details) > 0) {
            $data = array('error' => $title, 'details' => $details);
        } else {
            $data = array('error' => $title);
        };

        $res = new Response();
        $res->json($data, $code);
    }
}
