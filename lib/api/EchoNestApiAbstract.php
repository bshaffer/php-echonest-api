<?php

/**
 * Abstract class for EchoNestApi classes
 *
 * @author    Brent Shaffer <bshafs at gmail dot com>
 * @license   MIT License
 */
abstract class EchoNestApiAbstract
{
  /**
   * The core API
   * @var EchoNestApiAbstract
   */
  protected 
    $api,
    $options = array();

  public function __construct(EchoNestApi $api, $options = array())
  {
    $this->api     = $api;
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
