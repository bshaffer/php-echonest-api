<?php

/**
 * Simple PHP GitHub API
 * 
 * @tutorial  http://github.com/ornicar/php-github-api/blob/master/README.markdown
 * @version   2.6
 * @author    Brent Shaffer <bshafs at gmail dot com>
 * @license   MIT License
 *
 * Website: http://github.com/ornicar/php-github-api
 * Tickets: http://github.com/ornicar/php-github-api/issues
 */
class EchoNestApi
{
  /**
   * The request instance used to communicate with GitHub
   * @var EchoNestApiRequest
   */
  protected $request  = null;
  
  /**
   * The list of loaded API instances
   * @var array
   */
  protected $apis     = array();

  /**
   * Use debug mode (prints debug messages)
   * @var bool
   */
  protected $debug;

  /**
   * Instanciate a new GitHub API
   *
   * @param  bool           $debug      print debug messages
   */
  public function __construct($debug = false)
  {
    $this->debug = $debug;
  }

  /**
   * Authenticate a user for all next requests
   *
   * @param  string         $apiKey      EchoNest API key
   * @return EchoNestApi               fluent interface
   */
  public function authenticate($apiKey)
  {
    $this->getRequest()
    ->setOption('api_key', $apiKey);

    return $this;
  }

  /**
   * Deauthenticate a user for all next requests
   *
   * @return EchoNestApi               fluent interface
   */
  public function deAuthenticate()
  {
    return $this->authenticate(null);
  }
  
  /**
   * Call any route, GET method
   * Ex: $api->get('repos/show/my-username/my-repo')
   *
   * @param   string  $route            the GitHub route
   * @param   array   $parameters       GET parameters
   * @param   array   $requestOptions   reconfigure the request
   * @return  array                     data returned
   */
  public function get($route, array $parameters = array(), $requestOptions = array())
  {
    return $this->getRequest()->get($route, $parameters, $requestOptions);
  }

  /**
   * Call any route, POST method
   * Ex: $api->post('repos/show/my-username', array('email' => 'my-new-email@provider.org'))
   *
   * @param   string  $route            the GitHub route
   * @param   array   $parameters       POST parameters
   * @param   array   $requestOptions   reconfigure the request
   * @return  array                     data returned
   */
  public function post($route, array $parameters = array(), $requestOptions = array())
  {
    return $this->getRequest()->post($route, $parameters, $requestOptions);
  }

  /**
   * Get the request
   *
   * @return  EchoNestApiRequest   a request instance
   */
  public function getRequest()
  {
    if(!isset($this->request))
    {
      require_once(dirname(__FILE__).'/request/EchoNestApiRequest.php');
      $this->request = new EchoNestApiRequest(array(
        'debug' => $this->debug
      ));
    }
    
    return $this->request;
  }

  /**
   * Inject another request
   *
   * @param   EchoNestApiRequest   a request instance
   * @return  EchoNestApi          fluent interface
   */
  public function setRequest(EchoNestApiRequest $request)
  {
    $this->request = $request;

    return $this;
  }

  /**
   * Get the artist API
   *
   * @return  EchoNestApiArtist    the artist API
   */
  public function getArtistApi()
  {
    if(!isset($this->apis['artist']))
    {
      require_once(dirname(__FILE__).'/api/EchoNestApiArtist.php');
      $this->apis['artist'] = new EchoNestApiArtist($this);
    }

    return $this->apis['artist'];
  }

  /**
   * Get the song API
   *
   * @return  EchoNestApiSong   the song API
   */
  public function getSongApi()
  {
    if(!isset($this->apis['song']))
    {
      require_once(dirname(__FILE__).'/api/EchoNestApiSong.php');
      $this->apis['song'] = new EchoNestApiSong($this);
    }

    return $this->apis['song'];
  }

  /**
   * Get the track API
   *
   * @return  EchoNestApiTrack  the track API
   */
  public function getCommitApi()
  {
    if(!isset($this->apis['track']))
    {
      require_once(dirname(__FILE__).'/api/EchoNestApiTrack.php');
      $this->apis['track'] = new EchoNestApiTrack($this);
    }

    return $this->apis['track'];
  }

  /**
   * Get the playlist API
   *
   * @return  EchoNestApiPlaylist  the playlist API
   */
  public function getPlaylistApi()
  {
    if(!isset($this->apis['playlist']))
    {
      require_once(dirname(__FILE__).'/api/EchoNestApiPlaylist.php');
      $this->apis['playlist'] = new EchoNestApiPlaylist($this);
    }

    return $this->apis['playlist'];
  }

  /**
   * Get the catalog API
   *
   * @return  EchoNestApiCatalog  the catalog API
   */
  public function getCatalogApi()
  {
    if(!isset($this->apis['catalog']))
    {
      require_once(dirname(__FILE__).'/api/EchoNestApiCatalog.php');
      $this->apis['catalog'] = new EchoNestApiCatalog($this);
    }

    return $this->apis['catalog'];
  }

  /**
   * Inject another API instance
   *
   * @param   string                $name the API name
   * @param   EchoNestApiAbstract  $api  the API instance
   * @return  EchoNestApi                fluent interface
   */
  public function setApi($name, EchoNestApiAbstract $instance)
  {
    $this->apis[$name] = $instance;

    return $this;
  }

  /**
   * Get any API
   *
   * @param   string                $name the API name
   * @return  EchoNestApiAbstract        the API instance
   */
  public function getApi($name)
  {
    return $this->apis[$name];
  }
}