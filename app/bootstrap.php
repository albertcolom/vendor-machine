<?php

use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../app/config/services.php');
$builder->useAnnotations(false);

$container =  $builder->build();
