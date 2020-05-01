<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Router;
use Fyyb\Router\RouterSubgroup;
use Fyyb\Interfaces\RouterInterface;
use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Support\Utils;

class RouterGroup implements RouterInterface
{
    private $map;
    private $last;
    private $group;
    private $router;
    private $middlewares;
    private $isSubGroup;

    public function __construct($pattern)
    {
        $this->map = [];
        $this->last = [];
        $this->group = $pattern;
        $this->isSubGroup = false;
        $this->router = Router::getInstance();
        $this->middlewares = MiddlewareHandler::getInstance();
        
        $this->router->activateGroup();

        return $this;
    }
    
    public function map(Array $methods, String $pattern, $callable, $sub = false)
    {
        $prefix = $this->group;
        if($sub) $prefix = '';

        $this->router->map($methods, $prefix.$pattern, $callable);

        if(!$this->isSubGroup) $this->last = [];
        
        if (is_array($methods)) {
            foreach ($methods as $method) {
                $method = strtoupper($method);
                if (!array_key_exists($method, $this->last)) $this->last[$method] = [];
                if (!array_key_exists($method, $this->map)) $this->map[$method] = [];
             
                $base = '';
                $routes = [];
                
                foreach (explode('[', str_replace(']', '', $prefix.$pattern)) as $route) {
                    $r = $base.$route;
                    $base = $r;                
                    $routes[] = Utils::clearURI($r); 
                };
                
                foreach (array_reverse($routes) as $r) {
                    array_push($this->last[$method], $r);
                    array_push($this->map[$method], $r);
                };
			};
        };
            
        return $this;
	}

    public function get(String $pattern, $callable) :RouterGroup
    {
        $this->map(['GET'], $pattern, $callable);
        return $this;
	}

    public function post(String $pattern, $callable) :RouterGroup
    {
		$this->map(['POST'], $pattern, $callable);
        return $this;
	}

    public function put(String $pattern, $callable) :RouterGroup
    {
		$this->map(['PUT'], $pattern, $callable);
        return $this;
	}

    public function delete(String $pattern, $callable) :RouterGroup
    {
		$this->map(['DELETE'], $pattern, $callable);
        return $this;
	}

    public function any(String $pattern, $callable) :RouterGroup
    {
		$this->map(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], $pattern, $callable);
        return $this;
    }

    public function group(String $pattern, $callback, $sub=false)
    {
        $this->last = [];
        $prefix = $this->group;
        if($sub) $this->group = '';

        $this->isSubGroup = true;
        
        $routerGroup = new RouterSubgroup($this->group.$pattern, $this->map, $this);
        call_user_func($callback, $routerGroup);
        return $this;
    }

    public function add(...$mids)
    {
        $this->middlewares->add($this->last, $mids);
        $this->last = [];                
    }

    public function __destruct()
    {
        $this->router->disableGroup();
    }

}