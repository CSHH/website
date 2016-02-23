# Czech Silent Hill Heaven

## Repozitář

```
git clone git@bitbucket.org:heavenproject/silenthill.git silent-hill.local
cd silent-hill.local
```

## Databáze

`cp ./app/config/config.local.neon.dist ./app/config/config.local.neon`

V souboru `./app/config/config.local.neon` nastavit tyto parametry: `dbname`, `user` a `password`.

## Composer

`curl -sS https://getcomposer.org/installer | php`

Bude pak k dispozici jako `./composer.phar`.

## Phing

```
curl -sS http://www.phing.info/get/phing-latest.phar > phing.phar
chmod +x phing.phar
```

Bude pak k dispozici jako `./phing.phar`.

Pro účely vývoje použijte příkaz `./phing.phar init+fixtures`, který aplikaci inicializuje a do databáze nahraje testovací data (fixtury).

## Bower

`npm install -g bower` popř. `sudo npm install -g bower`

## Uživatelé

Po aplikaci fixtur bude k dispozici jeden uživatel:

### Administrátor:

* email: john.doe@example.com
* heslo: admin
