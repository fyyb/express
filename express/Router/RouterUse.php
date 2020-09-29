<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Router;
use Fyyb\Router\RouterCollection;
use Fyyb\Router\RouterDefault;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class RouterUse extends RouterDefault
{

    /**
     * route prefix
     *
     * @var string
     */
    private $use;

    /**
     * instance of Router
     *
     * @var Router
     */
    private $router;

    /**
     * instance of RouterCollection
     *
     * @var RouterCollection
     */
    private $routerCollection;

    /**
     * Class Constructor
     *
     * @param string $pattern
     * @param string $file
     */
    public function __construct(string $pattern, string $file)
    {
        $this->use = $pattern;
        $this->router = Router::getInstance();
        $this->routerCollection = RouterCollection::getInstance();

        require_once $file;
        return $this;
    }

    /**
     * MAP
     *
     * @param array $method
     * @param string $pattern
     * @param string|callable $callable
     * @return mixed
     */
    public function map(array $method, string $pattern, $callable)
    {
        $this->router->map($method, $this->use . $pattern, $callable);
        return $this;
    }

    /**
     * Group
     *
     * @param string $pattern
     * @param string|callable $callback
     * @return mixed
     */
    public function group(string $pattern, $callback)
    {
        $this->router->group($this->use . $pattern, $callback);
        return $this;
    }

    /**
     * Add
     *
     * @param array ...$mids
     * @return void
     */
    public function add(...$mids)
    {
        $this->router->add(...$mids);
    }

    /**
     * Where
     * set params pattern
     *
     * @param array $arr
     * @return void
     */
    public function where(array $arr)
    {
        foreach ($this->last->getLast() as $method => $routes) {
            foreach ($routes as $route) {
                foreach ($arr as $key => $pattern) {
                    if (strstr($route, ':' . $key)) {
                        $this->routerCollection->setParamsPatternInRoute($method, $route, $key, $pattern);
                    };
                };
            };
        };
    }
}