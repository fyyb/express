<?php

declare (strict_types = 1);

namespace Fyyb\Support;

class JWT
{

    private $headers;
    private $claims;
    private $payload;
    private $signature;

    public function __construct(String $jwt = '')
    {

        if ($jwt !== '') {
            $t = explode('.', $jwt);
            if (count($t) === 3) {
                $this->headers = $this->jsonDecode($this->base64url_decode($t[0]));
                $this->claims = $this->jsonDecode($this->base64url_decode($t[1]));
                $this->setPayload();
                $this->signature = $t[2];
            };
        } else {
            $this->headers = [
                'typ' => 'JWT',
                'alg' => 'HS256',
            ];
            if (defined('JWT')) {
                if (!empty(JWT['iss'])) {
                    $this->claims['iss'] = JWT['iss'];
                };

                if (!empty(JWT['jti'])) {
                    $this->headers['jti'] = JWT['jti'];
                    $this->claims['jti'] = JWT['jti'];
                };
            };
        };
    }

    public function getHeaders()
    {

        return $this->headers;
    }

    public function hasHeader($name)
    {

        return array_key_exists($name, $this->headers);
    }

    public function getHeader($name)
    {
        if ($this->hasHeader($name)) {
            return $this->getHeaderValue($name);
        };
        return false;
    }

    private function getHeaderValue($name)
    {
        $header = $this->headers[$name];
        return $header;
    }

    public function getClaims()
    {
        return $this->claims;
    }

    public function hasClaim($name)
    {
        return array_key_exists($name, $this->claims);
    }

    public function getClaim($name)
    {
        if ($this->hasClaim($name)) {
            return $this->getClaimValue($name);
        };
        return false;
    }

    private function getClaimValue($name)
    {
        $claim = $this->claims[$name];
        return $claim;
    }

    public function setIssuedAt($issuedAt, $replicateAsHeader = false)
    {
        return $this->setRegisteredClaim('iat', (int) $issuedAt, $replicateAsHeader);
    }

    public function setNotBefore($notBefore, $replicateAsHeader = false)
    {
        return $this->setRegisteredClaim('nbf', (int) $notBefore, $replicateAsHeader);
    }

    public function setExpiration($expiration, $replicateAsHeader = false)
    {
        return $this->setRegisteredClaim('exp', (int) $expiration, $replicateAsHeader);
    }

    public function set($params = [])
    {
        foreach ($params as $key => $value) {
            $this->setRegisteredClaim($key, $value);
        };
        return $this;
    }

    private function setRegisteredClaim($name, $value, $replicate = false)
    {
        $this->claims[(string) $name] = $value;
        if ($replicate) {$this->headers[$name] = $this->claims[$name];};
        return $this;
    }

    public function createToken()
    {
        if (!isset($this->claims['iat']) || empty($this->claims['iat'])) {
            $this->setIssuedAt(time());
        };

        if (!isset($this->claims['nbf']) || empty($this->claims['nbf'])) {
            $this->setNotBefore(time() - 1);
        };

        if (!isset($this->claims['exp']) || empty($this->claims['exp'])) {

            if (defined("JWT")) {
                if (!empty(JWT['exp'])) {
                    $exp = JWT['exp'];
                }
            } else {
                $exp = '30 minutes';
            }
            $this->setExpiration(strtotime($exp));
        };

        $this->setPayload();
        $this->signature = $this->sign();

        $token = implode('.', $this->payload) . '.' . $this->signature;
        return $token;
    }

    private function setPayload()
    {
        $this->payload = [
            $this->base64url_encode($this->jsonEncode(($this->headers))),
            $this->base64url_encode($this->jsonEncode(($this->claims))),
        ];
        return;
    }

    private function sign()
    {
        if (defined("JWT")) {
            if (!empty(JWT['key'])) {
                $s = $this->base64url_encode(
                    hash_hmac('sha256',
                        implode('.', $this->payload),
                        JWT['key'],
                        true
                    )
                );
                return $s;
            };

            throw new \Exception("Not found the key 'key' in the JWT constant! \n JWT['key'] = undefined");
        };

        throw new \Exception("You need to define the JWT constant in your settings.php file");
    }

    public function verify()
    {
        $arr = array('stt' => false);
        $time = time();

        if (
            !isset($this->claims['nbf']) || empty($this->claims['nbf']) ||
            !isset($this->claims['exp']) || empty($this->claims['exp']) ||
            $this->signature == '' || $this->signature == null ||
            $this->signature !== $this->sign()
        ) {
            $arr['error']['cod'] = "JWT";
            $arr['error']['msg'] = "Invalid token";
            return $arr;
        };

        if ($this->getClaim('nbf') > $time) {
            $arr['error']['cod'] = "JWT";
            $arr['error']['msg'] = "Token not available ('nbf')";
            return $arr;
        };

        if ($this->getClaim('exp') < $time) {
            $arr['error']['cod'] = "JWT";
            $arr['error']['msg'] = "Expired token ('exp')";
            return $arr;
        };

        $arr['stt'] = true;
        return $arr;
    }

    private function jsonEncode($data)
    {
        return json_encode($data);
    }

    private function jsonDecode($data)
    {
        return json_decode($data, true);
    }

    private function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64url_decode($data)
    {
        return base64_decode(
            strtr($data, '-_', '+/') .
            str_repeat('=', 3 - (3 + strlen($data)) % 4)
        );
    }

    public function __toString()
    {
        echo $this->createToken();
    }
}
