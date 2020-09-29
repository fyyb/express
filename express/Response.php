<?php

declare(strict_types=1);

namespace Fyyb;

use Fyyb\Http\HttpPopulateTrait;
use Fyyb\Http\HttpRequestResponseMethods;
use Fyyb\Http\HttpResponseCode;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class Response extends HttpRequestResponseMethods
{
    use HttpPopulateTrait;

    public function inResponse(String $key = '')
    {
        if (empty($key)) {
            return $this->data;
        } elseif (array_key_exists($key, $this->data)) {
            return $this->{$key};
        } else {
            return;
        };
    }

    /**
     * Return with HTML
     * performs the return of information by html
     *
     * @param String $html
     * @param Int $statusCode
     * @return String HTML
     */
    public function send(String $html, Int $statusCode = 200): String
    {
        HttpResponseCode::setResposeWithHeaderAndStatusCode([], $statusCode);
        echo $html;
        exit;
    }

    /**
     * Return with JSON
     * performs the return of information by json
     *
     * @param Array $data
     * @param Int $statusCode
     * @return String JSON
     */
    public function json(array $data = array(), Int $statusCode = 200): string
    {
        $res = HttpResponseCode::setResposeWithHeaderAndStatusCode($data, $statusCode);
        self::setheader('Content-Type', 'application/json; charset=utf-8');
        echo json_encode($res[0]);
        exit;
    }

    /**
     * Redirect
     * performs redirection and can keep the initial headers or not
     *
     * @param String $url
     * @param Bool $newHeader
     * @return void
     */
    public function redirect(String $url, Bool $newHeader = true): void
    {
        header('Location: ' . $url, $newHeader);
        exit;
    }

    /**
     * Get Header
     * returns the list of headers
     *
     * @return array
     */
    public static function getHeaders(): array
    {
        return apache_response_headers();
    }
}