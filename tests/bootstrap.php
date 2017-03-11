<?php

require __DIR__ . '/../vendor/autoload.php';

$_SERVER['SERVER_NAME']  = 'localhost';
$_SERVER['REQUEST_URI']  = '';
$_SERVER['QUERY_STRING'] = '';
$_SERVER['HTTP_HOST']    = '';

Tester\Environment::setup();
date_default_timezone_set('Europe/Prague');

define('TEMP_DIR', __DIR__ . '/tmp/' . getmypid());

@mkdir(TEMP_DIR);
@mkdir(TEMP_DIR . '/sessions');

$params = array('testingsqlitedb' => TEMP_DIR . '/db.sqlite');

$configurator = new Nette\Configurator;
$configurator->setDebugMode(true);
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters($params);
$configurator->createRobotLoader()
    ->addDirectory(__DIR__ . '/../app')
    ->register();

$configurator->addConfig(__DIR__ . '/../app/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/db.neon');

$container = $configurator->createContainer();

$source = __DIR__ . '/db-image.sqlite';
$target = $params['testingsqlitedb'];
Nette\Utils\FileSystem::copy($source, $target);

return $container;
