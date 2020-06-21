<?php

declare (strict_types = 1);

namespace Fyyb\Http;

trait HttpPopulateTrait
{
    protected $data = array();

    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    public function __set($key, $value)
    {
        $this->data[$key] = $value;

    }
}
