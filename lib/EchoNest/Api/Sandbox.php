<?php


Class SignatureCreation {

  public static function oauth_hmacsha1($key, $data) {
      return base64_encode(self::hmacsha1($key, $data));
  }

  public static function hmacsha1($key,$data) {
      $blocksize=64;
      $hashfunc='sha1';
      if (strlen($key)>$blocksize)
          $key=pack('H*', $hashfunc($key));
      $key=str_pad($key,$blocksize,chr(0x00));
      $ipad=str_repeat(chr(0x36),$blocksize);
      $opad=str_repeat(chr(0x5c),$blocksize);
      $hmac = pack(
                  'H*',$hashfunc(
                      ($key^$opad).pack(
                          'H*',$hashfunc(
                              ($key^$ipad).$data
                          )
                      )
                  )
              );
      return $hmac;
  }

  public static function oauth_signature($url,$params, $secret) {

    // sort the parameters
    ksort($params);

    $param_string = "";
    $i = 0;
    foreach ($params as $k => $v) {
      if ($i != 0) {
        $param_string .= "&";
      }
      if (is_array($v)) {
        $j = 0;
        foreach($v as $key => $val) {
          if ($j != 0) {
            $param_string .= "&"; 
          }
          $param_string .= $k . "=" . $val;
          $j++;
        }
      } else {
        $param_string .= $k . "=" . $v;
      }
      $i++;
    }


    echo $param_string."<br/>";  
    
    $data =  "GET&".urlencode($url). "&".urlencode($param_string);


    $key = $secret."&";

    $sig = self::oauth_hmacsha1($key, $data);
    return  $sig;
  }
}


/**
 * API calls for managing sandboxes
 *
 * @link      http://developer.echonest.com/docs/v4/catalog.html#overview
 * @author    Syd Lawrence <sydlawrence at gmail dot com>
 * @license   MIT License
 */
class EchoNest_Api_Sandbox extends EchoNest_Api
{

  protected $sandbox = "";

  protected $oauth_config = array
  (
    "consumer_key" => "",
    "consumer_secret" => "",
  );

  function setSandbox($sandbox)
  {
    if ($sandbox)
      $this->sandbox = $sandbox;
    return $this;
  }

  function setConfig($oauth_config)
  {
    foreach ($this->oauth_config as $key => $val) {

      if (!isset($oauth_config[$key]) || $oauth_config[$key] == "") {
        // @todo make this thrown an exception
        throw new Exception('Missing sandbox oauth config: '.$key);
      }
      $this->oauth_config[$key] = $oauth_config[$key];
    }
    return $this;

  }


  /**
   * Lists assets in a sandbox.
   * http://developer.echonest.com/docs/v4/catalog.html#create
   *
   * @param   string  $name         The name of the catalog
   * @param   string  $type         The type of the catalog (artist or song)
   * @return  array                 response object
   */
  function assets($start = 0, $per_page=100)
  {

    $response = $this->client->get('sandbox/list', array(
      'sandbox'    => $this->sandbox,
      'results'    => $per_page,
      'start'      => $start
    ));

    return $this->returnResponse($response);
  }

  function fetch($id)
  {

    $endpoint = "sandbox/access";

    $time = time();
    $params = array(
      "api_key" => $this->client->getHttpClient()->getOption('api_key'),
      "id" => $id,
      "oauth_nonce" => md5($time),
      "oauth_timestamp" => $time,
      "format" => $this->client->getHttpClient()->getOption('format'),
      "oauth_signature_method" => "HMAC-SHA1",
      "oauth_version" => "1.0",
      "oauth_consumer_key" => $this->oauth_config['consumer_key'],
      "sandbox" => $this->sandbox
    );

    $url = strtr($this->client->getHttpClient()->getOption('url'), array(
      ':api_version' => $this->client->getHttpClient()->getOption('api_version'),
      ':protocol'    => $this->client->getHttpClient()->getOption('protocol'),
      ':path'        => trim($endpoint, '/')
    ));

    $sig = SignatureCreation::oauth_signature($url,$params, $this->oauth_config['consumer_secret']);

    $params['oauth_signature'] = $sig;


    $response = $this->client->get($endpoint, $params);

    return $this->returnResponse($response);

  }
}
