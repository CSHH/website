<?php

use Tracy\Debugger as Dbg;

/**
 * @param string $message
 * @param string $type
 */
function dlog($message, $type = Dbg::EXCEPTION)
{
    Dbg::barDump($message);
    Dbg::log($message, $type);
}
