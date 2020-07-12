<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Exception;

use Exception;

class VendingMachineNotFoundException extends Exception
{
    public static function withMessage(): self
    {
        return new self('Vending machine not found');
    }
}
