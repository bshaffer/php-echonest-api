<?php

require_once(dirname(__FILE__).'/EchoNestApiRequestException.php');

/**
 * Performs requests on GitHub API. API documentation should be self-explanatory.
 *
 * @author    Brent Shaffer <bshafs at gmail dot com>
 * @license   MIT License
 */
class EchoNestApiRequest
{
  /**
   * The request options
   * @var array
   */
  protected $options = array(
    'protocol'    => 'http',
    'api_version' => 'v4',
    'url'         => ':protocol://developer.echonest.com/api/:api_version/:path',
    'user_agent'  => 'php-echonest-api (http://github.com/bshaffer/php-echonest-api)',
    'http_port'   => 80,
    'timeout'     => 20,
    'api_key'     => null,
    'format'      => 'json',
    'limit'       => false,
    'debug'       => false
  );

  /**
   * Instanciate a new request
   *
   * @param  array   $options  Request options
   */
  public function __construct(array $options = array())
  {
    $this->configure($options);
  }

  /**
   * Configure the request
   *
   * @param   array               $options  Request options
   * @return  EchoNestApiRequest $this     Fluent interface
   */
  public function configure(array $options)
  {
    $this->options = array_merge($this->options, $options);

    return $this;
  }

  /**
   * Send a request to the server, receive a response,
   * decode the response and returns an associative array
   *
   * @param  string   $apiPath        Request API path
   * @param  array    $parameters     Parameters
   * @param  string   $httpMethod     HTTP method to use
   * @param  array    $options        reconfigure the request for this call only
   *
   * @return array                    Data
   */
  public function send($apiPath, array $parameters = array(), $httpMethod = 'GET', array $options = array())
  {
    if(!empty($options))
    {
      $initialOptions = $this->options;
      $this->configure($options);
    }
    
    $response = $this->decodeResponse($this->doSend($apiPath, $parameters, $httpMethod));

    if(isset($initialOptions))
    {
      $this->options = $initialOptions;
    }

    return $response['response'];
  }

  /**
   * Send a GET request
   * @see send
   */
  public function get($apiPath, array $parameters = array(), array $options = array())
  {
    return $this->send($apiPath, $parameters, 'GET', $options);
  }

  /**
   * Send a POST request
   * @see send
   */
  public function post($apiPath, array $parameters = array(), array $options = array())
  {
    return $this->send($apiPath, $parameters, 'POST', $options);
  }

  /**
   * Get a JSON response and transform it to a PHP array
   *
   * @return  array   the response
   */
  protected function decodeResponse($response)
  {
    switch ($this->options['format']) 
    {
      case 'json':
        return json_decode($response, true);

      case 'jsonp':
        throw new Exception("format 'jsonp' not yet supported by this library");

      case 'xml':
        throw new Exception("format 'xml' not yet supported by this library");

      case 'xspf':
        throw new Exception("format 'xspf' not yet supported by this library");
    }

    throw new Exception(__CLASS__.' only supports json, json, xml, and xspf formats, '.$this->options['format'].' given.');
  }

  /**
   * Send a request to the server, receive a response
   *
   * @param  string   $apiPath       Request API path
   * @param  array    $parameters    Parameters
   * @param  string   $httpMethod    HTTP method to use
   *
   * @return string   HTTP response
   */
  public function doSend($apiPath, array $parameters = array(), $httpMethod = 'GET')
  {
    $url = strtr($this->options['url'], array(
      ':api_version' => $this->options['api_version'],
      ':protocol'    => $this->options['protocol'],
      ':path'        => trim($apiPath, '/')
    ));

    if($this->options['api_key'])
    {
      $parameters = array_merge(array(
        'format'  => $this->options['format'],
        'api_key' => $this->options['api_key']
      ), $parameters);
    }

    $curlOptions = array();

    if (!empty($parameters))
    {
      $queryString = utf8_encode($this->buildQuery($parameters));

      if('GET' === $httpMethod)
      {
        $url .= '?' . $queryString;
      }
      else
      {
        $curlOptions += array(
          CURLOPT_POST        => true,
          CURLOPT_POSTFIELDS  => $queryString
        );
      }
    }

    $this->debug('send '.$httpMethod.' request: '.$url);

    $curlOptions += array(
      CURLOPT_URL             => $url,
      CURLOPT_PORT            => $this->options['http_port'],
      CURLOPT_USERAGENT       => $this->options['user_agent'],
      CURLOPT_FOLLOWLOCATION  => true,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_TIMEOUT         => $this->options['timeout']
    );

    $curl = curl_init();

    curl_setopt_array($curl, $curlOptions);

    $response     = curl_exec($curl);
    $headers      = curl_getinfo($curl);
    $errorNumber  = curl_errno($curl);
    $errorMessage = curl_error($curl);

    curl_close($curl);

    if (!in_array($headers['http_code'], array(0, 200, 201)))
    {
      throw new EchoNestApiRequestException(null, (int) $headers['http_code']);
    }

    if ($errorNumber != '')
    {
      throw new EchoNestApiRequestException($errorMessage, $errorNumber);
    }

    return $response;
  }

  /**
   * Change an option value.
   *
   * @param string $name   The option name
   * @param mixed  $value  The value
   *
   * @return dmConfigurable The current object instance
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
  
  protected function buildQuery($parameters)
  {
    $append = '';
    foreach ($parameters as $key => $value) 
    {
      // multiple parameter passed
      if (is_array($value)) 
      {
        foreach ($value as $val) {
          $append.=sprintf('&%s=%s', $key, $val);
        }
        unset($parameters[$key]);
      }
    }
     
    return http_build_query($parameters, '', '&') . $append;
  }

  protected function debug($message)
  {
    if($this->options['debug'])
    {
      print $message."\n";
    }
  }
}
