<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Product;

use App\Domain\Catalog\Exception\InvalidProductTypeException;
use function in_array;

class ProductType
{
    private const WATER = 'water';
    private const JUICE = 'juice';
    private const SODA = 'soda';
    private const VALID_TYPES = [
        self::WATER,
        self::JUICE,
        self::SODA
    ];
    /** @var string */
    private $type;

    private function __construct(string $type)
    {
        $this->assertIsValidType($type);
        $this->type = $type;
    }

    public static function fromString(string $type): self
    {
        return new self($type);
    }

    public static function withWater(): self
    {
        return new self(self::WATER);
    }

    public static function withJuice(): self
    {
        return new self(self::JUICE);
    }

    public static function withSoda(): self
    {
        return new self(self::SODA);
    }

    public function value(): string
    {
        return $this->type;
    }

    private function assertIsValidType(string $type): void
    {
        if (!in_array($type, self::VALID_TYPES, true)) {
            throw InvalidProductTypeException::withProductTypeMessage($type, self::VALID_TYPES);
        }
    }
}
