<?php

declare(strict_types=1);

namespace Fyyb\Error;

use Fyyb\Request;
use Fyyb\Response;

class HtmlErrorRenderer
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
        $html = '<html>' .
            '   <head>' .
            "       <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>" .
            '       <title>' . $req->error['title'] . '</title>' .
            '       <style>' .
            '           body{margin:0;padding:30px;font:12px/1.5 Helvetica,Arial,Verdana,sans-serif}' .
            '           h1{margin:0;font-size:48px;font-weight:normal;line-height:48px}' .
            '           h2{margin-bottom:0;}' .
            '           strong{display:inline-block}' .
            '           ul{list-style:none;padding:0;margin:5px 0px 10px 15px;}' .
            '       </style>' .
            '   </head>' .
            '   <body>' .
            '       <h1>' . $req->error['title'] . '</h1>';

        if (count($req->error['details']) > 0) {
            $html .= '       <p>The application could not run because of the following error:</p>' .
                '       <h2>Details</h2>' .
                '       <ul>';
            foreach ($req->error['details'] as $key => $value) {
                $html .= '       <li><span><strong>' . $key . ':</strong> ' . $value . '</span></li>';
            };

            $html .= '       </ul>';
        };

        $html .= '       <a href="#" onClick="window.history.go(-1)">Go Back</a>' .
            '   </body>' .
            '</html>';

        $res->send($html, $req->error['code']);
    }
}