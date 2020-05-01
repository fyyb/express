<?php

namespace Fyyb\Http;

class HeaderHandler
{
    protected static function setHeader($header, $value)
    {
        if (!self::hasHeader($header)) {
            header($header . ':' . $value);
        };
    }

    protected static function getHeaders()
    {
        return apache_response_headers();
    }

    protected static function getHeader($header)
    {
        $headers = self::getHeaders();
        foreach ($headers as $h => $value) {
            if ($h === $header) {
                return $value;
            };
        };
    }

    protected static function hasHeader($header)
    {
        $headers = self::getHeaders();
        foreach ($headers as $h) {
            if ($h === $header) {
                return true;
            };
        };
        return false;
    }

    protected static function appendHeader($header, $value)
    {
        if (self::hasHeader($header)) {
            $oldValue = self::getHeader($header);
            $newValue = $oldValue . ', ' . $value;
            self::withoutHeader($header);
            self::setHeader($header, $newValue);
        }
    }

    protected static function withoutHeader($header)
    {
        if (self::hasHeader($header)) {
            header_remove($header);
        };
    }
}
