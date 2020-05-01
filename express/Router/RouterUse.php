<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Router;
use Fyyb\Interfaces\RouterInterface;
use Fyyb\Middleware\MiddlewareHandler;

class RouterUse implements RouterInterface
{
    private $use;
    private $router;
    private $middlewares;

    public function __construct($pattern, $file)
    {
        $this->use = $pattern;
        $this->router = Router::getInstance();
        require_once $file;
        return $this;
    }
    
    public function map(Array $method, String $pattern, $callable)
    {
        $this->router->map($method, $this->use.$pattern, $callable);
        return $this;
	}

    public function get(String $pattern, $callable) :RouterUse
    {
        $this->map(['GET'], $pattern, $callable);
        return $this;
	}

    public function post(String $pattern, $callable) :RouterUse
    {
		$this->map(['POST'], $pattern, $callable);
		return $this;
	}

    public function put(String $pattern, $callable) :RouterUse
    {
		$this->map(['PUT'], $pattern, $callable);
		return $this;
	}

    public function delete(String $pattern, $callable) :RouterUse
    {
		$this->map(['DELETE'], $pattern, $callable);
		return $this;
	}

    public function any(String $pattern, $callable) :RouterUse
    {
		$this->map(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], $pattern, $callable);
		return $this;
    }

    
    public function group(String $pattern, $callback)
    {
        $this->router->group($this->use.$pattern, $callback);
        return $this;
    }
    
    public function add(...$mids)
    {
        $this->router->add(...$mids);
    }

}