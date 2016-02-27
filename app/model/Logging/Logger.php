<?php

namespace App\Model\Logging;

use Tracy\Debugger;

class Logger
{
    /**
     * @param string $message
     * @param string $type
     */
    public static function log($message, $type = Debugger::EXCEPTION)
    {
        Debugger::barDump($message);
        Debugger::log($message, $type);
    }
}
