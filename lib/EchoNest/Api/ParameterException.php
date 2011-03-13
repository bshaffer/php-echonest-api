<?php

/**
 * Request communication error
 *
 * @author    Brent Shaffer <bshafs at gmail dot com>
 * @license   MIT License
 */
class EchoNest_Api_ParameterException extends Exception
{
    /**
     * Http header-codes
     * @var  array
     */
    static protected $parameterTypes = array(
        EchoNest_API::ARR   => 'array',
        EchoNest_API::BOOL  => 'boolean',
        EchoNest_API::DEC   => 'decimal',
        EchoNest_API::INT   => 'integer',
        EchoNest_API::STR   => 'string',
    );

    /**
     * Default constructor
     *
     * @param  string $message
     * @param  mixed $value
     * @param  int $code
     */
    public function __construct($message, $value = null, $code = null)
    {
        $supportedTypes = array();
        
        if ($value && $code) {
            foreach (self::$parameterTypes as $typeCode => $typeString) {
                if ($code & $typeCode) {
                    $supportedTypes[] = $typeString;
                }
            }
        }

        $message = sprintf('Parameter %s is not of proper type. "%s" provided, expected %s. Please view the documentation for more information.', $message, $value, implode(' or ', $supportedTypes));

        parent::__construct($message);
    }
}