<?php

declare(strict_types = 1);

namespace Fyyb\Http;

trait HttpPopulateTrait
{
    /**
     * Data
     * data storage
     *
     * @var array
     */
    protected $data = array();

    /**
     * __GET
     * set values ​​in data storage
     *
     * @param mixed $key
     * @return void
     */
    public function __get($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * __SET
     * set values ​​in data storage
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set(string $key, $value)
    {
        $this->data[$key] = $value;
    }
}
