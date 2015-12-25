<?php

namespace HeavenProject\Utils;

use Nette\StaticClassException;

/**
 * Converts given class to static one and forbids its instantiation.
 */
trait StaticClass
{
    /**
     * Static class - cannot be instantiated.
     *
     * @throws StaticClassException
     */
    final public function __construct()
    {
        throw new StaticClassException();
    }
}
