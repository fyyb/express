<?php

declare(strict_types=1);

namespace Fyyb\Http;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class HttpResponseCode
{
    /**
     * Set Header - 100
     *
     * @param string $menssage
     * @return
     */
    public static function Continue_100($menssage = '')
    {
        header('HTTP/1.1 100 Continue');
        return $menssage;
    }

    /**
     * Set Header - 101
     *
     * @param string $menssage
     * @return
     */
    public static function Switching_Protocols($menssage = '')
    {
        header('HTTP/1.1 101 Switching Protocols');
        return $menssage;
    }

    /**
     * Set Header - 200
     *
     * @param string $menssage
     * @return
     */
    public static function OK($menssage = '')
    {
        header('HTTP/1.1 200 Ok');
        return $menssage;
    }

    /**
     * Set Header - 201
     *
     * @param string $menssage
     * @return
     */
    public static function Created($menssage = '')
    {
        header('HTTP/1.1 201 Created');
        return $menssage;
    }

    /**
     * Set Header - 202
     *
     * @param string $menssage
     * @return
     */
    public static function Accepted($menssage = '')
    {
        header('HTTP/1.1 202 Accepted');
        return $menssage;
    }

    /**
     * Set Header - 203
     *
     * @param string $menssage
     * @return
     */
    public static function Non_Authoritative_Information($menssage = '')
    {
        header('HTTP/1.1 203 Non-Authoritative Information');
        return $menssage;
    }

    /**
     * Set Header - 204
     *
     * @param string $menssage
     * @return
     */
    public static function No_Content($menssage = '')
    {
        header('HTTP/1.1 204 No Content');
        return $menssage;
    }

    /**
     * Set Header - 205
     *
     * @param string $menssage
     * @return
     */
    public static function Reset_Content($menssage = '')
    {
        header('HTTP/1.1 205 Reset Content');
        return $menssage;
    }

    /**
     * Set Header - 206
     *
     * @param string $menssage
     * @return
     */
    public static function Partial_Content($menssage = '')
    {
        header('HTTP/1.1 206 Partial Content');
        return $menssage;
    }

    /**
     * Set Header - 300
     *
     * @param string $menssage
     * @return
     */
    public static function Multiple_Choices($menssage = '')
    {
        header('HTTP/1.1 300 Multiple Choices');
        return $menssage;
    }

    /**
     * Set Header - 301
     *
     * @param string $menssage
     * @return
     */
    public static function Moved_Permanently($menssage = '')
    {
        header('HTTP/1.1 301 Moved Permanently');
        return $menssage;
    }

    /**
     * Set Header - 302
     *
     * @param string $menssage
     * @return
     */
    public static function Found($menssage = '')
    {
        header('HTTP/1.1 302 Found');
        return $menssage;
    }

    /**
     * Set Header - 303
     *
     * @param string $menssage
     * @return
     */
    public static function See_Other($menssage = '')
    {
        header('HTTP/1.1 303 See Other');
        return $menssage;
    }

    /**
     * Set Header - 304
     *
     * @param string $menssage
     * @return
     */
    public static function Not_Modified($menssage = '')
    {
        header('HTTP/1.1 304 Not Modified');
        return $menssage;
    }

    /**
     * Set Header - 305
     *
     * @param string $menssage
     * @return
     */
    public static function Use_Proxy($menssage = '')
    {
        header('HTTP/1.1 305 Use Proxy');
        return $menssage;
    }

    /**
     * Set Header - 306
     *
     * @param string $menssage
     * @return
     */
    public static function Unused($menssage = '')
    {
        header('HTTP/1.1 306 (Unused)');
        return $menssage;
    }

    /**
     * Set Header - 307
     *
     * @param string $menssage
     * @return
     */
    public static function Temporary_Redirect($menssage = '')
    {
        header('HTTP/1.1 307 Temporary Redirect');
        return $menssage;
    }

    /**
     * Set Header - 400
     *
     * @param string $menssage
     * @return
     */
    public static function Bad_Request($menssage = '')
    {
        header('HTTP/1.1 400 Bad Request');
        return $menssage;
    }

    /**
     * Set Header - 401
     *
     * @param string $menssage
     * @return
     */
    public static function Unauthorized($menssage = '')
    {
        header('HTTP/1.1 401 Unauthorized');
        return $menssage;
    }

