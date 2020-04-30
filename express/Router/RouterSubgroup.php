<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Router;
use Fyyb\Interfaces\RouterInterface;
use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Support\Utils;

class RouterSubgroup implements RouterInterface
{
    private $last;
    private $group;
    private $subgroup;
    private $router;
    private $middlewares;

    public function __construct($pattern, $map, $group)
    {
        $this->last = [];
        $this->group = $group;
        $this->subgroup = $pattern;
        $this->router = Router::getInstance();
        $this->middlewares = MiddlewareHandler::getInstance();
    }
    
    public function map(Array $methods, String $pattern, $callable)
    {
        $this->group->map($methods, $this->subgroup.$pattern, $callable, true);
        
        $this->last = [];
        if (is_array($methods)) {
            foreach ($methods as $method) {
                $method = strtoupper($method);
                if (!array_key_exists($method, $this->last)) $this->last[$method] = [];
             
                $base = '';
                $routes = [];
                
                foreach (explode('[', str_replace(']', '', $this->subgroup.$pattern)) as $route) {
                    $r = $base.$route;
                    $base = $r;                
                    $routes[] = Utils::clearURI($r); 
                };
                
                foreach (array_reverse($routes) as $r) {
                    array_push($this->last[$method], $r);
                };
			};
        };
            
        return $this;
	}

    public function get(String $pattern, $callable) :RouterSubgroup
    {
        $this->map(['GET'], $pattern, $callable);
        return $this;
	}

    public function post(String $pattern, $callable) :RouterSubgroup
    {
		$this->map(['POST'], $pattern, $callable);
        return $this;
	}

    public function put(String $pattern, $callable) :RouterSubgroup
    {
		$this->map(['PUT'], $pattern, $callable);
        return $this;
	}

    public function delete(String $pattern, $callable) :RouterSubgroup
    {
		$this->map(['DELETE'], $pattern, $callable);
        return $this;
	}

    public function any(String $pattern, $callable) :RouterSubgroup
    {
		$this->map(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], $pattern, $callable);
        return $this;
    }

    public function add(...$mids)
    {        
        $this->middlewares->add($this->last, $mids);
        $this->last = [];                
    }

}