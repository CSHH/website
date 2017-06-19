<?php

require __DIR__ . '/../../vendor/autoload.php';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());

@mkdir(TEMP_DIR);
@mkdir(TEMP_DIR . '/sessions');

$configurator = new Nette\Configurator;
$configurator->setDebugMode(false);
$configurator->enableDebugger(TEMP_DIR);
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(__DIR__ . '/../../config/config.neon');
$configurator->addConfig(__DIR__ . '/db.neon');

return $configurator->createContainer();
