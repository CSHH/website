<?php

namespace App\Model\Duplicities;

/**
 * Exception that is thrown when trying to re-create a record with already existing unique key value.
 */
class PossibleUniqueKeyDuplicationException extends \Exception
{
}
