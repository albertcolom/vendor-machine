<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Exception;

use Exception;
use function sprintf;

class ProductNotExistException extends Exception
{
    public static function withProductTypeMessage(string $product_type): self
    {
        return new self(sprintf('Product type "%s" not exist', $product_type));
    }
}
