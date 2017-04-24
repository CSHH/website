<?php

$container = require __DIR__ . '/../app/bootstrap.php';
$entityManager = $container->getByType('Kdyby\Doctrine\EntityManager');
Nelmio\Alice\Fixtures::load(__DIR__ . '/fixtures.php', $entityManager);
