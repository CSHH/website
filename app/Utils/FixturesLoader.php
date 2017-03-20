<?php

namespace App\Utils;

use Zenify\DoctrineFixtures\Contract\Alice\AliceLoaderInterface;

class FixturesLoader
{
    /** @var string */
    private $fixturesDir;

    /** @var AliceLoaderInterface */
    private $aliceLoader;

    /**
     * @param string               $fixturesDir
     * @param AliceLoaderInterface $aliceLoader
     */
    public function __construct($fixturesDir, AliceLoaderInterface $aliceLoader)
    {
        $this->fixturesDir = $fixturesDir;
        $this->aliceLoader = $aliceLoader;
    }

    public function loadFixtures()
    {
        $this->aliceLoader->load($this->fixturesDir);
    }
}
