<?php

namespace HeavenProject\AssetLoader\Helpers;

use Nette;
use Nette\Utils\ArrayHash;
use Nette\Utils\FileSystem;
use HeavenProject\Utils\Slugger;

/**
 * Asset loader helper.
 */
class Loader extends Nette\Object
{
    /** @var string */
    private $wwwDir;

    /** @var string */
    private $saveDir;

    /**
     * @param string $wwwDir
     * @param string $saveDir
     */
    public function __construct($wwwDir, $saveDir)
    {
        $this->wwwDir = $wwwDir;
        $this->saveDir = $saveDir;
    }

    /**
     * @param string $asset file
     *
     * @return string
     */
    public function load($asset)
    {
        if (!file_exists($this->saveDir)) {
            FileSystem::createDir($this->saveDir);
        }

        if (!file_exists($this->wwwDir.'/'.$asset)) {
            return $asset;
        }

        $slug = Slugger::slugify($asset);
        $file = $this->saveDir.'/'.$slug;

        if (file_exists($file)) {
            $str = file_get_contents($file);
            $obj = unserialize($str);

            $currTime = filemtime($this->wwwDir.$asset);
            $lastTime = $obj->timestamp;
            if ($currTime === $lastTime) {
                return $this->getUrl($asset, $obj->version);
            }

            $obj->timestamp = $currTime;
            $obj->version = $obj->version + 1;
            $this->save($obj, $file);

            return $this->getUrl($asset, $obj->version);
        }

        $obj = new ArrayHash();
        $obj->timestamp = filemtime($this->wwwDir.$asset);
        $obj->version = 1;
        $this->save($obj, $file);

        return $this->getUrl($asset, $obj->version);
    }

    /**
     * @param ArrayHash $object
     * @param string    $file
     */
    private function save(ArrayHash $object, $file)
    {
        $str = serialize($object);
        FileSystem::write($file, $str);
    }

    /**
     * @param string $file
     * @param int    $version
     *
     * @return string
     */
    private function getUrl($file, $version)
    {
        return $file.'?v='.$version;
    }
}
