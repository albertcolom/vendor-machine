<?php

declare(strict_types=1);

namespace App\Application\VendingMachine;

use App\Application\VendingMachine\Command\UserAddCoinVendingMachineCommand;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;

class UserAddCoinVendingMachine
{
    /** @var VendingMachineRepository */
    private $vending_machine_repository;

    public function __construct(VendingMachineRepository $vending_machine_repository)
    {
        $this->vending_machine_repository = $vending_machine_repository;
    }

    public function handle(UserAddCoinVendingMachineCommand $command): void
    {
        $vending_machine = $this->vending_machine_repository->get();
        $vending_machine->addUserCoin($command->value());
        $this->vending_machine_repository->persist($vending_machine);
    }
}
