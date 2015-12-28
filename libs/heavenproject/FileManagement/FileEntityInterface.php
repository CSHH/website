<?php

namespace HeavenProject\FileManagement;

interface FileEntityInterface
{
    /**
     * @param string $year
     */
    public function setYear($year);

    /**
     * @return string
     */
    public function getYear();

    /**
     * @param string $month
     */
    public function setMonth($month);

    /**
     * @return string
     */
    public function getMonth();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $extension
     */
    public function setExtension($extension);

    /**
     * @return string
     */
    public function getExtension();

    /**
     * @param string $checksum
     */
    public function setChecksum($checksum);

    /**
     * @return string
     */
    public function getChecksum();

    /**
     * @param string $joints
     */
    public function setJoints($joints);

    /**
     * @return int
     */
    public function getJoints();
}