    /**
     * Set Header - 402
     *
     * @param string $menssage
     * @return
     */
    public static function Payment_Required($menssage = '')
    {
        header('HTTP/1.1 402 Payment Required');
        return $menssage;
    }

    /**
     * Set Header - 403
     *
     * @param string $menssage
     * @return
     */
    public static function Forbidden($menssage = '')
    {
        header('HTTP/1.1 403 Forbidden');
        return $menssage;
    }

    /**
     * Set Header - 404
     *
     * @param string $menssage
     * @return
     */
    public static function Not_Found($menssage = '')
    {
        header('HTTP/1.1 404 Not Found');
        return $menssage;
    }

    /**
     * Set Header - 405
     *
     * @param string $menssage
     * @return
     */
    public static function Method_Not_Allowed($menssage = '')
    {
        header('HTTP/1.1 405 Method Not Allowed');
        return $menssage;
    }

    /**
     * Set Header - 406
     *
     * @param string $menssage
     * @return
     */
    public static function Not_Acceptable($menssage = '')
    {
        header('HTTP/1.1 406 Not Acceptable');
        return $menssage;
    }

    /**
     * Set Header - 407
     *
     * @param string $menssage
     * @return
     */
    public static function Proxy_Authentication_Required($menssage = '')
    {
        header('HTTP/1.1 407 Proxy Authentication Required');
        return $menssage;
    }

    /**
     * Set Header - 408
     *
     * @param string $menssage
     * @return
     */
    public static function Request_Timeout($menssage = '')
    {
        header('HTTP/1.1 408 Request Timeout');
        return $menssage;
    }

    /**
     * Set Header - 409
     *
     * @param string $menssage
     * @return
     */
    public static function Conflict($menssage = '')
    {
        header('HTTP/1.1 409 Conflict');
        return $menssage;
    }

    /**
     * Set Header - 410
     *
     * @param string $menssage
     * @return
     */
    public static function Gone($menssage = '')
    {
        header('HTTP/1.1 410 Gone');
        return $menssage;
    }

    /**
     * Set Header - 411
     *
     * @param string $menssage
     * @return
     */
    public static function Length_Required($menssage = '')
    {
        header('HTTP/1.1 411 Length Required');
        return $menssage;
    }

    /**
     * Set Header - 412
     *
     * @param string $menssage
     * @return
     */
    public static function Precondition_Failed($menssage = '')
    {
        header('HTTP/1.1 412 Precondition Failed');
        return $menssage;
    }

    /**
     * Set Header - 413
     *
     * @param string $menssage
     * @return
     */
    public static function Request_Entity_Too_Large($menssage = '')
    {
        header('HTTP/1.1 413 Request Entity Too Large');
        return $menssage;
    }

    /**
     * Set Header - 414
     *
     * @param string $menssage
     * @return
     */
    public static function Request_URI_Too_Long($menssage = '')
    {
        header('HTTP/1.1 414 Request-URI Too Long');
        return $menssage;
    }

    /**
     * Set Header - 415
     *
     * @param string $menssage
     * @return
     */
    public static function Unsupported_Media_Type($menssage = '')
    {
        header('HTTP/1.1 415 Unsupported Media Type');
        return $menssage;
    }

    /**
     * Set Header - 416
     *
     * @param string $menssage
     * @return
     */
    public static function Requested_Range_Not_Satisfiable($menssage = '')
    {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        return $menssage;
    }

    /**
     * Set Header - 417
     *
     * @param string $menssage
     * @return
     */
    public static function Expectation_Failed($menssage = '')
    {
        header('HTTP/1.1 417 Expectation Failed');
        return $menssage;
    }

    /**
     * Set Header - 422
     *
     * @param string $menssage
     * @return
     */
    public static function Unprocessable_Entity($menssage = '')
    {
        header('HTTP/1.1 422 Unprocessable Entity');
        return $menssage;
    }

    /**
     * Set Header - 500
     *
     * @param string $menssage
     * @return
     */
    public static function Internal_Server_Error($menssage = '')
    {
        header('HTTP/1.1 500 Internal Server Error');
        return $menssage;
    }

    /**
     * Set Header - 501
     *
     * @param string $menssage
     * @return
     */
    public static function Not_Implemented($menssage = '')
    {
        header('HTTP/1.1 501 Not Implemented');
        return $menssage;
    }

