<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Router;
use Fyyb\Router\LastRouter;
use Fyyb\Router\RouterCollection;
use Fyyb\Router\RouterDefault;
use Fyyb\Router\RouterSubgroup;
use Fyyb\Support\Utils;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class RouterGroup extends RouterDefault
{
    /**
     * MAP
     *
     * @var array
     */
    private $map = [];

    /**
     * instece of LastRouter
     *
     * @var LastRouter
     */
    private $last;

    /**
     * route prefix
     *
     * @var string
     */
    private $group;

    /**
     * instance of Router
     *
     * @var Router
     */
    private $router;

    /**
     * isntance of Middlewares
     *
     * @var Middlewares
     */
    private $middlewares;

    /**
     * instance of RouterCollection
     *
     * @var RouterCollection
     */
    private $routerCollection;

    /**
     * Is Subgroup
     *
     * @var boolean
     */
    private $isSubGroup = false;

    /**
     * Class Constructor
     *
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        $this->last = new LastRouter();
        $this->group = $pattern;
        $this->router = Router::getInstance();
        $this->middlewares = MiddlewareHandler::getInstance();
        $this->routerCollection = RouterCollection::getInstance();

        $this->router->activateGroup();

        return $this;
    }

    /**
     * MAP
     *
     * @param array $methods
     * @param string $pattern
     * @param string|callable $callable
     * @param boolean $sub
     * @return mixed
     */
    public function map(array $methods, string $pattern, $callable, bool $sub = false)
    {
        $prefix = ($sub) ? '' : $this->group;

        // Set Routes in Collection
        $this->router->map($methods, $prefix . $pattern, $callable);

        if (!$this->isSubGroup) {
            $this->last->cleanLast();
        };

        // Set Last Routes
        $this->last->setLast($methods, $prefix . $pattern);

        foreach ($methods as $method) {
            $method = strtoupper($method);

            if (!array_key_exists($method, $this->map)) {
                $this->map[$method] = [];
            };

            $base = '';
            $routes = [];

            foreach (explode('[', str_replace(']', '', $prefix . $pattern)) as $route) {
                $r = $base . $route;
                $base = $r;
                $routes[] = Utils::clearURI($r);
            };

            foreach (array_reverse($routes) as $r) {
                array_push($this->map[$method], $r);
            };
        };

        return $this;
    }

    /**
     * Group
     *
     * @param string $pattern
     * @param string|callable $callback
     * @param boolean $sub
     * @return mixed
     */
    public function group(string $pattern, $callback, bool $sub = false)
    {
        $this->last->cleanLast();

        if ($sub) {
            $this->group = '';
        };

        $this->isSubGroup = true;

        $routerGroup = new RouterSubgroup($this->group . $pattern, $this);
        call_user_func($callback, $routerGroup);
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
        $this->middlewares->add($this->last->getLast(), $mids);
        $this->last->cleanLast();
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

    /**
     * Disable Group in Router
     */
    public function __destruct()
    {
        $this->router->disableGroup();
    }
}