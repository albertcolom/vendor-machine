<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Exception;

use Exception;
use function sprintf;

class InvalidCoinException extends Exception
{
    public static function withCoinMessage(float $coin_type, array $valid_types): self
    {
        return new self(sprintf('%s is not a valid Coin. Valid types: "%s"', $coin_type, implode(', ', $valid_types)));
    }
}
