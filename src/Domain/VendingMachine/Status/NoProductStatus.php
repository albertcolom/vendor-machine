<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Status;

use App\Domain\Catalog\Product\Product;
use App\Domain\VendingMachine\Exception\NoProductMachineException;
use App\Domain\VendingMachine\VendingMachine;

class NoProductStatus implements Status
{
    private const STATUS_NAME = 'NO_PRODUCT';

    public function buyProduct(VendingMachine $context, Product $product): void
    {
        throw NoProductMachineException::withMessage();
    }

    public function name(): string
    {
        return self::STATUS_NAME;
    }
}
