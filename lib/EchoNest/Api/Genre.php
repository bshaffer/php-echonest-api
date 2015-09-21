<?php
/**
 * Api calls getting data about genres.
 * @link http://http://developer.echonest.com/docs/v4/
 */
class EchoNest_Api_Genre extends EchoNest_Api {
  /**
   * Returns a list of all of the available genres.
   * @param   array $options - See the EchoNest documentation for a list of options
   * @return  array - Array of genres
   */
  public function getList(array $options = array()) {
    $response = $this->client->get('genre/list', $options);
    return $this->returnResponse($response, 'genres');
  }  

  /**
   * Search for genres
   * @param   array $options - See the EchoNest documentation for a list of options
   * @return  array - Array of genres
   */
  public function search(array $options = array()) {
    $response = $this->client->get('genre/search', $options);
    return $this->returnResponse($response, 'genres');
  }
}