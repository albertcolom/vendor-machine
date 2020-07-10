<?php

declare(strict_types=1);

namespace App\Application\VendingMachine\Command;

use App\Domain\Wallet\Coin\Coin;

class UserAddCoinVendingMachineCommand
{
    /** @var float */
    private $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function value(): Coin
    {
        return Coin::fromFloat($this->value);
    }
}
