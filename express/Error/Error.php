<?php

declare(strict_types=1);

namespace Fyyb\Error;

/**
 * Class Fyyb Error
 *
 * @author Joao Netto <https://github.com/jnetto23>
 * @package Fyyb\express
 */
class Error
{
    /**
     * Report Error
     * defines the type of error return
     *
     * @var string
     */
    private $reportError = 'html';

    /**
     * Returns a single instance of the class.
     *
     * @return Error
     */
    public static function getInstance(): Error
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Error();
        }
        return $instance;
    }

    /**
     * Protected constructor method prevents a new instance of the
     * Class from being created using the `new` operator from outside that class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method prevents cloning of this class instance
     */
    private function __clone()
    {
    }

    /**
     * Public wakeup method prevent deserialization of the instance of this class.
     */
    public function __wakeup()
    {
    }

    /**
     * Set Errors With JSON
     *
     * @return void
     */
    public function setErrorsWithJson()
    {
        $this->reportError = 'json';
        return $this;
    }

    /**
     * Set Errors With HTML
     *
     * @return void
     */
    public function setErrorsWithHtml()
    {
        $this->reportError = 'html';
        return $this;
    }

    /**
     * Get Report Error
     *
     * @return void
     */
    public function getReportError()
    {
        return $this->reportError;
    }
}
