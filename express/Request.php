<?php

declare(strict_types = 1);

namespace Fyyb;

use Fyyb\Http\HttpPopulateTrait;
use Fyyb\Http\HttpRequestResponseMethods;
use Fyyb\Support\Utils;

class Request extends HttpRequestResponseMethods
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

    /**
     * Get URI
     * return request URI
     *
     * @return String
     */
    public function getURI(): String
    {
        if (defined('BASE_DIR')) {
            $uri = '/' . str_replace(BASE_DIR, '', $_SERVER['REQUEST_URI']);
        };

        $uri = str_replace('?' . $_SERVER['QUERY_STRING'], '', $uri);
        return Utils::clearURI($uri);
    }

    /**
     * Get Method
     * return request HTTP Method
     *
     * @return String
     */
    public function getMethod(): String
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Get Params
     * returns parameters passed in the request URL
     *
     * @param String $filter
     * @param mixed $default
     * @return mixed
     */
    public function getParams(String $filter = '', $default = null)
    {
        $data = Utils::convertDataToArray($this->params);
        return self::returnData($data, $filter, $default);
    }

    /**
     * Get Parsed Body
     * returns parameters passed in the request body
     *
     * @param String $filter
     * @param mixed $default
     * @return mixed
     */
    public function getParsedBody(String $filter = '', $default = null)
    {
        $data = json_decode(file_get_contents('php://input'));
        $data = Utils::convertDataToArray(array_merge((array) $data, $_POST));
        return self::returnData($data, $filter, $default);
    }

    /**
     * Get Query String
     * returns parameters passed by query string
     *
     * @param String $filter
     * @param mixed $default
     * @return mixed
     */
    public function getQueryString(String $filter = '', $default = null)
    {
        return self::returnData($_GET, $filter, $default);
    }

    /**
     * Get Uploaded Files
     * equivalent to $ _FILES
     *
     * @return array
     */
    public function getUploadedFiles(): array
    {
        return $_FILES;
    }

    /**
     * Return Data
     * auxiliary function that handles the return of filtered or unfiltered data
     *
     * @param array $data
     * @param String $filter
     * @param mixed $default
     * @return mixed
     */
    private static function returnData(array $data, String $filter, $default = null)
    {
        if (!empty($filter)) {
            if (isset($data[$filter])) {
                return $data[$filter];
            } elseif (!empty($default)) {
                return $default;
            } else {
                return null;
            }
        }
        return $data;
    }

    /**
     * Get Header
     * returns the list of headers
     *
     * @return array
     */
    public static function getHeaders(): array
    {
        return apache_request_headers();
    }
}
