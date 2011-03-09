<?php

/**
 * Abstract class for EchoNest_Api classes
 *
 * @author    Brent Shaffer <bshafs at gmail dot com>
 * @license   MIT License
 */
abstract class EchoNest_Api
{
  /**
   * The core EchoNest Client
   * @var EchoNest_Client
   */
  protected 
    $client,
    $options = array();

  public function __construct(EchoNest_Client $client, $options = array())
  {
    $this->client  = $client;
    $this->options = $options;
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
}
