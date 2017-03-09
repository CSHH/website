<?php

namespace App\Videos;

class Vimeo
{
    /** @var string */
    private $vimeoOembedEndpoint;

    /**
     * @param string $vimeoOembedEndpoint
     */
    public function __construct($vimeoOembedEndpoint)
    {
        $this->vimeoOembedEndpoint = $vimeoOembedEndpoint;
    }

    /**
     * @param  string $pageUrl
     * @return string
     */
    public function getVideoSrc($pageUrl)
    {
        $url = $this->vimeoOembedEndpoint . '?url=' . rawurlencode($pageUrl);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($curl);
        curl_close($curl);

        $xml = simplexml_load_string($result);

        $iframe = (string) $xml->html;

        $part = substr($iframe, strpos($iframe, 'src="') + 5);

        return substr($part, 0, strpos($part, '"'));
    }
}
