<?php

namespace Fyyb\Http;

class HeaderHandler
{
    public static function setHeader($header, $value)
    {
        if (!self::hasHeader($header)) {
            header($header . ':' . $value);
        };
    }

    public static function getHeaders()
    {
        return apache_response_headers();
    }

    public static function getHeader($header)
    {
        $headers = self::getHeaders();
        foreach ($headers as $h => $value) {
            if ($h === $header) {
                return $value;
            };
        };
    }

    public static function hasHeader($header)
    {
        $headers = self::getHeaders();
        foreach ($headers as $h) {
            if ($h === $header) {
                return true;
            };
        };
        return false;
    }

    public static function appendHeader($header, $value)
    {
        if (self::hasHeader($header)) {
            $oldValue = self::getHeader($header);
            $newValue = $oldValue . ', ' . $value;
            self::withoutHeader($header);
            self::setHeader($header, $newValue);
        }
    }

    public static function withoutHeader($header)
    {
        if (self::hasHeader($header)) {
            header_remove($header);
        };
    }
}
