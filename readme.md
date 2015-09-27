# Silent Hill

Renovace existující webové stránky [silent-hill.cz](http://silent-hill.cz).

## Instalace

Stažení Git repozitáře:

```
git clone git@bitbucket.org:heavenproject/silenthill.git
```

Composer:

```
cd ./silenthill/

curl -sS https://getcomposer.org/installer > composer.phar
chmod a+x ./composer.phar
```

PHP závislosti pomocí composeru:

```
./composer install
```

Databáze:

```
mysql -u <jméno> -p
```

K zadání hesla pro MySQL budete vyzvání po zadání příkazu výše.

V mysql shellu:

```
CREATE DATABASE IF NOT EXISTS `silenthill3` CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci';
```

Virtuální server:

```
sudo edit /etc/hosts
```

Lokální konfigurace:

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