    /**
     * Set Header - 502
     *
     * @param string $menssage
     * @return
     */
    public static function Bad_Gateway($menssage = '')
    {
        header('HTTP/1.1 502 Bad Gateway');
        return $menssage;
    }

    /**
     * Set Header - 503
     *
     * @param string $menssage
     * @return
     */
    public static function Service_Unavailable($menssage = '')
    {
        header('HTTP/1.1 503 Service Unavailable');
        return $menssage;
    }

    /**
     * Set Header - 504
     *
     * @param string $menssage
     * @return
     */
    public static function Gateway_Timeout($menssage = '')
    {
        header('HTTP/1.1 504 Gateway Timeout');
        return $menssage;
    }

    /**
     * Set Header - 505
     *
     * @param string $menssage
     * @return
     */
    public static function HTTP_Version_Not_Supported($menssage = '')
    {
        header('HTTP/1.1 505 HTTP Version Not Supported');
        return $menssage;
    }

    /**
     * Set Respose With Header And Status Code
     *
     * @param Array $data
     * @param Int $status
     * @return Array
     */
    public static function setResposeWithHeaderAndStatusCode(array $data, Int $status): array
    {
        $arr = array();
        switch ($status) {
            case 100:
                $arr[] = self::Continue_100($data);
                break;

            case 101:
                $arr[] = self::Switching_Protocols($data);
                break;

            case 201:
                $arr[] = self::Created($data);
                break;

            case 202:
                $arr[] = self::Accepted($data);
                break;

            case 203:
                $arr[] = self::Non_Authoritative_Information($data);
                break;

            case 204:
                $arr[] = self::No_Content($data);
                break;

            case 205:
                $arr[] = self::Reset_Content($data);
                break;

            case 206:
                $arr[] = self::Partial_Content($data);
                break;

            case 300:
                $arr[] = self::Multiple_Choices($data);
                break;

            case 301:
                $arr[] = self::Moved_Permanently($data);
                break;

            case 302:
                $arr[] = self::Found($data);
                break;

            case 303:
                $arr[] = self::See_Other($data);
                break;

            case 304:
                $arr[] = self::Not_Modified($data);
                break;

            case 305:
                $arr[] = self::Use_Proxy($data);
                break;

            case 306:
                $arr[] = self::Unused($data);
                break;

            case 307:
                $arr[] = self::Temporary_Redirect($data);
                break;

            case 400:
                $arr[] = self::Bad_Request($data);
                break;

            case 401:
                $arr[] = self::Unauthorized($data);
                break;

            case 402:
                $arr[] = self::Payment_Required($data);
                break;

            case 403:
                $arr[] = self::Forbidden($data);
                break;

            case 404:
                $arr[] = self::Not_Found($data);
                break;

            case 405:
                $arr[] = self::Method_Not_Allowed($data);
                break;

            case 406:
                $arr[] = self::Not_Acceptable($data);
                break;

            case 407:
                $arr[] = self::Proxy_Authentication_Required($data);
                break;

            case 408:
                $arr[] = self::Request_Timeout($data);
                break;

            case 409:
                $arr[] = self::Conflict($data);
                break;

            case 410:
                $arr[] = self::Gone($data);
                break;

            case 411:
                $arr[] = self::Length_Required($data);
                break;

            case 412:
                $arr[] = self::Precondition_Failed($data);
                break;

            case 413:
                $arr[] = self::Request_Entity_Too_Large($data);
                break;

            case 414:
                $arr[] = self::Request_URI_Too_Long($data);
                break;

            case 415:
                $arr[] = self::Unsupported_Media_Type($data);
                break;

            case 416:
                $arr[] = self::Requested_Range_Not_Satisfiable($data);
                break;

            case 417:
                $arr[] = self::Expectation_Failed($data);
                break;

            case 422:
                $arr[] = self::Unprocessable_Entity($data);
                break;

            case 500:
                $arr[] = self::Internal_Server_Error($data);
                break;

            case 501:
                $arr[] = self::Not_Implemented($data);
                break;

            case 502:
                $arr[] = self::Bad_Gateway($data);
                break;

            case 503:
                $arr[] = self::Service_Unavailable($data);
                break;

            case 504:
                $arr[] = self::Gateway_Timeout($data);
                break;

            case 505:
                $arr[] = self::HTTP_Version_Not_Supported($data);
                break;

            default:
                $arr[] = self::OK($data);
        }
        return $arr;
    }
}