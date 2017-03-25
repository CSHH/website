<?php

namespace App\Videos;

use App\Entities\VideoEntity;
use HeavenProject\Utils\Slugger;

class VideoThumbnail
{
    /** @var string */
    private $wwwDir;

    /** @var string */
    private $videoThumbnailsDir;

    /** @var string */
    private $vimeoOembedEndpoint;

    /** @var string */
    private $defaultImage;

    /**
     * @param string $wwwDir
     * @param string $videoThumbnailsDir
     * @param string $vimeoOembedEndpoint
     * @param string $defaultImage
     */
    public function __construct($wwwDir, $videoThumbnailsDir, $vimeoOembedEndpoint, $defaultImage)
    {
        $this->wwwDir              = $wwwDir;
        $this->videoThumbnailsDir  = $videoThumbnailsDir;
        $this->vimeoOembedEndpoint = $vimeoOembedEndpoint;
        $this->defaultImage        = $defaultImage;
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

            default:
                return '';
        }
    }

    /**
     * @param  VideoEntity $video
     * @return string
     */
    private function getYoutubeVideoThumbnail(VideoEntity $video)
    {
        $filePath = $this->getFilePath($video);

        if (!file_exists($this->wwwDir . $filePath)) {
            $youtubeVideoId = substr($video->url, strpos($video->url, '?v=') + 3);
            $videoThumbnail = @file_get_contents('https://i.ytimg.com/vi/' . $youtubeVideoId . '/hqdefault.jpg');
            if ($videoThumbnail) {
                file_put_contents($this->wwwDir . $filePath, $videoThumbnail);
            }
        }

        return $this->getImage($filePath);
    }

    /**
     * @param  VideoEntity $video
     * @return string
     */
    private function getVimeoVideoThumbnail(VideoEntity $video)
    {
        $filePath = $this->getFilePath($video);

        if (!file_exists($this->wwwDir . $filePath)) {
            $url = $this->vimeoOembedEndpoint . '?url=' . rawurlencode($video->url);

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            $result = curl_exec($curl);
            curl_close($curl);

            $xml = @simplexml_load_string($result);
            if ($xml) {
                $videoThumbnail = file_get_contents((string) $xml->thumbnail_url);
                file_put_contents($this->wwwDir . $filePath, $videoThumbnail);
            }
        }

        return $this->getImage($filePath);
    }

    /**
     * @param  VideoEntity $video
     * @return string
     */
    private function getFilePath(VideoEntity $video)
    {
        return $this->videoThumbnailsDir . '/' . Slugger::slugify($video->url);
    }

    /**
     * @param  string $filePath
     * @return string
     */
    private function getImage($filePath)
    {
        return file_exists($this->wwwDir . $filePath) ? $filePath : $this->defaultImage;
    }
}
