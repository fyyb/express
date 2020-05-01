<?php

declare(strict_types=1);

namespace Fyyb;

use Fyyb\Http\HttpPopulateTrait;
use Fyyb\Support\Utils;

class Request
{
    use HttpPopulateTrait;

    public function inRequest($key = '')
    {
        if (empty($key)) {
            return $this->data;
        } elseif (array_key_exists($key, $this->data)) {
            return $this->{$key};
        } else {
            return;
        };
    }

    public function getURI(): String
    {
        $uri;
        if (defined('ENVIRONMENT')) {
            if (ENVIRONMENT === 'dev') {
                if (defined('BASE_DIR')) {
                    $uri = '/'.str_replace(BASE_DIR, '', $_SERVER['REQUEST_URI']);
                };
            };
        } else {
            $uri = '/'.$_SERVER['REQUEST_URI'];            
        };

        $uri = str_replace('?'.$_SERVER['QUERY_STRING'], '', $uri);
        return Utils::clearURI($uri);
    }

    public function getMethod(): String
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getParams(): Array
    {
        return Utils::convertDataToArray($this->params);
    }

    public function getParsedBody(): Array
    {
        $data = json_decode(file_get_contents('php://input'));
        return Utils::convertDataToArray(array_merge((array) $data, $_POST));
    }

    public function getUploadedFiles(): Array
    {
        return $_FILES;
    }

    public function getQueryString(): Array
    {
        return $_GET;
    }

    public function getHeaders(): Array
    {
        return apache_request_headers();
    }

    public function getHeader($header): String
    {
        foreach ($this->getHeaders() as $h => $value) {
            if ($h === $header) {
                return $value;
            };
        };
    }

    public function hasHeader($header) : Bool
    {
        foreach ($this->getHeaders() as $h => $value) {
            if ($h === $header) {
                return true;
            };
        };
        return false;
    }
}
