<?php

declare(strict_types=1);

namespace App\Application\VendingMachine;

use App\Application\VendingMachine\Response\GetVendingMachineSummaryResponse;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;

class GetVendingMachineSummary
{
    /** @var VendingMachineRepository */
    private $vending_machine_repository;

    public function __construct(VendingMachineRepository $vending_machine_repository)
    {
        $this->vending_machine_repository = $vending_machine_repository;
    }

    public function handle(): GetVendingMachineSummaryResponse
    {
        $vending_machine = $this->vending_machine_repository->get();

        return new GetVendingMachineSummaryResponse(
            $vending_machine->status()->name(),
            $vending_machine->catalog(),
            $vending_machine->machineWallet(),
            $vending_machine->userWallet()
        );
    }
}
