<?php

declare(strict_types=1);

namespace App\Application\VendingMachine;

use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\StatusFactory;

class UpdateStatusVendingMachine
{
    /** @var VendingMachineRepository */
    private $vending_machine_repository;
    /** @var StatusFactory */
    private $status_factory;

    public function __construct(VendingMachineRepository $vending_machine_repository, StatusFactory $status_factory)
    {
        $this->vending_machine_repository = $vending_machine_repository;
        $this->status_factory = $status_factory;
    }

    public function handle(): void
    {
        $vending_machine = $this->vending_machine_repository->get();

        $status = $this->status_factory->build(
            $vending_machine->catalog()->totalProducts(),
            $vending_machine->machineWallet()->totalAmount()
        );

        if ($status->name() === $vending_machine->status()->name()) {
            return;
        }

        $vending_machine->setStatus($status);
        $this->vending_machine_repository->persist($vending_machine);
    }
}
