<?php

namespace Fyyb\Router;

use Fyyb\Interfaces\RouterInterface;

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
        $dir = $this->router->getDirRoutes();
        $file = $dir.DIRECTORY_SEPARATOR.$f.'.php';

        if (file_exists($file)) {
            require_once $file;
        } else {
            echo 'Arquivo de Rotas Não Encontrado <br>';
            echo 'em: '.$file;
            exit;
        };
    }

    public function map(Array $method, String $pattern, $callable): RouterUse
    {
        $this->router->map($method, $pattern, $callable);
        return $this;
	}

    public function get(String $pattern, $callable): RouterUse
    {
        $this->map(['GET'], $this->use.$pattern, $callable);
        return $this;
	}

    public function post(String $pattern, $callable): RouterUse
    {
		$this->map(['POST'], $this->use.$pattern, $callable);
		return $this;
	}

    public function put(String $pattern, $callable): RouterUse
    {
		$this->map(['PUT'], $this->use.$pattern, $callable);
		return $this;
	}

    public function delete(String $pattern, $callable): RouterUse
    {
		$this->map(['DELETE'], $this->use.$pattern, $callable);
		return $this;
	}

    public function any(String $pattern, $callable): RouterUse
    {
		$this->map(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], $this->use.$pattern, $callable);
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