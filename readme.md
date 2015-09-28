# Silent Hill

Renovace webové stránky [silent-hill.cz](http://silent-hill.cz).

## Požadavky

* Git
* Apche 2
* MySQL
* PHP

## Instalace (pro Debian GNU/Linux a odvozené distribuce)

* Repozitář
* Composer a stažení závislostí
* Databáze
* Virtuální server
* Konfigurace aplikace a inicializace

### Repozitář

Stažení Git repozitáře

```bash
$ git clone git@bitbucket.org:heavenproject/silenthill.git
```

### Composer a stažení závislostí

Přemístit se do adresáře repozitáře

```bash
$ cd ./silenthill/
```

Stažení Composeru a nastavení práv pro spuštění

```bash
$ curl -sS https://getcomposer.org/installer > composer.phar
$ chmod a+x ./composer.phar
```

Stažení PHP závislostí

```bash
$ ./composer.phar install
```

### Databáze

Přihlášení se do MySQL shellu

```bash
$ mysql -u <jméno> -p
```

K zadání hesla pro MySQL budete vyzvání po zadání příkazu výše.

Vytvoření databáze (v mysql shellu)

```mysql
mysql> CREATE DATABASE IF NOT EXISTS `silenthill` CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci';
```

### Virtuální server

Otevřít soubor `/etc/hosts` a na nový řádek přidat záznam `127.0.0.1    silenthill` (oddělení musí být provedeno tabulátorem).

Vytvořit si soubor pro konfiguraci nového virtuálního serveru

```bash
$ sudo cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/silenthill.conf
```

Uvnitř tohoto souboru nastavit následující položky

```apache2
ServerName silenthill

DocumentRoot /cesta/do/adresare/s/weby/silenthill/www

<Directory "/cesta/do/adresare/s/weby/silenthill/www">
    Options FollowSymlinks
    AllowOverride All
    Require all granted
</Directory>

ErrorLog ${APACHE_LOG_DIR}/silenthill/error.log
CustomLog ${APACHE_LOG_DIR}/silenthill/access.log combined
```
Vytvoření logovacího adresáře pro virtuální server

```
$ sudo mkdir /var/log/apache2/silenthill/
```

Aktivace virtualního serveru

```bash
$ sudo a2ensite silenthill
```

Restart web serveru

```bash
$ sudo service apache2 restart
```

### Konfigurace aplikace a inicializace

Vytvoření lokálního konfiguračního souboru

```
$ cp ./app/config/config.local.template.neon ./app/config/config.local.neon
```

V souboru `./app/config/config.local.neon` nastavit tyto parametry:

```
dbname: silenthill
user: user
password: password
```

Nastavení práv pro adresáře

```bash
$ chmod 0777 ./temp/cache/ ./temp/sessions/ ./log/
```

Databázové migrace

```
./console migrations:migrate
```
