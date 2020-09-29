<?php

declare(strict_types=1);

namespace Fyyb\Router;

use Fyyb\Interfaces\RouterInterface;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class RouterDefault implements RouterInterface
{
    public function map(array $method, string $pattern, $callable)
    {
    }

    /**
     * Get
     * define accessible route by GET method
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function get(string $pattern, $callable)
    {
        $this->map(['GET'], $pattern, $callable);
        return $this;
    }

    /**
     * Post
     * define accessible route by POST method
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function post(string $pattern, $callable)
    {
        $this->map(['POST'], $pattern, $callable);
        return $this;
    }

    /**
     * PUT
     * define accessible route by HEAD method
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function put(string $pattern, $callable)
    {
        $this->map(['PUT'], $pattern, $callable);
        return $this;
    }

    /**
     * Delete
     * define accessible route by DELETE method
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function delete(string $pattern, $callable)
    {
        $this->map(['DELETE'], $pattern, $callable);
        return $this;
    }

    /**
     * Patch
     * define accessible route by PATCH method
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function patch(string $pattern, $callable)
    {
        $this->map(['PATCH'], $pattern, $callable);
        return $this;
    }

    /**
     * Options
     * define accessible route by OPTIONS method
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function options(string $pattern, $callable)
    {
        $this->map(['OPTIONS'], $pattern, $callable);
        return $this;
    }

    /**
     * Head
     * define accessible route by HEAD method
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function head(string $pattern, $callable)
    {
        $this->map(['HEAD'], $pattern, $callable);
        return $this;
    }

    /**
     * Any
     * define route accessible by all methods
     *
     * @param string $pattern
     * @param callable|string $callable
     * @return mixed
     */
    public function any(string $pattern, $callable)
    {
        $this->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD'], $pattern, $callable);
        return $this;
    }
}