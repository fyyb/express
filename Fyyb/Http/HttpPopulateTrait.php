<?php

namespace Fyyb\Http;

trait HttpPopulateTrait
{
    protected $data = array();

    public function __get($key)
    {
        return $this->data[$key];
    }
    
    public function __set($key, $value)
    {
        $this->data[$key] = $value;

    }
}