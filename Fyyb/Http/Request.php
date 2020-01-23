<?php

namespace Fyyb\Http;

use Fyyb\Http\HttpPopulateTrait;

class Request
{
    use HttpPopulateTrait;

    public function inRequest($key = '')
    {
        if (empty($key)) {
            return $this->data;

        } else if (array_key_exists($key, $this->data)){
            return $this->{$key};
        
        } else {
            return '';
        };
    }
    
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getParsedBody() {
		switch ($this->getMethod()) {
            case 'GET':
                return $_GET;
				break;
			
			case 'PUT':
			case 'DELETE':
				$data = json_decode(file_get_contents('php://input'));
                return (array) $data;
                break;
                
			case 'POST':
				$data = json_decode(file_get_contents('php://input'));
				if (is_null($data)) $data = $_POST;
				return (array) $data;
				break;
		}

    }
    
    public function getURI()
    {
        return explode("?", $_SERVER['REQUEST_URI'])[0];
    }

    public function getQueryString()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $qs = '';

        if (count(explode("?", $uri)) === 2) {
            $qs = explode("?", $uri)[1];
        };
        return $qs;
    }

    public function getHeaders()
    {
        return apache_request_headers();
    }

    public function getHeader($header)
    {
        $headers = $this->getHeaders();    
        foreach ($headers as $h => $value) {
            if ($h === $header) {
                return $value;
            };
        };
    }

    public function hasHeader($header)
    {
        $headers = $this->getHeaders();
        
        foreach ($headers as $h => $value) {
            if ($h === $header) {
                return true;
            };
        };

        return false;
    }
}