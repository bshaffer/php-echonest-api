<?php

/**
 * Abstract class for EchoNest_Api classes
 *
 * @author    Brent Shaffer <bshafs at gmail dot com>
 * @license   MIT License
 */
abstract class EchoNest_Api implements EchoNest_ApiInterface
{
    const
        ALL  = 0,
        ARR  = 1,
        BOOL = 2,
        DEC  = 4,
        INT  = 8,
        STR  = 16;
        
    /**
    * The core EchoNest Client
    * @var EchoNest_Client
    */
    protected 
        $client,
        $options = array(),
        $validation = null;

    public function __construct(EchoNest_Client $client, $options = array())
    {
        $this->client  = $client;
        $this->options = $options;
    }

    /**
     * Call any path, GET method
     * Ex: $api->get('artist/biographies', array('name' => 'More Hazards More Heroes'))
     *
     * @param   string  $path             the EchoNest path
     * @param   array   $parameters       GET parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    protected function get($path, array $parameters = array(), $requestOptions = array())
    {
        $this->checkParameters($parameters);
        
        return $this->client->get($path, $parameters, $requestOptions);
    }

    /**
     * Call any path, POST method
     * Ex: $api->post('catalog/create', array('type' => 'artist', 'name' => 'My Catalog'))
     *
     * @param   string  $path             the EchoNest path
     * @param   array   $parameters       POST parameters
     * @param   array   $requestOptions   reconfigure the request
     * @return  array                     data returned
     */
    protected function post($path, array $parameters = array(), $requestOptions = array())
    {
        $this->checkParameters($parameters);
        
        return $this->client->post($path, $parameters, $requestOptions);
    }

    /**
    * Change an option value.
    *
    * @param string $name   The option name
    * @param mixed  $value  The value
    *
    * @return EchoNestApiAbstract the current object instance
    */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
    * Get an option value.
    *
    * @param  string $name The option name
    *
    * @return mixed  The option value
    */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }
    
    public function setValidation($validation)
    {
        $this->validation = $validation;
    }
    
    public function checkParameters($parameters, $validation = null)
    {
        $validation = $validation ? $validation : $this->validation;

        if (!$validation) {
            return true;
        }

        foreach ($parameters as $name => $value) {
            if (!isset($validation[$name])) {
                throw new EchoNest_Api_ParameterException(sprintf('Parameter name "%s" is not supported by this function', $name));
            }
            
            $supportedTypes = $validation[$name];
            
            if (is_array($supportedTypes) && is_array($value)) {
                $this->checkParameters($value, $supportedTypes);
                continue;
            }
            
            if ($supportedTypes & self::ALL) {
                continue;
            }
            
            if ($supportedTypes & self::ARR && is_array($value)) {
                continue;
            }
            
            if ($supportedTypes & self::BOOL && is_bool($value)) {
                continue;
            }
            
            if ($supportedTypes & self::DEC && $this->checkDec($value)) {
                continue;
            }
            
            if ($supportedTypes & self::INT && $this->checkInt($value)) {
                continue;
            }
            
            if ($supportedTypes & self::STR && is_string($value)) {
                continue;
            }
            
            throw new EchoNest_Api_ParameterException($name, $value, $supportedTypes);
        }
    }
    
    protected function checkInt($value)
    {
        if (is_int($value)) {
            return true;
        }
        
        if ($value == '0') {
            return true;
        }
        
        if (intval($value)) {
            return true;
        }

        return false;
    }
    
    protected function checkDec($value)
    {
        if(is_numeric($value)) {
            return true;
        }
        
        if ($value == '0') {
            return true;
        }
        
        if (floatval($value)) {
            return true;
        }

        return false;
    }
}
