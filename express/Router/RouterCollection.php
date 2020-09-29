<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Support\Utils;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class RouterCollection
{
    /**
     * Router Collection
     * route storage
     *
     * @var array
     */
    private $routerCollection = [];

    /**
     * Params Pattern In Route
     *
     * @var array
     */
    private $ParamsPatternInRoute = [];

    /**
     * Params Pattern Providers
     *
     * @var array
     */
    private $ParamsPatternProviders = [];

    /**
     * Fallback
     *
     * @var string|callable
     */
    private $fallback;

    /**
     * Returns a single instance of the class.
     *
     * @return RouterCollection
     */
    public static function getInstance(): RouterCollection
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new RouterCollection();
        }
        return $instance;
    }

    /**
     * Protected constructor method prevents a new instance of the
     * Class from being created using the `new` operator from outside that class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method prevents cloning of this class instance
     */
    private function __clone()
    {
    }

    /**
     * Private wakeup method prevent deserialization of the instance of this class.
     */
    private function __wakeup()
    {
    }

    /**
     * Set Routes In Collection
     *
     * @param array $methods
     * @param string $pattern
     * @param callable|string $callable
     * @return void
     */
    public function setRoutesInCollection(array $methods, string $pattern, $callable)
    {
        $pattern = explode('[', str_replace(']', '', $pattern));

        foreach ($methods as $method) {
            $method = strtoupper($method);
            $base = '';
            $routes = [];

            if (!array_key_exists($method, $this->routerCollection)) {
                $this->routerCollection[$method] = [];
            };

            foreach ($pattern as $route) {
                $r = $base . $route;
                $base = $r;
                $routes[] = Utils::clearURI($r);
            };

            foreach (array_reverse($routes) as $r) {
                $this->routerCollection[$method][$r] = $callable;
            };
        };

        return $this;
    }

    /**
     * Get Routes in Collection
     * returns routes registered on the map
     *
     * @return array|null
     */
    public function getRoutesInCollection(string $method = ''): ?array
    {
        if ($method !== '') {
            if (isset($this->routerCollection[$method])) {
                return $this->routerCollection[$method];
            } else {
                return null;
            };
        };
        return $this->routerCollection;
    }

    /**
     * Set Params Pattern In Route
     *
     * @param string $method
     * @param string $route
     * @param string $key
     * @param string $pattern
     * @return void
     */
    public function setParamsPatternInRoute(string $method, string $route, string $key, string $pattern)
    {
        $this->ParamsPatternInRoute[$method][$route][$key] = $pattern;
        return;
    }

    /**
     * Get Params Pattern In Route
     *
     * @param string $method
     * @return array|null
     */
    public function getParamsPatternInRoute(string $method = ''): ?array
    {
        if ($method !== '') {
            if (isset($this->ParamsPatternInRoute[$method])) {
                return $this->ParamsPatternInRoute[$method];
            } else {
                return null;
            };
        };
        return $this->ParamsPatternInRoute;
    }

    /**
     * Set Fallback
     *
     * @param callable|string $callable
     * @return void
     */
    public function setFallback($callable)
    {
        $this->fallback = $callable;
        return;
    }

    /**
     * Get Fallback
     *
     * @return callable|string
     */
    public function getFallback()
    {
        return $this->fallback;
    }
}