<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Exception;

use Exception;

class NoChangeMachineException extends Exception
{
    public static function withMessage(): self
    {
        return new self('The machine doesn\'t have change. Please insert the exact money');
    }
}
