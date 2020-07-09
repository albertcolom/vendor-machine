<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Exception;

use Exception;

class NoProductMachineException extends Exception
{
    public static function withMessage(): self
    {
        return new self('You can not buy the machine does not have any product');
    }
}
