<?php

namespace App\Model\Utils;

class InputTextPurifier
{
    /** @var \HTMLPurifier */
    private $htmlPurifier;

    public function __construct()
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,h2,h3,h4,h5,h6,strong,em,u,strike,a[href],ul,ol,li,img[src|alt]');

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
