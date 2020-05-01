<?php

declare (strict_types = 1);

namespace Fyyb;

use Fyyb\Http\HeaderHandler;
use Fyyb\Http\HttpPopulateTrait;

class Response extends HeaderHandler
{
    use HttpPopulateTrait;

    public function inResponse($key = '')
    {
        if (empty($key)) {
            return $this->data;
        } else if (array_key_exists($key, $this->data)) {
            return $this->{$key};
        } else {
            return;
        };
    }

    public function send($html, $statusCode = 200)
    {
        $this->setResposeWithHeaderAndStatusCode([], $statusCode);
        echo $html;
        exit;
    }

    public function json($data = array(), $statusCode = 200)
    {
        $res = $this->setResposeWithHeaderAndStatusCode($data, $statusCode);
        $this->setheader('Content-Type', 'application/json; charset=utf-8');
        echo json_encode($res[0]);
        exit;
    }

    public function redirect($url, $newHeader = true)
    {
        header('Location: ' . $url, $newHeader);
        exit;
    }

    public static function Continue_100($menssage = '')
    {
        header('HTTP/1.1 100 Continue');
        return $menssage;
    }

    public static function Switching_Protocols($menssage = '')
    {
        header('HTTP/1.1 101 Switching Protocols');
        return $menssage;
    }

    public static function OK($menssage = '')
    {
        header('HTTP/1.1 200 Ok');
        return $menssage;
    }

    public static function Created($menssage = '')
    {
        header('HTTP/1.1 201 Created');
        return $menssage;
    }

    public static function Accepted($menssage = '')
    {
        header('HTTP/1.1 202 Accepted');
        return $menssage;
    }

    public static function Non_Authoritative_Information($menssage = '')
    {
        header('HTTP/1.1 203 Non-Authoritative Information');
        return $menssage;
    }

    public static function No_Content($menssage = '')
    {
        header('HTTP/1.1 204 No Content');
        return $menssage;
    }

    public static function Reset_Content($menssage = '')
    {
        header('HTTP/1.1 205 Reset Content');
        return $menssage;
    }

    public static function Partial_Content($menssage = '')
    {
        header('HTTP/1.1 206 Partial Content');
        return $menssage;
    }

    public static function Multiple_Choices($menssage = '')
    {
        header('HTTP/1.1 300 Multiple Choices');
        return $menssage;
    }

    public static function Moved_Permanently($menssage = '')
    {
        header('HTTP/1.1 301 Moved Permanently');
        return $menssage;
    }

    public static function Found($menssage = '')
    {
        header('HTTP/1.1 302 Found');
        return $menssage;
    }

    public static function See_Other($menssage = '')
    {
        header('HTTP/1.1 303 See Other');
        return $menssage;
    }

    public static function Not_Modified($menssage = '')
    {
        header('HTTP/1.1 304 Not Modified');
        return $menssage;
    }

    public static function Use_Proxy($menssage = '')
    {
        header('HTTP/1.1 305 Use Proxy');
        return $menssage;
    }

    public static function Unused($menssage = '')
    {
        header('HTTP/1.1 306 (Unused)');
        return $menssage;
    }

    public static function Temporary_Redirect($menssage = '')
    {
        header('HTTP/1.1 307 Temporary Redirect');
        return $menssage;
    }

    public static function Bad_Request($menssage = '')
    {
        header('HTTP/1.1 400 Bad Request');
        return $menssage;
    }

    public static function Unauthorized($menssage = '')
    {
        header('HTTP/1.1 401 Unauthorized');
        return $menssage;
    }

    public static function Payment_Required($menssage = '')
    {
        header('HTTP/1.1 402 Payment Required');
        return $menssage;
    }

    public static function Forbidden($menssage = '')
    {
        header('HTTP/1.1 403 Forbidden');
        return $menssage;
    }

    public static function Not_Found($menssage = '')
    {
        header('HTTP/1.1 404 Not Found');
        return $menssage;
    }

    public static function Method_Not_Allowed($menssage = '')
    {
        header('HTTP/1.1 405 Method Not Allowed');
        return $menssage;
    }

    public static function Not_Acceptable($menssage = '')
    {
        header('HTTP/1.1 406 Not Acceptable');
        return $menssage;
    }

    public static function Proxy_Authentication_Required($menssage = '')
    {
        header('HTTP/1.1 407 Proxy Authentication Required');
        return $menssage;
    }

    public static function Request_Timeout($menssage = '')
    {
        header('HTTP/1.1 408 Request Timeout');
        return $menssage;
    }

    public static function Conflict($menssage = '')
    {
        header('HTTP/1.1 409 Conflict');
        return $menssage;
    }

    public static function Gone($menssage = '')
    {
        header('HTTP/1.1 410 Gone');
        return $menssage;
    }

    public static function Length_Required($menssage = '')
    {
        header('HTTP/1.1 411 Length Required');
        return $menssage;
    }

    public static function Precondition_Failed($menssage = '')
    {
        header('HTTP/1.1 412 Precondition Failed');
        return $menssage;
    }

