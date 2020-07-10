#!/usr/bin/env php
<?php

require __DIR__ . '/../app/bootstrap.php';

use App\Infrastructure\Ui\Cli\CreateEmptyMachineCommand;
use App\Infrastructure\Ui\Cli\CreateMachineCommand;
use App\Infrastructure\Ui\Cli\GetMachineSummaryCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add($container->get(CreateMachineCommand::class));
$application->add($container->get(CreateEmptyMachineCommand::class));
$application->add($container->get(GetMachineSummaryCommand::class));

$application->run();
