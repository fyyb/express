<?php

declare(strict_types=1);

namespace Fyyb\Middleware;

use Fyyb\Support\Singleton;
use Fyyb\Router;

class MiddlewareHandler extends Singleton
{

    private $Middlewares = array();
   
    public function add(Array $last, $middlewares)
    {   
        foreach ($last as $method => $patterns) {
            if (!array_key_exists($method, $this->Middlewares)) {
                $this->Middlewares[$method] = [];
            };            
            foreach ($patterns as $pt) {
                $x = array();
                $y = array();
                foreach ($middlewares as $mid) {
                    $x = $this->Middlewares[$method][$pt] ?? array();
                    $y[] = $mid;
                    $error[] = array(
                        "x" => $x, 
                        "y" => $y, 
                        "merge" => array_merge($y,$x)
                    );
                };
                $this->Middlewares[$method][$pt] = array_merge($y,$x);
            };
        };
    }

    public function getMiddlewaresThisRoute($method, $pt)
    {
        if(isset($this->Middlewares[$method][$pt])) {
            return $this->Middlewares[$method][$pt];
        } else {
            return false;
        };
    }

    public static function call($middleware, $request, $response, $next)
    {
        if(is_string($middleware)) {            
            if (!empty($middleware)) {
                $middle = explode(':', $middleware); 
                if (is_array($middle) && count($middle) === 2) {
                    $class  = trim($middle[0]);
                    $action = trim($middle[1]);
                    if (class_exists($class) && method_exists($class, $action)) {
                        $m = new $class;
                        return call_user_func_array(
                            array($m, $action), [
                                $request, 
                                $response, 
                                $next
                            ]
                        );
                    }; 
                }; 
            };

            $request->error = [
                'code' => 501, 
                'title' => 'Method not implemented',
                'details' => [
                    'Method' => $middleware
                ]
            ];

            Router::getInstance()->responseError();
            exit;
        };

        if(is_callable($middleware)) {
            return $middleware($request, $response, $next);
        };
    }

    public static function executeMiddlewares(Array $middlewares, &$request, &$response)
    {
        foreach ($middlewares as $middleware) {
            $response = self::call($middleware, $request, $response, 
                function($request, $response){ 
                    return $response;
                }
            );
        };
    }

    public function getMids()
    {
        return $this->Middlewares;
    }
    
}