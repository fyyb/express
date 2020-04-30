<?php

declare(strict_types=1);

namespace Fyyb\Support;

class Token
{
    public static function create($ncaract = '')
    {
        $final = (empty($ncaract))?128:$ncaract; 
        $hash  = hash('whirlpool', (md5(time().rand(0,999999)).time().rand(0,999999)));
        return substr($hash, 0, (($final>strlen($hash))?strlen($hash):$final));  
    }
}