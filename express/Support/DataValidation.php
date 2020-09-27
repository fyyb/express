<?php

declare(strict_types=1);

namespace Fyyb\Support;

use \stdClass;

class DataValidation
{
    /**
     * @var \stdClass
     */
    private $data;

    public function __construct()
    {
        $this->data = new stdClass();
        return $this;
    }

    /**
     * Check function
     *
     * @param array $inputs
     * @param array $required
     * @return DataValidation
     */
    public function check(array $inputs, array $required): DataValidation
    {
        $this->data->inputs = $inputs;
        $this->data->error = array();

        foreach ($required as $req) {
            if (
                !in_array($req, array_keys($this->data->inputs)) ||
                empty($this->data->inputs[$req])
            ) {
                $this->data->error[] = $req;
            };
        };

        return $this;
    }

    /**
     * Verify function
     *
     * @return Bool
     */
    public function verify(): Bool
    {
        return count($this->data->error) === 0;
    }


    /**
     * GetErrorMessage function
     *
     * @return String
     */
    public function getErrorMessage(): String
    {
        return (count($this->data->error) !== 0)
            ? 'required fields not sent or invalid. (' . implode(', ', $this->data->error) . ')'
            : '';
    }
}