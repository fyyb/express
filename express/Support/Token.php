<?php

declare(strict_types=1);

namespace Fyyb\Support;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class Token
{
    /**
     * Returns a token of up to 128 characters
     *
     * @param Int $ncaract
     * @return String
     */
    public static function create(Int $ncaract = 0): String
    {
        $final = ($ncaract <= 0) ? 128 : $ncaract;
        $hash  = hash('whirlpool', (md5(time() . rand(0, 999999)) . time() . rand(0, 999999)));
        return substr($hash, 0, (($final > strlen($hash)) ? strlen($hash) : $final));
    }
}