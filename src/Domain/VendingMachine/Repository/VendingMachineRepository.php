<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Repository;

use App\Domain\VendingMachine\VendingMachine;

interface VendingMachineRepository
{
    public function get(): VendingMachine;
    public function persist(VendingMachine $vending_machine): void;
}
