<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Exception;

use Exception;
use function sprintf;

class CoinNotExistInWalletException extends Exception
{
    public static function withCoinMessage(float $coin_type): self
    {
        return new self(sprintf('The coin "%s" does not exist in the wallet', $coin_type));
    }
}
