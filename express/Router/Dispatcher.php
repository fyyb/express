<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Cors;
use Fyyb\Error\Error;
use Fyyb\Error\HtmlErrorRenderer;
use Fyyb\Error\JsonErrorRenderer;
use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Request;
use Fyyb\Response;
use Fyyb\Router\RouterCollection;
use Fyyb\Support\Utils;

class Dispatcher
{
    /**
     * instance of Request
     *
     * @var Request
     */
    private $request;

    /**
     * instance of Response
     *
     * @var Response
     */
    private $response;

    /**
     * instance of RouterCollection
     *
     * @var RouterCollection
     */
    private $routerCollection;

    /**
     * instance of Error
     *
     * @var Error
     */
    private $error;

    public function __construct()
    {
        // Start Request
        $this->request = new Request();
        // Start Response
        $this->response = new Response();
        // Get instance RouterCollection
        $this->routerCollection = RouterCollection::getInstance();
        // Get Instance Error
        $this->error = Error::getInstance();
    }

    /**
     * Match
     *
     * @return void
     */
    public function match()
    {
        // Start CORS
        $this->defineCors();
        // Get Request Method
        $method = $this->request->getMethod();
        // Get Request URI
        $uri = $this->request->getUri();
        // Get routes by method
        $routesByMethod = $this->routerCollection->getRoutesInCollection($method);

        if ($routesByMethod) {
            // Loop on all routes
            foreach ($routesByMethod as $pt => $call) {
                // identifies arguments and replaces with regex
                $pattern = '/' . preg_replace('(\:[a-z0-9-]{0,})', '([a-z0-9-]{0,})', $pt);
                // clean the URI
                $pattern = Utils::clearURI($pattern);
                // matches URI
                if (preg_match('#^(' . $pattern . ')$#i', $uri, $matches) === 1) {
                    array_shift($matches);
                    array_shift($matches);
                    // get all the arguments to associate
                    $items = array();
                    if (preg_match_all('(\:[a-z0-9]{0,})', $pt, $m)) {
                        $items = preg_replace('(\:)', '', $m[0]);
                    };
                    // make an association of the arguments
                    $args = array();
                    $checkParamsPatternInRoute = true;

                    foreach ($matches as $key => $match) {
                        // checks if there is ParamPatternInRoute;
                        $ParamPatternInRoute = $this->routerCollection->getParamsPatternInRoute($method)[$pt] ?? [];
                        if (count($ParamPatternInRoute) > 0) {
                            if (array_key_exists($items[$key], $ParamPatternInRoute)) {
                                if (!preg_match('#^(' . $ParamPatternInRoute[$items[$key]] . ')$#i', $match)) {
                                    $checkParamsPatternInRoute = false;
                                };
                            };
                        };
                        $args[$items[$key]] = $match;
                    };

                    if (!$checkParamsPatternInRoute) {
                        continue;
                    };

                    // define the arguments
                    $this->request->params = $args;
                    // checks if this route has Registered Middleware
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

        $this->fallback();

        $this->responseError([
            'code' => 404,
            'title' => 'Not Found Route',
            'details' => ['Route' => $this->request->getUri()],
        ]);
    }

    /**
     * Callable Controller
     *
     * @param String $callable
     * @return void
     */
    private function callableController(String $callable)
    {
        if (strpos($callable, ':')) {
            $call = explode(':', $callable);
        } elseif (strpos($callable, '@')) {
            $call = explode('@', $callable);
        };

        if (is_array($call) && count($call) === 2) {
            $class = trim($call[0]);
            $action = trim($call[1]);

            if (class_exists($class) && method_exists($class, $action)) {
                $controller = new $class;
                return call_user_func_array(
                    array($controller, $action),
                    [
                        $this->request,
                        $this->response,
                    ]
                );
            };
        };

        $this->responseError([
            'code' => 501,
            'title' => 'Method not implemented',
            'details' => ['Method' => $callable],
        ]);
    }

    /**
     * Callable Function
     *
     * @param callable $callable
     * @return void
     */
    private function callableFunction(callable $callable)
    {
        $callable(
            $this->request,
            $this->response
        );
    }

    /**
     * Define CORS
     *
     * @return void
     */
    private function defineCors(): void
    {
        $cors = Cors::getInstance();

        if (!empty($cors->getOriginCors())) {
            header('Access-Control-Allow-Origin: ' . $cors->getOriginCors());
        };

        if (!empty($cors->getMethodsCors())) {
            header('Access-Control-Allow-Methods: ' . $cors->getMethodsCors());
        };

        if (!empty($cors->getHeadersCors())) {
            header('Access-Control-Allow-Headers: ' . $cors->getHeadersCors());
        };
    }

    /**
     * Response Error
     *
     * @param array $error
     * @return void
     */
    public function responseError(array $error)
    {
        // Start CORS
        $this->defineCors();
        $this->fallback();

        $this->request->error = $error;

        if ($this->error->getReportError() === 'html') {
            new HtmlErrorRenderer($this->request, $this->response);
        } elseif ($this->error->getReportError() === 'json') {
            new JsonErrorRenderer($this->request, $this->response);
        };

        exit;
    }

    /**
     * Fallback
     *
     * @return void
     */
    private function fallback()
    {
        $fallback = $this->routerCollection->getFallback();
        if ($fallback) {
            if (is_string($fallback)) {
                $this->callableController($fallback);
                exit;
            };
            if (is_callable($fallback)) {
                $this->callableFunction($fallback);
                exit;
            };
        }

        return;
    }
}