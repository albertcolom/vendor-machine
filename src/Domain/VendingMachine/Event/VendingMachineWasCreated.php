<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Event;

use App\Domain\Core\Event\DomainEvent;

class VendingMachineWasCreated extends DomainEvent
{
    public function payload(): array
    {
        return [];
    }
}
