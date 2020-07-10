<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Exception;

use Exception;
use function implode;
use function sprintf;

class InvalidProductTypeException extends Exception
{
    public static function withProductTypeMessage(string $product_type, array $valid_types): self
    {
        return new self(sprintf('%s is not a valid product type. Valid types: "%s"', $product_type, implode(', ', $valid_types)));
    }
}
