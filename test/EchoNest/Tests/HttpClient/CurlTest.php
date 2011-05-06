<?php

class EchoNest_Tests_HttpClient_CurlTest extends PHPUnit_Framework_TestCase
{
    public function testDoRequest()
    {
        $url       = 'http://site.com/some/path';
        $curlResponse = array('headers' => array('http_code' => 200), 'response' => '{"response": "hi there"}', 'errorNumber' => '', 'errorMessage' => '');
        $options = array('format' => 'json');

        $httpClient = $this->getHttpClientCurlMockBuilder()
            ->setMethods(array('doCurlCall'))
            ->getMock();
        $httpClient->expects($this->once())
            ->method('doCurlCall')
            ->will($this->returnValue($curlResponse));

        $responseText = $httpClient->get($url, array(), $options);

        $this->assertEquals('hi there', $responseText);
    }

    public function testDoAuthenticatedRequest()
    {
        $url       = 'http://site.com/some/path';
        $curlResponse = array('headers' => array('http_code' => 200), 'response' => '{"response": "hi there"}', 'errorNumber' => '', 'errorMessage' => '');
        $options = array('format' => 'json', 'api_key' => 'mykey');

        $httpClient = $this->getHttpClientCurlMockBuilder()
            ->setMethods(array('doCurlCall'))
            ->getMock();
        $httpClient->expects($this->once())
            ->method('doCurlCall')
            ->will($this->returnValue($curlResponse));

        $responseText = $httpClient->get($url, array(), $options);

        $this->assertEquals('hi there', $responseText);
    }

    public function testDoGetRequestWithParameters()
    {
        $url       = 'http://site.com/some/path';
        $curlResponse = array('headers' => array('http_code' => 200), 'response' =>'{"response": "hi there"}', 'errorNumber' => '', 'errorMessage' => '');
        $params = array('a' => 'b');
        $options = array('format' => 'json');

        $httpClient = $this->getHttpClientCurlMockBuilder()
            ->setMethods(array('doCurlCall'))
            ->getMock();
        $httpClient->expects($this->once())
            ->method('doCurlCall')
            ->will($this->returnValue($curlResponse));

        $responseText = $httpClient->get($url, $params, $options);

        $this->assertEquals('hi there', $responseText);
    }

    public function testDoPostRequestWithParameters()
    {
        $url       = 'http://site.com/some/path';
        $curlResponse = array('headers' => array('http_code' => 200), 'response' => '{"response": "hi there"}', 'errorNumber' => '', 'errorMessage' => '');
        $params = array('a' => 'b');
        $options = array('format' => 'json');

        $httpClient = $this->getHttpClientCurlMockBuilder()
            ->setMethods(array('doCurlCall'))
            ->getMock();
        $httpClient->expects($this->once())
            ->method('doCurlCall')
            ->will($this->returnValue($curlResponse));

        $responseText = $httpClient->post($url, $params, $options);

        $this->assertEquals('hi there', $responseText);
    }

    protected function getHttpClientCurlMockBuilder()
    {
        return $this->getMockBuilder('EchoNest_HttpClient_Curl');
    }
}
