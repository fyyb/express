<?php

declare(strict_types = 1);

namespace Fyyb\Router;

use Fyyb\Support\Utils;

class LastRouter
{
    /**
     * Last Router Collection
     *
     * @var array
     */
    private $last = [];

    /**
     * Set Last Routes
     *
     * @param array $methods
     * @param String $pattern
     * @return void
     */
    public function setLast(array $methods, String $pattern)
    {
        foreach ($methods as $method) {
            $method = strtoupper($method);
            $base = '';
            $routes = [];

            if (!array_key_exists($method, $this->getLast())) {
                $this->last[$method] = [];
            };

            foreach (explode('[', str_replace(']', '', $pattern)) as $route) {
                $r = $base . $route;
                $base = $r;
                $routes[] = Utils::clearURI($r);
            };

            foreach (array_reverse($routes) as $r) {
                array_push($this->last[$method], $r);
            };
        };
    }

    /**
     * Get Last Routes
     *
     * @return Array
     */
    public function getLast(String $method = ''): array
    {
        if ($method !== '') {
            if (isset($this->last[$method])) {
                return $this->last[$method];
            } else {
                return [];
            };
        };
        return $this->last;
    }

    /**
     * Clean Last Routes
     *
     * @return void
     */
    public function cleanLast()
    {
        $this->last = [];
        return;
    }
}
