<?php

declare(strict_types=1);

namespace Fyyb\Support;

class Valid
{
    public static function verifyRequiredFields($sent, Array $required = array())
    {
        $notSent = array();
        
        foreach ($required as $req) {
            if (!in_array($req, array_keys($sent)) || empty($sent[$req])) {
                $notSent[] = $req;
            };
        };
        
        if (count($notSent) > 0) {
            return [
                'error' => [
                    'cod' => 'required-fields',
                    'msg' => 'required fields ('.implode(', ', $notSent).') not sent'
                ]
            ];
        };

        return true;
    }
}