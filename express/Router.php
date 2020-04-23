<?php

declare(strict_types=1);

namespace Fyyb;

use Fyyb\Error\HtmlErrorRenderer;
use Fyyb\Error\JsonErrorRenderer;
use Fyyb\Support\Singleton;
use Fyyb\Support\Utils;
use Fyyb\Interfaces\RouterInterface;
use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Request;
use Fyyb\Response;

class Router extends Singleton implements RouterInterface
{
    private $map = array();
    private $middlewares;
    private $request;
    private $response;
    private $dirRoutes;
    private $last = array();
    private $lastGroup = false;
    
    private $corsOrigin = '*';
    private $corsMethods = '*';
    private $corsHeaders = 'true';

    private $reportError = 'html';

    public function map(Array $methods, String $pattern, $callable) :Router
    {   
        if ($this->lastGroup === false) $this->last = [];
        
        if (is_array($methods)) {
            foreach ($methods as $method) {
                $method = strtoupper($method);
                if (!array_key_exists($method, $this->map)) $this->map[$method] = [];
                if (!array_key_exists($method, $this->last)) $this->last[$method] = [];
             
                $base = '';
                $routes = [];
                
                foreach (explode('[', str_replace(']', '', $pattern)) as $route) {
                    $r = $base.$route;
                    $base = $r;
                    
                    $routes[] = Utils::clearURI($r); 
                };
                
                foreach (array_reverse($routes) as $r) {
                    array_push($this->last[$method], $r);
                    $this->map[$method][$r] = $callable;
                };
			};
        };
        return $this;
	}

    public function get(String $pattern, $callable) :Router
    {
        $this->map(['GET'], $pattern, $callable);
        return $this;
	}

    public function post(String $pattern, $callable) :Router
    {
		$this->map(['POST'], $pattern, $callable);
		return $this;
	}

    public function put(String $pattern, $callable) :Router
    {
		$this->map(['PUT'], $pattern, $callable);
		return $this;
	}

    public function delete(String $pattern, $callable) :Router 
    {
		$this->map(['DELETE'], $pattern, $callable);
		return $this;
	}

    public function any(String $pattern, $callable) :Router
    {
		$this->map(['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'], $pattern, $callable);
		return $this;
    }

    public function run() :void
    {
        header('Access-Control-Allow-Origin: ' . $this->corsOrigin);
        header('Access-Control-Allow-Methods: ' . $this->corsMethods);
        header('Access-Control-Allow-Headers: ' . $this->corsHeaders);
        $this->match();
        exit;
    }

    private function match() :void
	{
        $this->request  = new Request();
        $this->response = new Response();
        $method = $this->request->getMethod();
        
        if (isset($this->map[$method])) {
            // Loop em todas as routes
            foreach ($this->map[$method] as $pt => $call) {       
                // identifica os argumentos e substitui por regex
                $pattern = preg_replace('(\:[a-z0-9]{0,})', '([a-z0-9]{0,})', $pt);
                // faz o match de URL
                if (preg_match('#^('.$pattern.')*$#i', $this->request->getUri(), $matches) === 1) {
                    array_shift($matches);
                    array_shift($matches);
                    //Pega todos os argumentos para associar
                    $items = array();
                    if (preg_match_all('(\:[a-z0-9]{0,})', $pt, $m)) {
                        $items = preg_replace('(\:)', '', $m[0]);
                    };                                    
                    // Faz a associação dos argumentos
                    $args = array();
                    foreach ($matches as $key => $match) {
                        $args[$items[$key]] = $match;
                    };
                    // seta os argumentos
                    $this->request->params = $args;
                    // Verifica se esta rota possui Middlewares Cadastrados
                    $mid = MiddlewareHandler::getInstance();
                    $midThisRoute = $mid->getMiddlewaresThisRoute($method, $pt);
                    if ($midThisRoute && count($midThisRoute) > 0) {
                        $mid::executeMiddlewares($midThisRoute, $this->request, $this->response);
                    };
                    if (is_string($call)) {
                        $this->callableController($call);
                        exit;
                    };
                    if (is_callable($call)) {
                        $this->callableFunction($call);
                        exit;
                    };                  
                    break;
                };
            };            
        };

        if($this->reportError === 'html') {
            new HtmlErrorRenderer(404);
        } else if($this->reportError === 'json') {
            new JsonErrorRenderer(404);
        };
    }
    
    private function callableController($callable)
    {
        if (!empty($callable)) {
            $call = explode(':', $callable); 
            if (is_array($call) && count($call) === 2) {
                $class  = trim($call[0]);
                $action = trim($call[1]);
                
                if (class_exists($class) && method_exists($class, $action)) {
                    $controller = new $class;
                    return call_user_func_array(
                        array($controller, $action), [
                            $this->request, 
                            $this->response
                        ]
                    );
                }; 
            }; 
        };

        if($this->reportError === 'html') {
            new HtmlErrorRenderer(501);

        } else if($this->reportError === 'json') {
            new JsonErrorRenderer(501);
        };      
    }

    private function callableFunction($callable)
    {
        $callable(
            $this->request,
            $this->response
        );
    }

    public function getLast()
    {
        return $this->last;
    }

    /**
    *   Implementação da funcionalidade 'group'
    *   possibilidade de criar grupos de Rotas passando um pattern raiz 
    */
    public function group(String $pattern, $callback)
    {
        $this->lastGroup = true;
        call_user_func($callback, new \Fyyb\Router\RouterGroup($pattern));
        return $this;
    }
    
    /**
    *   Implementação da funcionalidade 'use'
    *   possibilidade de criar as Rotas em arquivos diferentes 
    */
    public function setDirRoutes($dirRoutes)
    {
        $this->dirRoutes = $dirRoutes; 
        return $this;
    }

    public function getDirRoutes()
    {
        return $this->dirRoutes; 
    }

    public function use(String $pattern, String $fileRoute)
    {
        // if ($this->getDirRoutes() === null) {
        //     echo 'Diretorio de Rotas não informado';
        //     exit;
        // };
        if(empty($fileRoute)) {
            echo 'File Route não informado';
        };


        $useRoute = new \Fyyb\Router\RouterUse($pattern);
        $useRoute->load($fileRoute);
    }

    /**
     *  Implementação da funcionalidade de 'add'
     *  possibilidade de criar Middlewares
     */
    public function add(...$mids)
    {
        if ($this->middlewares === null) {
            $this->middlewares = MiddlewareHandler::getInstance(); 
        };

        if (count($this->last) > 0) {
            $this->middlewares->add($this->last, $mids);
            $this->last = [];
            $this->lastGroup = false;
        };

    }

    /**
     *  Settings Cors
     */
    public function setOriginCors(String $value = '*') 
    {
        $this->corsOrigin = $value;
        return $this;
    }
    
    public function setMethodsCors(String $value = '*') 
    {
        $this->corsMethods = $value;
        return $this;
    }
    
    public function setHeadersCors(String $value = 'true') 
    {
        $this->corsHeaders = $value;
        return $this;
    }

    /**
     * Set Response Error
     */
    public function setResponseErrorsWithJson()
    {
        $this->reportError = 'json';
        return $this;
    }

    public function setResponseErrorsWithHtml()
    {
        $this->reportError = 'html';
        return $this;
    }
}