    public static function Request_Entity_Too_Large($menssage = '')
    {
        header('HTTP/1.1 413 Request Entity Too Large');
        return $menssage;
    }

    public static function Request_URI_Too_Long($menssage = '')
    {
        header('HTTP/1.1 414 Request-URI Too Long');
        return $menssage;
    }

    public static function Unsupported_Media_Type($menssage = '')
    {
        header('HTTP/1.1 415 Unsupported Media Type');
        return $menssage;
    }

    public static function Requested_Range_Not_Satisfiable($menssage = '')
    {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        return $menssage;
    }

    public static function Expectation_Failed($menssage = '')
    {
        header('HTTP/1.1 417 Expectation Failed');
        return $menssage;
    }

    public static function Unprocessable_Entity($menssage = '')
    {
        header('HTTP/1.1 422 Unprocessable Entity');
        return $menssage;
    }

    public static function Internal_Server_Error($menssage = '')
    {
        header('HTTP/1.1 500 Internal Server Error');
        return $menssage;
    }

    public static function Not_Implemented($menssage = '')
    {
        header('HTTP/1.1 501 Not Implemented');
        return $menssage;
    }

    public static function Bad_Gateway($menssage = '')
    {
        header('HTTP/1.1 502 Bad Gateway');
        return $menssage;
    }

    public static function Service_Unavailable($menssage = '')
    {
        header('HTTP/1.1 503 Service Unavailable');
        return $menssage;
    }

    public static function Gateway_Timeout($menssage = '')
    {
        header('HTTP/1.1 504 Gateway Timeout');
        return $menssage;
    }

    public static function HTTP_Version_Not_Supported($menssage = '')
    {
        header('HTTP/1.1 505 HTTP Version Not Supported');
        return $menssage;
    }

    private function setResposeWithHeaderAndStatusCode(array $data, Int $status)
    {
        $arr = array();
        switch ($status) {
            case 100:
                $arr[] = $this->Continue_100($data);
                break;

            case 101:
                $arr[] = $this->Switching_Protocols($data);
                break;

            case 201:
                $arr[] = $this->Created($data);
                break;

            case 202:
                $arr[] = $this->Accepted($data);
                break;

            case 203:
                $arr[] = $this->Non_Authoritative_Information($data);
                break;

            case 204:
                $arr[] = $this->No_Content($data);
                break;

            case 205:
                $arr[] = $this->Reset_Content($data);
                break;

            case 206:
                $arr[] = $this->Partial_Content($data);
                break;

            case 300:
                $arr[] = $this->Multiple_Choices($data);
                break;

            case 301:
                $arr[] = $this->Moved_Permanently($data);
                break;

            case 302:
                $arr[] = $this->Found($data);
                break;

            case 303:
                $arr[] = $this->See_Other($data);
                break;

            case 304:
                $arr[] = $this->Not_Modified($data);
                break;

            case 305:
                $arr[] = $this->Use_Proxy($data);
                break;

            case 306:
                $arr[] = $this->Unused($data);
                break;

            case 307:
                $arr[] = $this->Temporary_Redirect($data);
                break;

            case 400:
                $arr[] = $this->Bad_Request($data);
                break;

            case 401:
                $arr[] = $this->Unauthorized($data);
                break;

            case 402:
                $arr[] = $this->Payment_Required($data);
                break;

            case 403:
                $arr[] = $this->Forbidden($data);
                break;

            case 404:
                $arr[] = $this->Not_Found($data);
                break;

            case 405:
                $arr[] = $this->Method_Not_Allowed($data);
                break;

            case 406:
                $arr[] = $this->Not_Acceptable($data);
                break;

            case 407:
                $arr[] = $this->Proxy_Authentication_Required($data);
                break;

            case 408:
                $arr[] = $this->Request_Timeout($data);
                break;

            case 409:
                $arr[] = $this->Conflict($data);
                break;

            case 410:
                $arr[] = $this->Gone($data);
                break;

            case 411:
                $arr[] = $this->Length_Required($data);
                break;

            case 412:
                $arr[] = $this->Precondition_Failed($data);
                break;

            case 413:
                $arr[] = $this->Request_Entity_Too_Large($data);
                break;

            case 414:
                $arr[] = $this->Request_URI_Too_Long($data);
                break;

            case 415:
                $arr[] = $this->Unsupported_Media_Type($data);
                break;

            case 416:
                $arr[] = $this->Requested_Range_Not_Satisfiable($data);
                break;

            case 417:
                $arr[] = $this->Expectation_Failed($data);
                break;

            case 422:
                $arr[] = $this->Unprocessable_Entity($data);
                break;

            case 500:
                $arr[] = $this->Internal_Server_Error($data);
                break;

            case 501:
                $arr[] = $this->Not_Implemented($data);
                break;

            case 502:
                $arr[] = $this->Bad_Gateway($data);
                break;

            case 503:
                $arr[] = $this->Service_Unavailable($data);
                break;

            case 504:
                $arr[] = $this->Gateway_Timeout($data);
                break;

            case 505:
                $arr[] = $this->HTTP_Version_Not_Supported($data);
                break;

            default:
                $arr[] = $this->OK($data);
        }
        return $arr;
    }

}
