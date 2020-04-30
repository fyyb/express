<?php

declare(strict_types=1);

namespace Fyyb\Support;

class Model
{
    private $data;
 
    public function __call($name, $args)
    {
        $method    = substr($name, 0, 3);
        $fieldName = substr($name, 3, strlen($name));

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
            $this->{'set'.$key} ($value);
        }
    }

    public function getData()
    {
        return $this->data;
    }
}