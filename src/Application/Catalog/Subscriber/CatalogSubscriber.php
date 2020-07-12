<?php

declare(strict_types=1);

namespace App\Application\Catalog\Subscriber;

use App\Application\VendingMachine\UpdateStatusVendingMachine;
use App\Domain\Catalog\Event\ProductLineWasCreated;
use App\Domain\Catalog\Event\ProductLineWasRemoved;
use App\Infrastructure\Service\Event\EventSubscriber;

class CatalogSubscriber implements EventSubscriber
{
    /** @var UpdateStatusVendingMachine */
    private $update_status_vending_machine;

    public function __construct(UpdateStatusVendingMachine $update_status_vending_machine)
    {
        $this->update_status_vending_machine = $update_status_vending_machine;
    }

    public function getSubscribedEvents(): array
    {
        return [
            ProductLineWasCreated::class => [$this, 'updateStatusVendingMachine'],
            ProductLineWasRemoved::class => [$this, 'updateStatusVendingMachine'],
        ];
    }

    public function updateStatusVendingMachine(): void
    {
        $this->update_status_vending_machine->handle();
    }
}
