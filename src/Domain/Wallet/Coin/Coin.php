<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Coin;

use App\Domain\Wallet\Exception\InvalidCoinException;
use function in_array;

class Coin
{
    private const FIVE_CENTS = 0.05;
    private const TEN_CENTS = 0.10;
    private const TWENTY_FIVE_CENTS = 0.25;
    private const ONE = 1.0;
    private const VALID_TYPES = [
        self::FIVE_CENTS,
        self::TEN_CENTS,
        self::TWENTY_FIVE_CENTS,
        self::ONE,
    ];
    /** @var float */
    private $type;

    private function __construct(float $type)
    {
        $this->assertIsValidType($type);
        $this->type = $type;
    }

    public static function fromString(float $type): self
    {
        return new self($type);
    }

    public static function withFiveCents(): self
    {
        return new self(self::FIVE_CENTS);
    }

    public static function withTenCents(): self
    {
        return new self(self::TEN_CENTS);
    }

    public static function withTwentyFiveCents(): self
    {
        return new self(self::TWENTY_FIVE_CENTS);
    }

    public static function withOne(): self
    {
        return new self(self::ONE);
    }

    public function value(): float
    {
        return $this->type;
    }

    private function assertIsValidType(float $type): void
    {
        if (!in_array($type, self::VALID_TYPES, true)) {
            throw InvalidCoinException::withCoinMessage($type);
        }
    }
}
