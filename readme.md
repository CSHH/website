# Silent Hill

## Instalace

Composer:

```
curl -sS https://getcomposer.org/installer > composer.phar
chmod a+x ./composer.phar
```

PHP závislosti pomocí composeru:

```
./composer install
```

Lokální konfigurace + DB:

```
cp ./app/config/config.local.template.neon ./app/config/config.local.neon
```

V souboru `./app/config/config.local.neon` nastavit tyto parametry:

```
dbname: dbname
user: user
password: password
```

DB migrace:

```
./console migrations:migrate
```

