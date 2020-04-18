<?php

namespace Fyyb;

use Fyyb\Http\HttpPopulateTrait;

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
                    $uri = str_replace('//', '/', $uri);
                };
            };
        } else {
            $uri = '/'.$_SERVER['REQUEST_URI'];
            $uri = str_replace('//', '/', $uri);
        };
        return $uri;
    }

    public function getMethod(): String
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getParsedBody(): Array
    {
        switch ($this->getMethod()) {
            case 'GET':
                return (array) $_GET;
                break;
            case 'PUT':
            case 'DELETE':
                $data = json_decode(file_get_contents('php://input'));
                return (array) $data;
                break;
            case 'POST':
                $data = json_decode(file_get_contents('php://input'));
                if (is_null($data)) {
                    $data = $_POST;
                };
                return (array) $data;
                break;
        };
    }

    public function getQueryString(): Array
    {
        $qs = explode('&', $_SERVER['QUERY_STRING']);
        array_shift($qs);
        return $qs;
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

    public function hasHeader($header): Boolean
    {
        foreach ($this->getHeaders() as $h => $value) {
            if ($h === $header) {
                return true;
            };
        };
        return false;
    }
}
