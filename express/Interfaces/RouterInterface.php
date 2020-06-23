<?php

declare(strict_types = 1);

namespace Fyyb\Interfaces;

interface RouterInterface
{
    /**
     * MAP
     *
     * @param array $method
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function map(array $method, string $pattern, $callable);

    /**
     * GET
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function get(string $pattern, $callable);

    /**
     * POST
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function post(string $pattern, $callable);

    /**
     * PUT
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function put(string $pattern, $callable);

    /**
     * DELETE
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function delete(string $pattern, $callable);

    /**
     * PATCH
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function patch(string $pattern, $callable);

    /**
     * OPTIONS
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function options(string $pattern, $callable);

    /**
     * HEAD
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function head(string $pattern, $callable);

    /**
     * ANY
     *
     * @param string $pattern
     * @param string|callable $callable
     * @return void
     */
    public function any(string $pattern, $callable);
}
