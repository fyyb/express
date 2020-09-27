<?php

declare(strict_types=1);

namespace Fyyb\Support;

class Cripto
{
    /**
     * @param String $pwd
     * @return String
     */
    public static function hash(String $pwd): String
    {
        $strongPWD  = defined("STRONG_PWD") ? STRONG_PWD : '';
        $cripto     = hash(
            'whirlpool',
            hash(
                'sha512',
                hash(
                    'sha384',
                    hash(
                        'sha256',
                        sha1(md5($strongPWD . $pwd))
                    )
                )
            )
        );

        $cripto     = $cripto . password_hash($cripto, PASSWORD_BCRYPT);
        return $cripto;
    }

    /**
     * @param String $pwd
     * @param String $pwdDB
     * @return Bool
     */
    public static function verify(String $pwd, String $pwdDB): Bool
    {
        $pwdHash = Cripto::hash($pwd);
        $p1      = substr($pwdHash, 0, 128);
        $p2      = substr($pwdHash, 128);
        $pBD1    = substr($pwdDB, 0, 128);
        $pBD2    = substr($pwdDB, 128);

        return ($p1 === $pBD1 && password_verify($p1, $pBD2) === true);
    }
}