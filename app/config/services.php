<?php

use App\Application\VendingMachine\Command\CreateEmptyVendingMachineCommand;
use App\Application\VendingMachine\Command\CreateVendingMachineCommand;
use App\Application\VendingMachine\CreateEmptyVendingMachine;
use App\Application\VendingMachine\CreateVendingMachine;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Infrastructure\Repository\JsonVendingMachineRepository;
use App\Infrastructure\Service\Bus\CommandBus;
use App\Infrastructure\Service\Bus\TacticianCommandBus;
use App\Infrastructure\Service\File\FileManager;
use App\Infrastructure\Service\File\SystemFileManager;
use App\Infrastructure\Service\Serialize\Serializer;
use App\Infrastructure\Service\Serialize\ZumbaJsonSerializer;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use Psr\Container\ContainerInterface;

return [
    Serializer::class => DI\get(ZumbaJsonSerializer::class),
    FileManager::class => DI\get(SystemFileManager::class),
    VendingMachineRepository::class => DI\get(JsonVendingMachineRepository::class),

    'command.handler.map' => [
        CreateEmptyVendingMachineCommand::class => CreateEmptyVendingMachine::class,
        CreateVendingMachineCommand::class => CreateVendingMachine::class,
    ],

    'command.handler.middleware' => DI\factory(function (ContainerInterface $container) {
        return new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new ContainerLocator($container, $container->get('command.handler.map')),
            new HandleInflector()
        );
    }),

    CommandBus::class => DI\factory(function (ContainerInterface $container) {
        return new TacticianCommandBus([$container->get('command.handler.middleware')]);
    }),
];