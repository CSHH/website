<?php

require __DIR__ . '/../../vendor/autoload.php';

$_SERVER['SERVER_NAME']  = 'localhost';
$_SERVER['REQUEST_URI']  = '';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['HTTP_HOST']    = '';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());

@mkdir(TEMP_DIR);
@mkdir(TEMP_DIR . '/sessions');

$params = [
    'testingsqlitedb' => TEMP_DIR . '/db.sqlite',
    'appDir'          => __DIR__ . '/../../app',
    'wwwDir'          => __DIR__ . '/../../www',
];

$configurator = new Nette\Configurator;
$configurator->setDebugMode(true);
$configurator->enableDebugger(TEMP_DIR);
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters($params);
$configurator->addConfig(__DIR__ . '/../../config/config.neon');
$configurator->addConfig(__DIR__ . '/config/db.neon');

$container = $configurator->createContainer();

$source = __DIR__ . '/db-image.sqlite';
$target = $params['testingsqlitedb'];
Nette\Utils\FileSystem::copy($source, $target);

return $container;
