<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Exception;

use Exception;

class NotEnoughQuantityException extends Exception
{
    public static function withMessage(): self
    {
        return new self('Not enough quantity');
    }
}
