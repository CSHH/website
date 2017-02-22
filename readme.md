# Czech Silent Hill Heaven

Následující informace uvádějí seznam nástrojů, které pro vývoj aplikace budete potřebovat a také jak aplikaci zprovoznit.

## Co budete potřebovat

* [PHP](http://php.net)
* [Composer](https://getcomposer.org)
* [Node.js](https://nodejs.org)
* [NPM](https://www.npmjs.com)
* [Bower](https://bower.io)

## Databáze

`cp ./app/config/config.local.neon.dist ./app/config/config.local.neon`

V souboru `./app/config/config.local.neon` nastavit tyto parametry: `dbname`, `user` a `password`.

## Inicializace aplikace pro vývoj

Pro účely vývoje použijte příkaz `vendor/bin/phing init+fixtures`, který aplikaci inicializuje a do databáze nahraje testovací data (fixtury).

## Uživatelé

Po aplikaci fixtur bude k dispozici jeden uživatel:

### Administrátor:

* email: john.doe@example.com
* heslo: admin
