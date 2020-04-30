<?php

declare(strict_types=1);

namespace Fyyb\Support;

class Cripto
{
    public static function hash($pwd)
    {
        $strongPWD  = defined("STRONG_PWD") ? STRONG_PWD : '';
        $cripto     = hash('whirlpool', 
                      hash('sha512', 
                      hash('sha384', 
                      hash('sha256', 
                      sha1(md5($strongPWD.$pwd))))));
                      	
		$cripto     = $cripto.password_hash($cripto, PASSWORD_BCRYPT);
		return $cripto;		
	}
	
	public static function verify($pwd, $pwdBD)
    {
		$pwdHash = Cripto::hash($pwd);
		$p1 	 = substr($pwdHash, 0, 128);
		$p2 	 = substr($pwdHash, 128);
		$pBD1 	 = substr($pwdBD, 0, 128);
		$pBD2 	 = substr($pwdBD, 128);

		return ($p1 === $pBD1 && password_verify($p1, $pBD2) === true);
	}
}