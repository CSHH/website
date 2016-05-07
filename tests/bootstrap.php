<?php

require_once __DIR__ . '/../vendor/autoload.php';

Tester\Environment::setup();

define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());

@mkdir(TEMP_DIR);

$params = array('testingsqlitedb' => TEMP_DIR . '/db.sqlite');

$configurator = new Nette\Configurator;
$configurator->setDebugMode(false);
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters($params);
$configurator->createRobotLoader()
    ->addDirectory(__DIR__ . '/../app')
    ->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/db.neon');

return $configurator->createContainer();
