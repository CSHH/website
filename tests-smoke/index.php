<?php

$container = require __DIR__ . '/bootstrap.php';
$container->getByType('Nette\Application\Application')->run();
