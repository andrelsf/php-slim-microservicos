<?php

/**
 * 
 * bootstrap.php
 * Concentra todas as configurações de todas as dependências da API.
 *  
 */

require './vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

/**
 * Container Resources
 */
$container = new \Slim\Container();

$isDevMode = true;

/**
 * Diretório de entidades e Metadata do doctrine.
 */
$config = Setup::createAnnotationMetadataConfiguration(
    array(__DIR__."/src/Models/Entity"), $isDevMode
);

/**
 * Configuração da conexão com banco de dados MySQL.
 */
$conn = array(
    'driver' => 'pdo_mysql',
    'host' => '172.17.0.2',
    'port' => 3306,
    'dbname' => 'users',
    'user' => 'dbuser',
    'password' => 'dbuser123'
);

/**
 * Instrância do EntityManager
 */
$entityManager = EntityManager::create($conn, $config);

/**
 * Inseri a EntityManager no container com o nome [Entity Manager]
 */
$container['em'] = $entityManager;

/**
 * 
 * Instancia GLOBAL da aplicação (Singleton)
 * Gerenciamento de toda nossa aplicação através de um ponto de acesso global
 * realizando a injeção de dependências dentro de um container.
 * 
 */
$app = new \Slim\App;