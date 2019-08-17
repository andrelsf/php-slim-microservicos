# Slim Framework PHP

## Install

```bash
$ composer install slim/slim:"^3.8"
$ composer install composer require slim/psr7
$ composer require doctrine/orm:"^2.5"
```

## Doctrine

CLI do Doctrine
Um require no arquivp Bootstrap e passar nossa instância do Entity Manager para o Console Runner do Doctrine.

Arquivo `cli-config.php`:

```php
<?php

use \Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once "./bootstrap.php";

return  ConsoleRunner::createHelperSet($entityManager);
```

```bash
$ vendor/bin/doctrine orm:schema-tool:update --force
```