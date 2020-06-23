<?php

declare(strict_types = 1);

namespace Fyyb\Middleware;

use Closure;
use Fyyb\Request;
use Fyyb\Response;
use Fyyb\Router\Dispatcher;

class MiddlewareHandler
{
    /**
     * Middlewares Collection
     *
     * @var array
     */
    private $Middlewares = [];

    /**
     * Returns a single instance of the class.
     *
     * @return MiddlewareHandler
     */
    public static function getInstance(): MiddlewareHandler
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new MiddlewareHandler();
        }
        return $instance;
    }

    /**
     * Protected constructor method prevents a new instance of the
     * Class from being created using the `new` operator from outside that class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method prevents cloning of this class instance
     */
    private function __clone()
    {
    }

    /**
     * Private wakeup method prevent deserialization of the instance of this class.
     */
    private function __wakeup()
    {
    }

    /**
     * Add Middlewares
     *
     * @param array $last
     * @param array $middlewares
     * @return void
     */
    public function add(array $last, array $middlewares)
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
                };
                $this->Middlewares[$method][$pt] = array_merge($y, $x);
            };
        };
    }

    /**
     * Get Middlewares This Route
     *
     * @param string $method
     * @param string $pt
     * @return array|null
     */
    public function getMiddlewaresThisRoute(string $method, string $pt)
    {
        if (isset($this->Middlewares[$method][$pt])) {
            return $this->Middlewares[$method][$pt];
        } else {
            return false;
        };
    }

    /**
     * Call Middleware
     *
     * @param string|callable $middleware
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return void
     */
    public static function call($middleware, Request $request, Response $response, Closure $next)
    {
        if (is_string($middleware)) {
            if (!empty($middleware)) {
                if (strpos($middleware, ':')) {
                    $middle = explode(':', $middleware);
                } elseif (strpos($middleware, '@')) {
                    $middle = explode('@', $middleware);
                };

                if (is_array($middle) && count($middle) === 2) {
                    $class = trim($middle[0]);
                    $action = trim($middle[1]);
                    if (class_exists($class) && method_exists($class, $action)) {
                        $m = new $class;
                        return call_user_func_array(
                            array($m, $action),
                            [
                                $request,
                                $response,
                                $next,
                            ]
                        );
                    };
                };
            };

            (new Dispatcher())
                ->responseError(
                    [
                        'code' => 501,
                        'title' => 'Method not implemented',
                        'details' => ['Middleware' => $middleware],
                    ]
                );
            exit;
        };

        if (is_callable($middleware)) {
            return $middleware($request, $response, $next);
        };
    }

    /**
     * Execute Middlewares
     *
     * @param array $middlewares
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public static function executeMiddlewares(array $middlewares, Request &$request, Response &$response)
    {
        foreach ($middlewares as $middleware) {
            $response = self::call(
                $middleware,
                $request,
                $response,
                function ($request, $response) {
                    return $response;
                }
            );
        };
    }
}
