<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Exception;

use Exception;

class NotEnoughChangeException extends Exception
{
    public static function withMessage(): self
    {
        return new self('Not enough change');
    }
}
