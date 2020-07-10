<?php

declare(strict_types=1);

namespace App\Application\VendingMachine;

use App\Domain\VendingMachine\Repository\VendingMachineRepository;

class RefundUserWallet
{
    /** @var VendingMachineRepository */
    private $vending_machine_repository;

    public function __construct(VendingMachineRepository $vending_machine_repository)
    {
        $this->vending_machine_repository = $vending_machine_repository;
    }

    public function handle(): void
    {
        $vending_machine = $this->vending_machine_repository->get();
        if (0.0 === $vending_machine->userWallet()->totalAmount()) {
            return;
        }
        $vending_machine->refund();
        $this->vending_machine_repository->persist($vending_machine);
    }
}
