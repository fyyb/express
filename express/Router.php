<?php

declare(strict_types = 1);

namespace Fyyb;

use Fyyb\Error\Error;
use Fyyb\Middleware\MiddlewareHandler;
use Fyyb\Router\Dispatcher;
use Fyyb\Router\LastRouter;
use Fyyb\Router\RouterCollection;
use Fyyb\Router\RouterDefault;
use Fyyb\Router\RouterGroup;
use Fyyb\Router\RouterUse;

class Router extends RouterDefault
{
    /**
     * instance of RouterCollection
     *
     * @var RouterCollection
     */
    private $routerCollection;

    /**
     * instance of RouterLast
     *
     * @var RouterLast
     */
    private $last;

    /**
     * instance of Middlewares
     *
     * @var Middlewares
     */
    private $middlewares;

    /**
     * instance of Error
     *
     * @var Error
     */
    private $error;

    /**
     * instance of Dispatcher
     *
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Is Group
     *
     * @var boolean
     */
    private $isGroup = false;

    /**
     * Returns a single instance of the class.
     *
     * @return Router
     */
    public static function getInstance(): Router
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Router();
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
     * Map
     * define accessible route for methods passed by parameter
     *
     * @param array $methods
     * @param String $pattern
     * @param callable|string $callable
     * @return void
     */
    public function map(array $methods, String $pattern, $callable)
    {
        $this->getInstances();

        // Check that the route group is active
        if (!$this->isGroup) {
            $this->last->cleanLast();
        }

        // Set Routes in Collection
        $this->routerCollection->setRoutesInCollection($methods, $pattern, $callable);
        // Set Last Routes
        $this->last->setLast($methods, $pattern);

        return $this;
    }

    /**
     * Group
     * Start RouterGroup
     *
     * @param String $pattern
     * @param callable $callback
     * @return void
     */
    public function group(String $pattern, callable $callback)
    {
        $this->getInstances();

        // Clears the history of the last routes
        $this->last->cleanLast();
        // Start RouterGroup
        call_user_func($callback, new RouterGroup($pattern));
        return $this;
    }

    /**
     * Add
     * Set Middlewares for routes
     *
     * @param array ...$mids
     * @return void
     */
    public function add(...$mids): void
    {
        $this->getInstances();

        // Set middlewares
        $this->middlewares->add($this->last->getLast(), $mids);
        // Clears the history of the last routes
        $this->last->cleanLast();
    }

    /**
     * Use
     * defines a file to serve routes with a certain prefix
     *
     * @param String $pattern
     * @param String $fileRoute
     * @return void
     */
    public function use(String $pattern, String $fileRoute)
    {
        $this->getInstance();

        $f = (substr($fileRoute, -4) === '.php') ? $fileRoute : $fileRoute . '.php';

        if (file_exists($f)) {
            // Clears the history of the last routes
            $this->last->cleanLast();
            // Start RouterUse
            new RouterUse($pattern, $f);

            return $this;
        } else {

            // return error
            $error = [
                'code' => 404,
                'title' => 'Route file not found',
                'details' => [
                    'File' => $f,
                ],
            ];

            $this->dispatcher->responseError($error);
            exit;
        };
    }

    /**
     * Where
     * set params pattern
     *
     * @param array $arr
     * @return void
     */
    public function where(array $arr)
    {
        $this->getInstances();
        foreach ($this->last->getLast() as $method => $routes) {
            foreach ($routes as $route) {
                foreach ($arr as $key => $pattern) {
                    if (strstr($route, ':' . $key)) {
                        $this->routerCollection->setParamsPatternInRoute($method, $route, $key, $pattern);
                    };
                };
            }
        };
    }

    /**
     * Fallback
     * set default route to 404
     *
     * @param callable|string $callable
     * @return Router
     */
    public function fallback($callable): Router
    {
        $this->getInstances();

        $this->routerCollection->setFallback($callable);
        return $this;
    }

    /**
     * Run
     * Start application
     *
     * @return void
     */
    public function run(): void
    {
        $this->getInstance();
        $this->dispatcher->match();
        exit;
    }

    /**
     * Get Instances
     *
     * @return void
     */
    private function getInstances()
    {
        // Get instance RouterCollection
        if ($this->routerCollection === null) {
            $this->routerCollection = RouterCollection::getInstance();
        };

        // Get instance MiddlewareHandler
        if ($this->middlewares === null) {
            $this->middlewares = MiddlewareHandler::getInstance();
        };

        // Get instance Last
        if ($this->last === null) {
            $this->last = new LastRouter();
        };

        // Get instance Error
        if ($this->error === null) {
            $this->error = Error::getInstance();
        };

        // Get instance Dispatcher
        if ($this->dispatcher === null) {
            $this->dispatcher = new Dispatcher();
        };
    }

    /**********************
     * Group
     **********************/

    /**
     * Disable Group
     * set 'isGroup' to TRUE
     *
     * @return void
     */
    public function activateGroup(): void
    {
        $this->isGroup = true;
    }

    /**
     * Disable Group
     * set 'isGroup' to FALSE
     *
     * @return void
     */
    public function disableGroup(): void
    {
        $this->isGroup = false;
    }

    /**********************
     * Set Response Error
     **********************/
    /**
     * Set Error Response Type
     *
     * @param string $type ['json' | 'html']
     * @return Router
     */
    public function setErrorResponseType(string $type): Router
    {
        $error = Error::getInstance();
        if (strtoupper($type) === 'JSON') {
            $error->setErrorsWithJson();
        } elseif (strtoupper($type) === 'HTML') {
            $error->setErrorsWithHtml();
        };
        return $this;
    }
}
