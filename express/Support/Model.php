<?php

declare(strict_types=1);

namespace Fyyb\Support;

/**
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class Model
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $hidden = [];

    /**
     * @param String $name
     * @param Array $args
     */
    public function __call(String $name, array $args)
    {
        $method = substr($name, 0, 3);
        $fieldName = strtolower(substr($name, 3, strlen($name)));

        switch ($method) {
            case 'get':
                return $this->data[$fieldName];
                break;

            case 'set':
                $this->data[$fieldName] = $args[0];
                break;
        };
    }

    /**
     * Set Data
     *
     * @param Array $data
     * @return void
     */
    public function setData(array $data = array()): void
    {
        foreach ($data as $key => $value) {
            $this->{'set' . ucfirst($key)}($value);
        }
        return;
    }

    /**
     * Get Data
     *
     * @return Array
     */
    public function getData(): array
    {
        $data = $this->data;

        if (count($this->hidden) > 0) {
            foreach ($this->hidden as $key) {
                if (array_key_exists($key, $data)) {
                    unset($data[$key]);
                };
            };
        }

        return $data;
    }
}