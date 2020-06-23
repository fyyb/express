<?php

declare(strict_types = 1);

namespace Fyyb\Router;

use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Router\LastRouter;
use Fyyb\Router\RouterCollection;
use Fyyb\Router\RouterDefault;
use Fyyb\Router\RouterGroup;

class RouterSubgroup extends RouterDefault
{
    /**
     * instance of LastRouter
     *
     * @var LastRouter
     */
    private $last;

    /**
     * instance of RouterGroup
     *
     * @var RouterGroup
     */
    private $group;

    /**
     * route prefix
     *
     * @var string
     */
    private $subgroup;

    /**
     * instance of Middlewares
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
     * Class Constructor
     *
     * @param string $pattern
     * @param RouterGroup $group
     */
    public function __construct(string $pattern, RouterGroup $group)
    {
        $this->group = $group;
        $this->subgroup = $pattern;
        $this->routerCollection = RouterCollection::getInstance();
        $this->last = new LastRouter();
        $this->middlewares = MiddlewareHandler::getInstance();
    }

    /**
     * MAP
     *
     * @param array $methods
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function map(array $methods, string $pattern, $callable)
    {
        $this->group->map($methods, $this->subgroup . $pattern, $callable, true);
        $this->last->cleanLast();
        $this->last->setLast($methods, $this->subgroup . $pattern);
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
}
