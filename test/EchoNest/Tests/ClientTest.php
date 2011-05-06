<?php

class EchoNest_Tests_ClientTest extends PHPUnit_Framework_TestCase
{
    public function testInstanciateWithoutHttpClient()
    {
        $client = new EchoNest_Client();

        $this->assertInstanceOf('EchoNest_HttpClientInterface', $client->getHttpClient());
    }

    public function testInstanciateWithHttpClient()
    {
        $httpClient = $this->getHttpClientMock();
        $client = new EchoNest_Client($httpClient);

        $this->assertEquals($httpClient, $client->getHttpClient());
    }

    public function testAuthenticate()
    {
        $key    = 'mykey';

        $httpClient = $this->getHttpClientMock();
        $httpClient->expects($this->once())
            ->method('setOption')
            ->will($this->returnValue($httpClient));

        $client = $this->getClientMockBuilder()
            ->setMethods(array('getHttpClient'))
            ->getMock();
        $client->expects($this->once())
            ->method('getHttpClient')
            ->with()
            ->will($this->returnValue($httpClient));

        $client->authenticate($key);
    }

    public function testDeauthenticate()
    {
        $client = $this->getClientMockBuilder()
            ->setMethods(array('authenticate'))
            ->getMock();
        $client->expects($this->once())
            ->method('authenticate')
            ->with(null);

        $client->deAuthenticate();
    }

    public function testGet()
    {
        $path      = '/some/path';
        $parameters = array('a' => 'b');
        $options    = array('c' => 'd');

        $httpClient = $this->getHttpClientMock();
        $httpClient->expects($this->once())
            ->method('get')
            ->with($path, $parameters, $options);

        $client = $this->getClientMockBuilder()
            ->setMethods(array('getHttpClient'))
            ->getMock();
        $client->expects($this->once())
            ->method('getHttpClient')
            ->with()
            ->will($this->returnValue($httpClient));

        $client->get($path, $parameters, $options);
    }

    public function testPost()
    {
        $path      = '/some/path';
        $parameters = array('a' => 'b');
        $options    = array('c' => 'd');

        $httpClient = $this->getHttpClientMock();
        $httpClient->expects($this->once())
            ->method('post')
            ->with($path, $parameters, $options);

        $client = $this->getClientMockBuilder()
            ->setMethods(array('getHttpClient'))
            ->getMock();
        $client->expects($this->once())
            ->method('getHttpClient')
            ->with()
            ->will($this->returnValue($httpClient));

        $client->post($path, $parameters, $options);
    }

    public function testDefaultApi()
    {
        $client = new EchoNest_Client();

        $this->assertInstanceOf('EchoNest_Api_Artist', $client->getArtistApi());
    }

    public function testInjectApi()
    {
        $client = new EchoNest_Client();

        $artistApiMock = $this->getMockBuilder('EchoNest_ApiInterface')
            ->getMock();

        $client->setApi('artist', $artistApiMock);

        $this->assertSame($artistApiMock, $client->getArtistApi());
    }

    protected function getClientMockBuilder()
    {
        return $this->getMockBuilder('EchoNest_Client')
            ->disableOriginalConstructor();
    }

    protected function getHttpClientMock()
    {
        return $this->getMockBuilder('EchoNest_HttpClientInterface')
            ->getMock();
    }
}
