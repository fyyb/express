<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Router;
use Fyyb\Interfaces\RouterInterface;
use Fyyb\Middleware\MiddlewareHandler;

class RouterGroup implements RouterInterface
{
    private $group;
    private $router;
    private $middlewares;

    public function __construct($pattern)
    {
        $this->group = $pattern;
        $this->router = Router::getInstance();
        return $this;
    }
    
    public function map(Array $method, String $pattern, $callable): RouterGroup
    {
        $this->router->map($method, $this->group.$pattern, $callable);
        return $this;
	}

    public function get(String $pattern, $callable): RouterGroup
    {
        $this->map(['GET'], $pattern, $callable);
        return $this;
	}

    public function post(String $pattern, $callable): RouterGroup
    {
		$this->map(['POST'], $pattern, $callable);
		return $this;
	}

    public function put(String $pattern, $callable): RouterGroup
    {
		$this->map(['PUT'], $pattern, $callable);
		return $this;
	}

    public function delete(String $pattern, $callable): RouterGroup
    {
		$this->map(['DELETE'], $pattern, $callable);
		return $this;
	}

    public function any(String $pattern, $callable): RouterGroup
    {
		$this->map(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], $pattern, $callable);
		return $this;
    }

    public function add(...$mids)
    {
        if ($this->middlewares === null) {
            $this->middlewares = MiddlewareHandler::getInstance(); 
        };
        $this->middlewares->add($this->router->getLast(), $mids);
    }
}