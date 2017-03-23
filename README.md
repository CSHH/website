# Czech Silent Hill Heaven (CSHH) Website

[![Build Status](https://travis-ci.org/CSHH/website.svg?branch=master)](https://travis-ci.org/CSHH/website)

The following information will help you to get yourself up and running with this project.

> **Please note, that this project is not done yet. There are still a few features to work on with probably some bugfixes yet to be discovered.
  Also there are not tests on everything, so this has to be done too before the release.**

## What you will need

* [PHP](http://php.net)
* [Composer](https://getcomposer.org)
* [Node.js](https://nodejs.org)
* [NPM](https://www.npmjs.com)
* [Grunt](https://gruntjs.com)

## After you clone this repository

Run `composer install` to install all PHP dependencies.

## If you need to work on front-end

Run `npm install` to install all JavaScript and CSS dependencies and then
run `grunt` to prepare all the assets.

## To setup database access

Set parameters `dbname`, `user` and `password` in `app/config/config.local.neon`.

## To prepare this project for development

Run `vendor/bin/phing init`.

## And if you need to seed your database with some dummy data

Run `vendor/bin/phing fixtures` or run `vendor/bin/phing init+fixtures` in one single step.

After this you can use one prepared user account to log yourself in with these credentials:

* email: john.doe@example.com
* password: admin

## And finally to run it

Run `bin/console server:start`, open your web browser and go to the `http://localhost:8000`.

After you are done don´t forget to run `bin/console server:stop` to stop the server running.

## Tests

Run `vendor/bin/tester tests -p php -c tests/php.ini`.

## Contributing

Please see our [contributing guidlines](contributing.md).

## License

This source code is [free software](http://www.gnu.org/philosophy/free-sw.html) licensed under MIT [license](license.md).
