<?php

declare(strict_types=1);

namespace App\Application\VendingMachine;

use App\Application\VendingMachine\Command\BuyProductVendingMachineCommand;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;

class BuyProductVendingMachine
{
    /** @var VendingMachineRepository */
    private $vending_machine_repository;

    public function __construct(VendingMachineRepository $vending_machine_repository)
    {
        $this->vending_machine_repository = $vending_machine_repository;
    }

    public function handle(BuyProductVendingMachineCommand $command): void
    {
        $vending_machine = $this->vending_machine_repository->get();
        $vending_machine->buyProduct($command->product());
        $this->vending_machine_repository->persist($vending_machine);
    }
}
