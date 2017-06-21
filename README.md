![Title image](www/images/bg-fog.jpg)

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

| Branch                                                                    | Description                                                                          |
|---------------------------------------------------------------------------|--------------------------------------------------------------------------------------|
| [master](https://github.com/CSHH/website/tree/master)                     | is the main branch used for development                                              |
| [ready-to-release](https://github.com/CSHH/website/tree/ready-to-release) | is always one commit ahead of master and contains some modifications for the release |

## Install PHP dependencies

```bash
$ composer install
```

## Setup database access

After all dependencies are installed, an interactive prompt will ask you for configuration parameters to setup your database access.

## Install client side dependencies

```bash
$ npm install
```

## Build the assets

```bash
$ grunt
```

## Prepare project for development

| Action                             | Command                          |
|------------------------------------|----------------------------------|
| Basic initialisation               | `vendor/bin/phing init`          |
| Seed database with some dummy data | `vendor/bin/phing fixtures`      |
| Initialise and seed                | `vendor/bin/phing init+fixtures` |

## Development server

| Action | Command                    |
|--------|----------------------------|
| Start  | `bin/console server:start` |
| Stop   | `bin/console server:stop`  |

The website will be accessible on http://localhost:8000 by default.

## Log yourself in

If you have applied the fixtures you can use one of three prepared user accounts to log yourself in

| Role          | Email                | Password      |
|---------------|----------------------|---------------|
| Administrator | john.doe@example.com | administrator |
| Moderator     | jane.doe@example.com | moderator     |
| User          | jake.doe@example.com | user          |

## Tests

```bash
$ vendor/bin/tester tests -p php -c tests/php.ini
```

## Contributing

Please see our [contributing guidlines](CONTRIBUTING.md).

## License

This source code is [free software](http://www.gnu.org/philosophy/free-sw.html) licensed under MIT [license](LICENSE.md).

