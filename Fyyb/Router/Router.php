<?php

namespace Fyyb\Router;

use \Fyyb\Support\Singleton;
use \Fyyb\Middleware\MiddlewareHandler;
use \Fyyb\Http\Request;
use \Fyyb\Http\Response;

class Router extends Singleton implements RouterInterface
{
    private $map = array();
    private $middlewares;
    private $request;
    private $response;
    private $args;

    private $dirRoutes;
    private $last = array();
    private $lastGroup = false;
    
    public function map(Array $methods, String $pattern, $callable)
    {   
        if ($this->lastGroup === false) $this->last = [];
        
        if (is_array($methods)) {
            foreach ($methods as $method) {
                if (!array_key_exists($method, $this->map)) $this->map[$method] = [];
                if (!array_key_exists($method, $this->last)) $this->last[$method] = [];
             
                $base = '';
                $routes = [];
                
                foreach (explode('[', str_replace(']', '', $pattern)) as $route) {
                    $r = $base.$route;
                    $base = $r;
                    $routes[] = str_replace('//', '/', $r);
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

    public function getLast()
    {
        return $this->last;
    }

    protected function getUri() :String
	{
        // Pega a URL;
        $uri = $_GET['url'] ?? '';
        $uri = (substr($uri,-1) === '/')?substr($uri,0,strlen($uri)-1):$uri;
        $uri = '/'.$uri;
        return $uri;
    }

	private function match()
	{
        $this->request  = new Request();
        $this->response = new Response();

        $method = $this->request->getMethod();
        
        if (isset($this->map[$method])) {
            // Loop em todas as routes
            foreach ($this->map[$method] as $pt => $call) {       
                
                // identifica os argumentos e substitui por regex
                $pattern = preg_replace('(\{[a-z0-9]{0,}\})', '([a-z0-9]{0,})', $pt);
                
                // faz o match de URL
                if (preg_match('#^('.$pattern.')*$#i', $this->getUri(), $matches) === 1) {
                    array_shift($matches);array_shift($matches);
                    
                    //Pega todos os argumentos para associar
                    $items = array();
                    if (preg_match_all('(\{[a-z0-9]{0,}\})', $pt, $m)) {
                        $items = preg_replace('(\{|\})', '', $m[0]);
                    };
                                    
                    // Faz a associação dos argumentos
                    $args = array();
                    foreach ($matches as $key => $match) {
                        $args[$items[$key]] = $match;
                    };
                    
                    // seta os argumentos
                    $this->setArgs($args);
                    
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
        $this->response->json([
            'error' => [
                'code' => '0001',
                'msg'  => 'Route Not Found'
            ]
        ],404);
    }
    
    public function run()
    {
        $this->match();
    }
      
    /**
    *   Implementação da funcionalidade 'Group'
    *   possibilidade de criar grupos de Rotas passando um pattern raiz 
    */
    public function group(String $pattern, $callback)
    {
        $this->lastGroup = true;
        call_user_func($callback, new RouterGroup($pattern));
        return $this;
    }
    
    /**
    *   Implementação da funcionalidade 'Use'
    *   possibilidade de criar as Rotas em arquivos diferentes 
    */
    public function setDirRoutes($dirRoutes)
    {
        $this->dirRoutes = $dirRoutes; 
        return $this;
    }

    protected function getDirRoutes()
    {
        return $this->dirRoutes; 
    }

    public function use(String $pattern, $fileRoute)
    {
        if ($this->getDirRoutes() === null) {
            echo 'Diretorio de Rotas não informado';
            exit;
        };

        $useRoute = new RouterUse($pattern);
        $useRoute->load($fileRoute);
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
                    return call_user_func_array(array($controller, $action), [$this->request, $this->response, $this->getArgs()]);
                }; 
            }; 
        };

        echo $this->response->json([
            'error' => [
                'code' => '0002',
                'msg' => 'Route Not Implemented'
            ]
        ],501);
        
        exit;       
    }

    private function callableFunction($callable)
    {
        $callable(
            $this->request,
            $this->response,
            $this->getArgs()
        );
    }

    private function setArgs($args = array())
    {
        $this->args = $args;
        return $this;
    }
    
    private function getArgs()
    {
        return $this->args;
    }

    /**
     * Implementação da funcionalidade de Middlewares
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

    public function getRoutes()
    {   
        $mid = MiddlewareHandler::getInstance();
        return array($this->map, $mid->getMids());
    }
}