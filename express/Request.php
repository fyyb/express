<?php

declare (strict_types = 1);

namespace Fyyb;

use Fyyb\Http\HeaderHandler;
use Fyyb\Http\HttpPopulateTrait;
use Fyyb\Support\Utils;

class Request extends HeaderHandler
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
        if (defined('ENVIRONMENT')) {
            if (ENVIRONMENT === 'dev') {
                if (defined('BASE_DIR')) {
                    $uri = '/' . str_replace(BASE_DIR, '', $_SERVER['REQUEST_URI']);
                };
            };
        } else {
            $uri = '/' . $_SERVER['REQUEST_URI'];
        };

        $uri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $uri);
        return Utils::clearURI($uri);
    }

    public function getMethod(): String
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getParams(): array
    {
        return Utils::convertDataToArray($this->params);
    }

    public function getParsedBody(): array
    {
        $data = json_decode(file_get_contents('php://input'));
        return Utils::convertDataToArray(array_merge((array) $data, $_POST));
    }

    public function getUploadedFiles(): array
    {
        return $_FILES;
    }

    public function getQueryString(): array
    {
        return $_GET;
    }
}
