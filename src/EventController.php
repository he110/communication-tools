<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 16/08/2019
 * Time: 01:46
 */

namespace He110\CommunicationTools;


final class EventController
{
    /** @var EventController|null */
    private static $instance = null;

    /** @var array */
    private $events = [];

    /**
     * @return EventController
     */
    public static function getInstance(): EventController
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param string $key
     * @param \Closure $closure
     */
    public function addEvent(string $key, \Closure $closure)
    {
        $this->events[$key] = $closure;
    }

    /**
     * @param string $key
     * @return \Closure|null
     */
    public function getEvent(string $key): ?\Closure
    {
        return isset($this->events[$key]) && is_callable($this->events[$key]) ? $this->events[$key] : null;
    }

    // @codeCoverageIgnoreStart
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
    // @codeCoverageIgnoreEnd
}