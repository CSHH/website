<?php

namespace AppTests;

use Mockery as m;
use Nette;

trait Login
{
    public function signIn(Nette\DI\Container $container)
    {
        $container->removeService('nette.userStorage');

        $userStorage = m::mock('Nette\Security\IUserStorage');
        $userStorage->shouldReceive('isAuthenticated')->once()->andReturn(true);
        $userStorage->shouldReceive('getIdentity')->once()->andReturn(new Nette\Security\Identity(1));

        $container->addService('nette.userStorage', $userStorage);
    }
}
