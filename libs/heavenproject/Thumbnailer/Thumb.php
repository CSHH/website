<?php

namespace HeavenProject\Thumbnailer;

use Nette\Utils\Image;
use Nette\Utils\FileSystem;

/**
 * Creates thumbnail of an image.
 */
class Thumb
{
    /** @var int Percentage of thumbnail image quality */
    const QUALITY_LOW = 75,
        QUALITY_MEDIUM = 85,
        QUALITY_HIGH = 100;

    /** @var string Resulting file type */
    const TYPE_PNG = 'png',
        TYPE_GIF = 'gif',
        TYPE_JPEG = 'jpeg';

    /** @var string */
    private static $wwwDir;

    /** @var string */
    private static $thumbDir;

    /** @var Image */
    private static $image;

    /**
     * @param string $wwwDir
     */
    public static function setWwwDir($wwwDir)
    {
        static::$wwwDir = $wwwDir;
    }

    /**
     * @param string $thumbDir
     */
    public static function setThumbDir($thumbDir)
    {
        static::$thumbDir = $thumbDir;
    }

    /**
     * @param string $file
     * @param int    $width
     * @param int    $height
     * @param string $fileType
     * @param int    $quality  on percentage scale from 0 to 100 (only for JPEG and PNG)
     *
     * @return string Thumbnail image path
     */
    public static function thumbnalize($file, $width = null, $height = null, $fileType = self::TYPE_PNG, $quality = self::QUALITY_MEDIUM)
    {
        $file = static::$wwwDir.'/'.$file;
        if (!is_file($file)) {
            return;
        }

        switch ($fileType) {
            case self::TYPE_JPEG:
                $type = Image::JPEG;
                break;
            case self::TYPE_PNG:
                $type = Image::PNG;
                break;
            case self::TYPE_GIF:
                $type = Image::GIF;
                break;
            default:
                $type = Image::PNG;
                break;
        }

        $fileName = self::getFileName($file, $fileType);

        $destinationDir = $width.'_'.$height.'/'.$fileType.'/'.$quality;
        $thumbDir = static::$wwwDir.static::$thumbDir;

        if (file_exists($thumbDir.'/'.$destinationDir.'/'.$fileName)) {
            return static::$thumbDir.'/'.$destinationDir.'/'.$fileName;
        }

        static::$image = Image::fromFile($file);

        if (static::isWidthSet($width) && static::isHeightSet($height)) {
            static::resizeImageExactly($width, $height);
        } elseif (static::isWidthSet($width)) {
            static::resizeImageProportionally($width, $height);
        } else {
            static::resizeImageProportionally(static::$image->getWidth(), $height);
        }

        FileSystem::createDir($thumbDir.'/'.$destinationDir);
        static::$image->save($thumbDir.'/'.$destinationDir.'/'.$fileName, $quality, $type);

        return static::$thumbDir.'/'.$destinationDir.'/'.$fileName;
    }

    /**
     * @param int $width
     * @param int $height
     */
    private static function resizeImageExactly($width, $height)
    {
        static::$image->resize($width, $height, Image::EXACT);
    }

    /**
     * @param int $width
     * @param int $height
     */
    private static function resizeImageProportionally($width, $height)
    {
        static::$image->resize($width, $height, Image::FIT);
    }

    /**
     * @param string $originalFile
     * @param string $fileType
     *
     * @return string
     */
    private static function getFileName($originalFile, $fileType)
    {
        if (in_array($fileType, [self::TYPE_PNG, self::TYPE_GIF, self::TYPE_JPEG]) === false) {
            $fileType = self::TYPE_PNG;
        }

        $pair = explode('.', $originalFile);

        if (count($pair) > 1) {
            $ext = end($pair);
            $fileName = basename($originalFile, '.'.$ext);
        } else {
            $fileName = basename($originalFile);
        }

        $thumb = $fileName.'.'.$fileType;

        return $thumb;
    }

    /**
     * @param int $width
     *
     * @return bool
     */
    private static function isWidthSet($width)
    {
        if (!empty($width) && $width > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param int $height
     *
     * @return bool
     */
    private static function isHeightSet($height)
    {
        if (!empty($height) && $height > 0) {
            return true;
        }

        return false;
    }
}
