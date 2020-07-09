<?php

declare(strict_types=1);

namespace App\Application\VendingMachine;

use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\VendingMachine;

class CreateVendingMachine
{
    /** @var VendingMachineRepository */
    private $vending_machine_repository;

    public function __construct(VendingMachineRepository $vending_machine_repository)
    {
        $this->vending_machine_repository = $vending_machine_repository;
    }

    public function handle(): void
    {
        $vending_machine = VendingMachine::withCatalogAndChange();
        $this->vending_machine_repository->persist($vending_machine);
    }
}
