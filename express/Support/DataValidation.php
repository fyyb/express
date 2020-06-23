<?php

declare(strict_types = 1);

namespace Fyyb\Support;

use \stdClass;

class DataValidation
{
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function verify(array $inputs, array $required)
    {
        $this->data->inputs = $inputs;
        $this->data->error = array();

        foreach ($required as $req) {
            if (!in_array($req, array_keys($this->data->inputs)) || empty($this->data->inputs[$req])) {
                $this->data->error[] = $req;
            };
        };

        return (count($this->data->error) === 0);
    }

    public function getError()
    {
        return $this->data->error;
    }

    public function getErrorMessage()
    {
        return (count($this->data->error) !== 0) ? 'required fields (' . implode(', ', $this->data->error) . ') not sent' : '';
    }
}
