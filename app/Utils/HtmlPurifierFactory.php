<?php

namespace App\Utils;

class HtmlPurifierFactory
{
    /**
     * @return \HTMLPurifier
     */
    public static function createHtmlPurifier()
    {
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,h2,h3,h4,h5,h6,strong,em,u,strike,a[href],ul,ol,li,img[src|alt]');

        return new \HTMLPurifier($config);
    }
}
