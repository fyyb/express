<?php

declare(strict_types=1);

namespace Fyyb;

use Fyyb\Request;
use Fyyb\Response;
use Fyyb\Interfaces\RouterInterface;
use Fyyb\Support\Singleton;
use Fyyb\Support\Utils;
use Fyyb\Router\RouterGroup;
use Fyyb\Router\RouterUse;
use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Error\HtmlErrorRenderer;
use Fyyb\Error\JsonErrorRenderer;

class Router extends Singleton implements RouterInterface
{
    private $request;
    private $response;
    private $map = array();
    private $last = array();
    private $isGroup = false;
    private $middlewares;
    
    private $corsOrigin = '*';
    private $corsMethods = '*';
    private $corsHeaders = 'true';
    private $reportError = 'html';
    private $hasError;

    public function map(Array $methods, String $pattern, $callable)
    {   
        if(!$this->isGroup) $this->last = [];
        
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

    public function group(String $pattern, $callback)
    {
        $this->last = [];
        $this->isGroup = true;

        call_user_func($callback, new RouterGroup($pattern));
        return $this;
    }
    
    public function add(...$mids)
    {
        if ($this->middlewares === null) {
            $this->middlewares = MiddlewareHandler::getInstance(); 
        };

        $this->middlewares->add($this->last, $mids);
        $this->last = [];

    }

    public function use(String $pattern, String $fileRoute)
    {
        if(substr($fileRoute, -4) === '.php') {
            $f = $fileRoute;
        } else {
            $f = $fileRoute.'.php';
        };

        if (!file_exists($f)) {
            $this->request->error = [
                'code' => 404, 
                'title' => 'Route file not found',
                'details' => [
                    'File' => $f
                    ]
                ];
            $this->responseError();
        };
        
        new RouterUse($pattern, $f);
        
    }

    public function run() :void
    {
        header('Access-Control-Allow-Origin: ' . $this->corsOrigin);
        header('Access-Control-Allow-Methods: ' . $this->corsMethods);
        header('Access-Control-Allow-Headers: ' . $this->corsHeaders);

        $this->request  = new Request();
        $this->response = new Response();
        
        $this->match();
        exit;
    }

    private function match()
	{
        
        $method = $this->request->getMethod();
        
        if (isset($this->map[$method])) {
            // Loop em todas as routes
            foreach ($this->map[$method] as $pt => $call) {       
                // identifica os argumentos e substitui por regex
                $pattern = '/'.preg_replace('(\:[a-z0-9]{0,})', '([a-z0-9]{0,})', $pt);
                $pattern = Utils::clearURI($pattern);
                // faz o match de URL
                if (preg_match('#^('.$pattern.')$#i', $this->request->getUri(), $matches) === 1) {
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

        $this->request->error = [
            'code' => 404, 
            'title' => 'Not Found Route',
            'details' => [
                'Route' => $this->request->getUri()
            ]
        ];
        $this->responseError();
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
        
        $this->request->error = [
            'code' => 501, 
            'title' => 'Method not implemented',
            'details' => [
                'Method' => $callable
            ]
        ];
        $this->responseError();
    }

    private function callableFunction($callable)
    {
        $callable(
            $this->request,
            $this->response
        );
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

    public function hasError($callable)
    {
        $this->hasError = $callable;
        return $this;
    }

    public function responseError() 
    {
        if(!empty($this->hasError)) {
            if (is_string($this->hasError)) {
                $this->callableController($this->hasError);
                exit;
            };

            if (is_callable($this->hasError)) {
                $this->callableFunction($this->hasError);
                exit;
            };
        }

        $code = $this->request->error['code'];
        $title = $this->request->error['title'];
        $details = $this->request->error['details'];
        
        if($this->reportError === 'html') {
            new HtmlErrorRenderer($code, $title, $details);
        } else if($this->reportError === 'json') {
            new JsonErrorRenderer($code, $title, $details);
        };
    }
}