<?php

namespace App\Model\Utils;

class InputTextPurifier
{
    /** @var \HTMLPurifier */
    private $htmlPurifier;

    public function __construct()
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Attr.AllowedClasses', array());

        $this->htmlPurifier = new \HTMLPurifier($config);
    }

    /**
     * @param  string $input
     * @return string
     */
    public function purify($input)
    {
        return $this->htmlPurifier->purify($input);
    }
}
