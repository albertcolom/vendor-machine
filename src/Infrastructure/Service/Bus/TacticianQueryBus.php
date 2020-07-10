<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bus;

use League\Tactician\CommandBus;

class TacticianQueryBus implements QueryBus
{
    /** @var CommandBus */
    private $command_bus;

    public function __construct(array $middleware)
    {
        $this->command_bus =  new CommandBus($middleware);
    }

    public function handle($command)
    {
        return $this->command_bus->handle($command);
    }
}
