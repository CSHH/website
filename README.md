# Czech Silent Hill Heaven (CSHH) Website

[![Build Status](https://travis-ci.org/CSHH/website.svg?branch=master)](https://travis-ci.org/CSHH/website)

The following information will help you to get yourself up and running with this project.

> **Please note, that this project is not done yet. There are still a few features to work on with probably some bugfixes yet to be discovered. Also there are not tests on everything, so this has to be done too before the release.**

## What you will need

* [PHP](http://php.net) (5.6)
* [Composer](https://getcomposer.org)
* [Node.js](https://nodejs.org)
* [NPM](https://www.npmjs.com)
* [Grunt](https://gruntjs.com) (`npm install -g grunt-cli`)

## Clone this repository

```bash
$ git clone git@github.com:CSHH/website.git
```

## Branches

* [**master**](https://github.com/CSHH/website/tree/master) is the main branch used for development
* [**ready-to-release**](https://github.com/CSHH/website/tree/ready-to-release) is always one commit ahead of master and contains some modifications for the release

## Install PHP dependencies

```bash
$ composer install
```

## Install client side dependencies

```bash
$ npm install
```

## Build the assets

```bash
$ grunt
```

## Setup database access

Open up the `app/config/config.local.neon` file in your code editor and set these parameters

* dbname
* user
* password

## Prepare this project for development

```bash
$ vendor/bin/phing init
```

## Seed your database with some dummy data

```bash
$ vendor/bin/phing fixtures
```

Or in one single step

```bash
$ vendor/bin/phing init+fixtures
```

## Start the development server

```bash
$ bin/console server:start
```

Open your web browser and go to the http://localhost:8000.

## Log yourself in

If you have applied the fixtures you can use one of three prepared user accounts to log yourself in with these credentials

### As a user with role user

* email: jake.doe@example.com
* password: user

### As a user with role moderator

* email: jane.doe@example.com
* password: moderator

### As a user with role administrator

* email: john.doe@example.com
* password: administrator

## After you are done don´t forget to stop the server

```bash
$ bin/console server:stop
```

## Tests

```bash
$ vendor/bin/tester tests -p php -c tests/php.ini
```

## Contributing

Please see our [contributing guidlines](CONTRIBUTING.md).

## License

This source code is [free software](http://www.gnu.org/philosophy/free-sw.html) licensed under MIT [license](LICENSE.md).
