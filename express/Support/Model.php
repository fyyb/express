<?php

declare(strict_types = 1);

namespace Fyyb\Support;

class Model
{
    protected $data = array();

    public function __call($name, $args)
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

    public function setData($data = array())
    {
        foreach ($data as $key => $value) {
            $this->{'set' . ucfirst($key)}($value);
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
