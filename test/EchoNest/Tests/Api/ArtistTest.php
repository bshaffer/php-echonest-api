<?php

class EchoNest_Tests_Api_ArtistTest extends EchoNest_Tests_ApiTest
{
    /**
     * @expectedException Exception
     */
    public function testGetAudioWithoutArtistNameOrIdThrowsException()
    {
        $api = $this->getApiMock();

        $api->getAudio();
    }

    public function testGetAudioWithArtistName()
    {
        $api = $this->getApiMock();

        $mockResponse = array('audio' => array(0 => 'The Good Kind', 1 => 'Flies'));

        $api->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockResponse));

        $response = $api->setName('More Hazards More Heroes')->getAudio();

        $this->assertEquals($response, $mockResponse['audio']);
    }

    public function testRawOptionReturnsRawResponse()
    {
        $api = $this->getApiMock();

        $mockResponse = array('audio' => array(0 => 'Romans', 1 => 'Windburnt Soul'));

        $api->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockResponse));

        $api->setOption('raw', true);
        $response = $api->setName('More Hazards More Heroes')->getAudio();

        $this->assertEquals($response, $mockResponse);
    }

    public function testSearch()
    {
        $api = $this->getApiMock();

        $mockResponse = array('audio' => array(0 => 'The Good Kind', 1 => 'Flies'));

        $api->expects($this->once())
            ->method('get')
            ->will($this->returnValue($mockResponse));

        $response = $api->setName('More Hazards More Heroes')->getAudio();

        $this->assertEquals($response, $mockResponse['audio']);
    }

    protected function getApiClass()
    {
        return 'EchoNest_Api_Artist';
    }
}
