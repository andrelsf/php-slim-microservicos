# PHP Slim MicroFramework

## Resumo do objetivo.

O objetivo e criar uma API para gerenciamento de usuários, onde terá uma pagina de login, uma pagina para registro, uma pagina home dos usuários autenticados com listagem dos usuários e uma tela de editar dados de usuários.

## Frameworks utilizados.

* PHP Slim      -> microframework usado para criação de APIs e microserviços.
* Doctrine ORM  -> camada de persistência, onde será utilizado Framework Doctrine como um Entity Manager para manipular as entidades e representações dos nossos dados.
* PHP-View      -> Framework utilizado para renderizar as paginas web utilizando o conceito de Templates.
* Auth Basic    -> baseado em sessão de usuário gerencidado por um middleware imbutido no PHP Slim.

## Por quê o PHP Slim

Suas caracteristicas minimalistas que ajuda a escrever rapidamente aplicações web e APIs.

Uso de Middleware filtrando e ajustando os objetos de requests e reponses HTTP entordo do seu APP.
Uso de Router mapeando os retornos de chamadas para metodos de requests HTTP e URIs especificos.
Injeção de dependências para controle de ferramentas externas (Container).
Suporte PSR para inspecionar e manipular os metodos, status, URI, Headers, cookies e body de mensages HTTP.

## Instalação

Arquivo `composer.json`:

```json
{
    "require": {
        "slim/slim": "^3.8",
        "slim/psr7": "^0.4.0",
        "doctrine/orm": "^2.5",
        "slim/php-view": "^2.2"
    },
    "autoload": {
        "psr-4": {"App\\": "./src"}
    }
}
```

```bash
$ composer install
```

## Rotas e os Métodos HTTP

* GET
* POST

## Doctrine commands

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