<?php

namespace App\Emails;

use Nette\Mail\Message;

class EmailMessageFactory
{
    /**
     * @return Message
     */
    public function createMessage()
    {
        return new Message;
    }
}
