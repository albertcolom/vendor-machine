<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Exception;

use Exception;
use function sprintf;

class InvalidProductTypeException extends Exception
{
    public static function withProductTypeMessage(string $product_type): self
    {
        return new self(sprintf('%s is not a valid type', $product_type));
    }
}
