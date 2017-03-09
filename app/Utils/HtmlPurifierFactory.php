<?php

namespace App\Utils;

class HtmlPurifierFactory
{
    /** @var \HTMLPurifier_Config */
    private $config;

    public function __construct()
    {
        $this->config = \HTMLPurifier_Config::createDefault();
        $this->config->set('HTML.Allowed', 'p,h2,h3,h4,h5,h6,strong,em,u,strike,a[href],ul,ol,li,img[src|alt]');
    }

    /**
     * @return \HTMLPurifier
     */
    public function createHtmlPurifier()
    {
        return new \HTMLPurifier($this->config);
    }
}
