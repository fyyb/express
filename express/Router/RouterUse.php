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

    public function __construct($pattern)
    {
        $this->use = $pattern;
        $this->router = Router::getInstance();
        return $this;
    }
    
    public function load($f)
    {
        $this->loadRouteFile($f);
        return $this;
    }

    private function loadRouteFile($f)
    {
        $f = $f.'.php';

        if ($this->router->getDirRoutes()) {
            $f = $dir.DIRECTORY_SEPARATOR.$f;
        }

        if (file_exists($f)) {
            require_once $f;
        } else {
            echo 'Arquivo de Rotas Não Encontrado <br>';
            echo 'em: '.$f;
            exit;
        };
    }

    public function map(Array $method, String $pattern, $callable): RouterUse
    {
        $this->router->map($method, $this->use.$pattern, $callable);
        return $this;
	}

    public function get(String $pattern, $callable): RouterUse
    {
        $this->map(['GET'], $pattern, $callable);
        return $this;
	}

    public function post(String $pattern, $callable): RouterUse
    {
		$this->map(['POST'], $pattern, $callable);
		return $this;
	}

    public function put(String $pattern, $callable): RouterUse
    {
		$this->map(['PUT'], $pattern, $callable);
		return $this;
	}

    public function delete(String $pattern, $callable): RouterUse
    {
		$this->map(['DELETE'], $pattern, $callable);
		return $this;
	}

    public function any(String $pattern, $callable): RouterUse
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
    
    /**
    *   Implementação da funcionalidade 'group'
    *   possibilidade de criar grupos de Rotas passando um pattern raiz 
    */
    public function group(String $pattern, $callback)
    {
        $this->router->group($this->use.$pattern, $callback);
        return $this;
    }
}