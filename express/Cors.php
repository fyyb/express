<?php

declare(strict_types = 1);

namespace Fyyb;

/**
 * Define the cors for use in APIs
 */
class Cors
{
    /**
     * Set CORS Origin
     *
     * @var string
     */
    private $corsOrigin;

    /**
     * Set CORS Methods
     *
     * @var string
     */
    private $corsMethods;

    /**
     * Set CORS Headers
     *
     * @var string
     */
    private $corsHeaders;

    /**
     * Returns a single instance of the class.
     *
     * @return Cors
     */
    public static function getInstance(): Cors
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new Cors();
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
     * Private wakeup method prevent deserialization of the instance of this class.
     */
    private function __wakeup()
    {
    }

    /**
     * Set Cors - Origin
     *
     * @param String $value
     * @return Cors
     */
    public function setOriginCors(String $value = '*'): Cors
    {
        $this->corsOrigin = $value;
        return $this;
    }

    /**
     * Set Cors - Methods
     *
     * @param String $value
     * @return Cors
     */
    public function setMethodsCors(String $value = '*'): Cors
    {
        $this->corsMethods = $value;
        return $this;
    }

    /**
     * Set Cors - Headers
     *
     * @param String $value
     * @return Cors
     */
    public function setHeadersCors(String $value = 'true'): Cors
    {
        $this->corsHeaders = $value;
        return $this;
    }

    /**
     * Get Cors - Origin
     *
     * @return String
     */
    public function getOriginCors(): ?String
    {
        return $this->corsOrigin;
    }

    /**
     * Get Cors - Methods
     *
     * @return String
     */
    public function getMethodsCors(): ?String
    {
        return $this->corsMethods;
    }

    /**
     * Get Cors - Headers
     *
     * @return String
     */
    public function getHeadersCors(): ?String
    {
        return $this->corsHeaders;
    }
}
