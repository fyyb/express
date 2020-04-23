<?php

declare(strict_types=1);

namespace Fyyb\Error;

use Fyyb\Response;

class JsonErrorRenderer
{

    public function __construct(Int $code, Array $details = [])
    {
        $title;
        switch($code)
        {
            case '501':
                $title = 'Not Implemented';
                break;
            case '404':
                $title = 'Not Found';
                break;
        };

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