<?php

declare(strict_types=1);

namespace App\Application\VendingMachine;

use App\Application\VendingMachine\Command\AddProductVendingMachineCommand;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;

class AddProductVendingMachine
{
    /** @var VendingMachineRepository */
    private $vending_machine_repository;

    public function __construct(VendingMachineRepository $vending_machine_repository)
    {
        $this->vending_machine_repository = $vending_machine_repository;
    }

    public function handle(AddProductVendingMachineCommand $command): void
    {
        $vending_machine = $this->vending_machine_repository->get();
        $vending_machine->addProduct($command->product(), $command->price());
        $this->vending_machine_repository->persist($vending_machine);
    }
}
