<?php

declare(strict_types=1);

namespace Fyyb\Error;

use Fyyb\Response;

class JsonErrorRenderer
{

    public function __construct(Int $code, String $title, Array $details = [])
    {
        $this->renderError($code, $title, $details);
    }

    private static function renderError($code, $title, $details = []): string
    {
      
      if(count($details) > 0) {
        $data = array('error' => $title, 'details' => $details);
      } else {
        $data = array('error' => $title);
      };
      
      $res = new Response();
      $res->json($data, $code);
    }
}