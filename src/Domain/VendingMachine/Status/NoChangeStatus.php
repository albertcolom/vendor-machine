<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Status;

use App\Domain\Catalog\Product\Product;
use App\Domain\VendingMachine\Exception\NoChangeMachineException;
use App\Domain\VendingMachine\VendingMachine;

class NoChangeStatus implements Status
{
    private const STATUS_NAME = 'NO_CHANGE';

    public function buyProduct(VendingMachine $context, Product $product): void
    {
        if ($context->userWallet()->totalAmount() !== $context->catalog()->productLine($product->productType())->price()) {
            throw NoChangeMachineException::withMessage();
        }

        $context->catalog()->removeProduct($product, 1);
    }

    public function name(): string
    {
        return self::STATUS_NAME;
    }
}
