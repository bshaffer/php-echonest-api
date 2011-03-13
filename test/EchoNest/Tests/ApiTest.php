<?php

class EchoNest_Tests_ApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException EchoNest_Api_ParameterException
     */
    public function testGetRequestIntegerWithInvalidStringParameterThrowsException()
    {
        $api = $this->getApiMock();
      
        $api->getWithInteger('This is not an integer');
    }

    public function testGetRequesIntegertWithStringIntegerParameter()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithInteger('15');
      
        $this->assertEquals(true, $response);
    }

    public function testGetRequestIntegerWithIntegerParameter()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithInteger(15);
      
        $this->assertEquals(true, $response);
    }

    public function testGetRequestBooleanWithBooleanParameter()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithBoolean(true);
      
        $this->assertEquals(true, $response);
    }
    
    /**
     * @expectedException EchoNest_Api_ParameterException
     */
    public function testGetRequestBooleanWithStringParameterThrowsException()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithBoolean('This is not a boolean');
      
        $this->assertEquals(true, $response);
    }

    public function testGetRequestArrayWithArrayParameter()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithArray(array('key' => 'value'));
      
        $this->assertEquals(true, $response);
    }

    /**
     * @expectedException EchoNest_Api_ParameterException
     */
    public function testGetRequestArrayWithStringParameterThrowsException()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithArray('string');
      
        $this->assertEquals(true, $response);
    }

    public function testGetRequestArrayOrStringWithArrayParameter()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithArrayOrString(array('key' => 'value'));
        
        $this->assertEquals(true, $response);
    }

    /**
     * @expectedException EchoNest_Api_ParameterException
     */
    public function testGetRequestArrayOrStringWithIntegerParameterThrowsException()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithArrayOrString(15);
    }

    public function testGetRequestDecimalWithDecimalParameter()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithDecimal(1.2);
      
        $this->assertEquals(true, $response);
    }
    
    public function testGetRequestDecimalWithStringDecimalParameter()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithDecimal('1.2');
      
        $this->assertEquals(true, $response);
    }
    
    /**
     * @expectedException EchoNest_Api_ParameterException
     */
    public function testGetRequestDecimalWithStringParameterThrowsException()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithDecimal('This is not a decimal');
    }
    
    public function testGetRequestOptionsWithCorrectParameterTypes()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithOptions(array('integer' => 1, 'string' => 'string'));
      
        $this->assertEquals(true, $response);
    }
    
    /**
     * @expectedException EchoNest_Api_ParameterException
     */
    public function testGetRequestOptionsWithIncorrectParameterTypeThrowsException()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithOptions(array('integer' => 'This is not an integer', 'string' => 'string'));
    }
    
    /**
     * @expectedException EchoNest_Api_ParameterException
     */
    public function testGetRequestOptionsWithInvalidParameterThrowsException()
    {
        $api = $this->getApiMock();
      
        $response = $api->getWithOptions(array('integer' => 2, 'string' => 'string', 'extra_parameter' => true));
    }
    
    protected function getApiMock()
    {
        return new EchoNest_Api_Test(new EchoNest_TestClient());
    }
}

/**
* 
*/
class EchoNest_Api_Test extends EchoNest_Api
{
    public function getWithInteger($integer)
    {
        $this->setValidation(array('integer' => self::INT));

        return $this->get('test/test', array('integer' => $integer));
    }
    
    public function getWithArray($array)
    {
        $this->setValidation(array('array' => self::ARR));

        return $this->get('test/test', array('array' => $array));
    }
    
    public function getWithArrayOrString($array_or_string)
    {
        $this->setValidation(array('array_or_string' => self::ARR | self::STR));

        return $this->get('test/test', array('array_or_string' => $array_or_string));
    }
    
    
    public function getWithBoolean($boolean)
    {
        $this->setValidation(array('boolean' => self::BOOL));

        return $this->get('test/test', array('boolean' => $boolean));
    }
    
    
    public function getWithDecimal($decimal)
    {
        $this->setValidation(array('decimal' => self::DEC));

        return $this->get('test/test', array('decimal' => $decimal));
    }
    
    public function getWithOptions($options)
    {
        $this->setValidation(array('options' => array('integer' => self::INT, 'string' => self::STR)));

        return $this->get('test/test', array('options' => $options));
    }
}

/**
* 
*/
class EchoNest_TestClient extends EchoNest_Client
{
    public function get($path, array $parameters = array(), array $options = array())
    {
        return true;
    }
    
    public function post($path, array $parameters = array(), array $options = array())
    {
        return true;
    }
}



