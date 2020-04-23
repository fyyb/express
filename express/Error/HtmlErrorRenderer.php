<?php

declare(strict_types=1);

namespace Fyyb\Error;

use Fyyb\Response;

class HtmlErrorRenderer
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

        $this->renderError($title, $details);
    }

    private static function renderError($title, $details = []): string
    {
        $html = '<html>' .
                '   <head>' .
                "       <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
                '       <title>'.$title.'</title>' .
                '       <style>' .
                '           body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif}' .
                '           h1{margin:0;font-size:48px;font-weight:normal;line-height:48px}' .
                '           h2{margin-bottom:0;}' .
                '           strong{display:inline-block}' .
                '           ul{list-style:none;padding:0;margin:5px 0px 10px 15px;}' .
                '       </style>' .
                '   </head>' .
                '   <body>' .
                '       <h1>'.$title.'</h1>';

        if (count($details) > 0) {
            $html .= '       <p>The application could not run because of the following error:</p>'.
                     '       <h2>Details</h2>'.
                     '       <ul>';
            foreach ($details as $key => $value) {
                $html .= '       <li>'.
                         '          <span><strong>'.$key.':</strong> '.$value.'</span>'.
                         '       </li>';
            };

            $html .= '       </ul>';

        };
                        
        $html .='       <a href="#" onClick="window.history.go(-1)">Go Back</a>' .
                '   </body>' .
                '</html>';

        $res = new Response();
        $res->send($html);
    }
}