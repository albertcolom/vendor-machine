<?php

use App\Application\VendingMachine\AddCoinVendingMachine;
use App\Application\VendingMachine\AddProductVendingMachine;
use App\Application\VendingMachine\BuyProductVendingMachine;
use App\Application\VendingMachine\Command\AddCoinVendingMachineCommand;
use App\Application\VendingMachine\Command\AddProductVendingMachineCommand;
use App\Application\VendingMachine\Command\BuyProductVendingMachineCommand;
use App\Application\VendingMachine\Command\CreateEmptyVendingMachineCommand;
use App\Application\VendingMachine\Command\CreateVendingMachineCommand;
use App\Application\VendingMachine\Command\RefundUserWalletCommand;
use App\Application\VendingMachine\Command\UserAddCoinVendingMachineCommand;
use App\Application\VendingMachine\CreateEmptyVendingMachine;
use App\Application\VendingMachine\CreateVendingMachine;
use App\Application\VendingMachine\GetVendingMachineSummary;
use App\Application\VendingMachine\RefundUserWallet;
use App\Application\VendingMachine\Request\GetVendingMachineSummaryRequest;
use App\Application\VendingMachine\UserAddCoinVendingMachine;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Infrastructure\Repository\JsonVendingMachineRepository;
use App\Infrastructure\Service\Bus\CommandBus;
use App\Infrastructure\Service\Bus\QueryBus;
use App\Infrastructure\Service\Bus\TacticianCommandBus;
use App\Infrastructure\Service\Bus\TacticianQueryBus;
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
        UserAddCoinVendingMachineCommand::class => UserAddCoinVendingMachine::class,
        AddCoinVendingMachineCommand::class => AddCoinVendingMachine::class,
        RefundUserWalletCommand::class => RefundUserWallet::class,
        AddProductVendingMachineCommand::class => AddProductVendingMachine::class,
        BuyProductVendingMachineCommand::class => BuyProductVendingMachine::class,
    ],

    'query.handler.map' => [
        GetVendingMachineSummaryRequest::class => GetVendingMachineSummary::class
    ],

    'command.handler.middleware' => DI\factory(static function (ContainerInterface $container) {
        return new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new ContainerLocator($container, $container->get('command.handler.map')),
            new HandleInflector()
        );
    }),

    'query.handler.middleware' => DI\factory(static function (ContainerInterface $container) {
        return new CommandHandlerMiddleware(
            new ClassNameExtractor(),
            new ContainerLocator($container, $container->get('query.handler.map')),
            new HandleInflector()
        );
    }),

    CommandBus::class => DI\factory(static function (ContainerInterface $container) {
        return new TacticianCommandBus([$container->get('command.handler.middleware')]);
    }),

    QueryBus::class => DI\factory(static function (ContainerInterface $container) {
        return new TacticianQueryBus([$container->get('query.handler.middleware')]);
    }),
];
