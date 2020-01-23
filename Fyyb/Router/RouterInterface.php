<?php

namespace Fyyb\Router;

interface RouterInterface
{
    public function map(Array $method, String $pattern, $callable);

    public function get(String $pattern, $callable);

    public function post(String $pattern, $callable);

    public function put(String $pattern, $callable);

    public function delete(String $pattern, $callable);

    public function any(String $pattern, $callable);
}