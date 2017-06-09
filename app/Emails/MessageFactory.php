<?php

namespace App\Emails;

use Nette\Mail\Message;

class MessageFactory
{
    /**
     * @return Message
     */
    public static function createMessage()
    {
        return new Message;
    }
}
