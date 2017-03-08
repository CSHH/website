# Czech Silent Hill Heaven (CSHH) Website

[![Build Status](https://travis-ci.org/CSHH/website.svg?branch=master)](https://travis-ci.org/CSHH/website)

The following information will help you to get yourself up and running with this project.

## What you will need

* [PHP](http://php.net)
* [Composer](https://getcomposer.org)
* [Node.js](https://nodejs.org)
* [NPM](https://www.npmjs.com)
* [Bower](https://bower.io)

## After you clone this repository

Run `composer install` to install all PHP dependencies.

## If you need to work on front-end

Run `bower install` to install all JavaScript and CSS dependencies.

## To setup database access

Set parameters `dbname`, `user` and `password` in `app/config/config.local.neon`.

## To prepare this project for development

Run `vendor/bin/phing init`.

## And if you need to seed your database with some dummy data

Run `vendor/bin/phing fixtures`.

After this you can use one prepared user account to log yourself in with these credentials:

* email: john.doe@example.com
* password: admin

## Contributing

Please see [contributing.md](contributing.md).

## License

This source code is [free software](http://www.gnu.org/philosophy/free-sw.html)
licensed under MIT [license](license.md).
