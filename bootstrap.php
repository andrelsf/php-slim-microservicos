<?php

/**
 * 
 * bootstrap.php
 * Concentra todas as configurações de todas as dependências da API.
 *  
 */

require './vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Slim\Container;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use \Slim\App;

/**
 * Container Resources e adiciona as definições
 */
$container = new Container(require __DIR__.'/settings.php');


/**
 * Diretório de entidades e Metadata do doctrine.
 */
$container[EntityManager::class] = function (Container $container): EntityManager {
    $config = Setup::createAnnotationMetadataConfiguration(
        $container['settings']['doctrine']['metadata_dirs'],
        $container['settings']['doctrine']['dev_mode']
    );

    $config->setMetadataDriverImpl(
        new AnnotationDriver(
            new AnnotationReader,
            $container['settings']['doctrine']['metadata_dirs']
        )
    );

    $config->setMetadataCacheImpl(
        new FilesystemCache(
            $container['settings']['doctrine']['cache_dir']
        )
    );

    return EntityManager::create(
        $container['settings']['doctrine']['connection'],
        $config
    );
};

/**
 * 
 * Instancia GLOBAL da aplicação (Singleton)
 * Gerenciamento de toda nossa aplicação através de um ponto de acesso global
 * realizando a injeção de dependências dentro de um container.
 * 
 */
$app = new App($container);