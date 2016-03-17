<?php

namespace App\Model\Videos;

use App\Model\Entities\VideoEntity;
use HeavenProject\Utils\Slugger;

class VideoThumbnail
{
    /** @var string */
    private $wwwDir;

    /** @var string */
    private $videoThumbnailsDir;

    /** @var string */
    private $vimeoOembedEndpoint;

    /**
     * @param string $wwwDir
     * @param string $videoThumbnailsDir
     * @param string $vimeoOembedEndpoint
     */
    public function __construct($wwwDir, $videoThumbnailsDir, $vimeoOembedEndpoint)
    {
        $this->wwwDir              = $wwwDir;
        $this->videoThumbnailsDir  = $videoThumbnailsDir;
        $this->vimeoOembedEndpoint = $vimeoOembedEndpoint;
    }

    /**
     * @param  VideoEntity $video
     * @return string
     */
    public function getThumbnailImage(VideoEntity $video)
    {
        switch ($video->type) {
            case VideoEntity::TYPE_YOUTUBE:
                return $this->getYoutubeVideoThumbnail($video);

            case VideoEntity::TYPE_VIMEO:
                return $this->getVimeoVideoThumbnail($video);

            default;
                return '';
        }
    }

    /**
     * @param VideoEntity $video
     */
    private function getYoutubeVideoThumbnail(VideoEntity $video)
    {
        $filePath = $this->videoThumbnailsDir . '/' . Slugger::slugify($video->url);

        if (!file_exists($filePath)) {
            $youtubeVideoId = substr($video->url, strpos($video->url, '?v=') + 3);
            $videoThumbnail = file_get_contents('https://i.ytimg.com/vi/' . $youtubeVideoId . '/hqdefault.jpg');
            file_put_contents($this->wwwDir . $filePath, $videoThumbnail);
        }

        return $filePath;
    }

    /**
     * @param VideoEntity $video
     */
    private function getVimeoVideoThumbnail(VideoEntity $video)
    {
        $filePath = $this->videoThumbnailsDir . '/' . Slugger::slugify($video->url);

        if (!file_exists($filePath)) {
            $url = $this->vimeoOembedEndpoint . '?url=' . rawurlencode($video->url);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            $xml = simplexml_load_string($result);

            $videoThumbnail = file_get_contents((string) $xml->thumbnail_url);
            file_put_contents($this->wwwDir . $filePath, $videoThumbnail);
        }

        return $filePath;
    }
}
