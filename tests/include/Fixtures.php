<?php

namespace AppTests;

use Nelmio;
use Nette;

trait Fixtures
{
    /**
     * @param Nette\DI\Container $container
     * @param string             $fixtures
     */
    public function applyFixtures(Nette\DI\Container $container, $fixtures)
    {
        $entityManager = $container->getByType('Kdyby\Doctrine\EntityManager');
        Nelmio\Alice\Fixtures::load($fixtures, $entityManager);
    }
}
