<?php

declare(strict_types=1);

namespace App\Application\Wallet\Subscriber;

use App\Application\VendingMachine\UpdateStatusVendingMachine;
use App\Domain\Wallet\Event\CoinAmountWasCreated;
use App\Domain\Wallet\Event\CoinAmountWasRemoved;
use App\Infrastructure\Service\Event\EventSubscriber;

class WalletSubscriber implements EventSubscriber
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
            CoinAmountWasCreated::class => [$this, 'updateStatusVendingMachine'],
            CoinAmountWasRemoved::class => [$this, 'updateStatusVendingMachine'],
        ];
    }

    public function updateStatusVendingMachine(): void
    {
        $this->update_status_vending_machine->handle();
    }
}
