<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Status;

class StatusFactory
{
    public function build(int $products, float $machine_amount): Status
    {
        if (0 === $products) {
            return new NoProductStatus();
        }

        if (0.0 === $machine_amount) {
            return new NoChangeStatus();
        }

        return new ReadyStatus();
    }
}
