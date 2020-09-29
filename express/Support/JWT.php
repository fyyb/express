<?php

declare(strict_types=1);

namespace Fyyb\Support;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class JWT
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $claims;

    /**
     * @var array
     */
    private $payload;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $error;

    /**
     * @var array
     */
    private $config;

    public function __construct(array $config = [])
    {
        $this->headers = ['typ' => 'JWT', 'alg' => 'HS256'];

        if (count($config) > 0) {
            $this->config = $config;
            if (isset($config['iss']) && !empty($config['iss'])) {
                $this->claims['iss'] = $config['iss'];
            };

            if (isset($config['jti']) && !empty($config['jti'])) {
                $this->headers['jti'] = $config['jti'];
                $this->claims['jti'] = $config['jti'];
            };
        };
    }

    /**
     * Get headers
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Check if a header exists
     *
     * @param String $name
     * @return Bool
     */
    public function hasHeader(String $name): Bool
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * Get header
     *
     * @param String $name
     * @return mixed
     */
    public function getHeader(String $name)
    {
        if ($this->hasHeader($name)) {
            return $this->getHeaderValue($name);
        };
        return false;
    }

    /**
     * Get header Value
     *
     * @param String $name
     * @return mixed
     */
    private function getHeaderValue(String $name)
    {
        $header = $this->headers[$name];
        return $header;
    }

    /**
     * Get claims
     *
     * @return array
     */
    public function getClaims(): array
    {
        return $this->claims;
    }

    /**
     * Check if a claims exists
     *
     * @param String $name
     * @return Bool
     */
    public function hasClaim(String $name): Bool
    {
        return array_key_exists($name, $this->claims);
    }

    /**
     * Get claim
     *
     * @param String $name
     * @return mixed
     */
    public function getClaim(String $name)
    {
        if ($this->hasClaim($name)) {
            return $this->getClaimValue($name);
        };
        return false;
    }

    /**
     * Get claim value
     *
     * @param String $name
     * @return mixed
     */
    private function getClaimValue(String $name)
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
        if ($replicate) {
            $this->headers[$name] = $this->claims[$name];
        };
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
                if (isset($this->config['exp']) && !empty($this->config['exp'])) {
                    $exp = $this->config['exp'];
                }
            } else {
                $exp = '+1 hour';
            }
            $this->setExpiration(strtotime($exp));
        };

        $this->setPayload();
        $this->signature = $this->sign();

        $this->token = implode('.', $this->payload) . '.' . $this->signature;
        return $this;
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
            if (isset($this->config['key']) && !empty($this->config['key'])) {
                $s = $this->base64url_encode(
                    hash_hmac(
                        'sha256',
                        implode('.', $this->payload),
                        $this->config['key'],
                        true
                    )
                );
                return $s;
            };

            throw new \Exception("Not found the key 'key' in the JWT constant! \n JWT['key'] = undefined");
        };

        throw new \Exception("You need to define the JWT constant in your config.php file");
    }

    public function verify(String $jwt)
    {
        $time = time();

        $t = explode('.', $jwt);
        if (count($t) === 3) {
            $this->headers = $this->jsonDecode($this->base64url_decode($t[0]));
            $this->claims = $this->jsonDecode($this->base64url_decode($t[1]));
            $this->setPayload();
            $this->signature = $t[2];
            $this->token = $jwt;
        } else {
            $arr['error']['cod'] = "JWT";
            $arr['error']['msg'] = "Invalid token format";
            $this->error = $arr;
            return false;
        };

        if (
            !isset($this->claims['nbf']) || empty($this->claims['nbf']) ||
            !isset($this->claims['exp']) || empty($this->claims['exp']) ||
            $this->signature == '' || $this->signature == null ||
            $this->signature !== $this->sign()
        ) {
            $arr['error']['cod'] = "JWT";
            $arr['error']['msg'] = "Invalid token";
            $this->error = $arr;
            return false;
        };

        if ($this->getClaim('nbf') > $time) {
            $arr['error']['cod'] = "JWT";
            $arr['error']['msg'] = "Token not available";
            $this->error = $arr;
            return false;
        };

        if ($this->getClaim('exp') < $time) {
            $arr['error']['cod'] = "JWT";
            $arr['error']['msg'] = "Expired token";
            $this->error = $arr;
            return false;
        };

        return true;
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

    public function getToken()
    {
        return $this->token;
    }

    public function getError()
    {
        return $this->error;
    }
}