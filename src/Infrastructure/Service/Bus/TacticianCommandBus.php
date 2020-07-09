<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bus;

class TacticianCommandBus implements CommandBus
{
    /** @var \League\Tactician\CommandBus */
    private $command_bus;

    public function __construct(array $middleware)
    {
        $this->command_bus =  new \League\Tactician\CommandBus($middleware);
    }

    public function handle($command)
    {
        $this->command_bus->handle($command);
    }
}
