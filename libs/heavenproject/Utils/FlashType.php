<?php

namespace HeavenProject\Utils;

use Nette;

/**
 * Flash message type.
 */
class FlashType extends Nette\Object
{
    use StaticClass;

    /** @var string */
    const SUCCESS = 'success';
    /** @var string */
    const INFO = 'info';
    /** @var string */
    const WARNING = 'warning';
    /** @var string */
    const DANGER = 'danger';
}
