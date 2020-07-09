<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Status;

use App\Domain\Catalog\Product\Product;
use App\Domain\VendingMachine\VendingMachine;

class ReadyStatus implements Status
{
    private const STATUS_NAME = 'READY';

    public function buyProduct(VendingMachine $context, Product $product): void
    {
        $remain = $context->userWallet()->totalAmount() - $context->catalog()->productLine($product->productType())->price();
        $change_wallet = $context->machineWallet()->getChange($remain);
        $context->machineWallet()->removeCoinsAmount($change_wallet->coinsAmount());

        $context->userWallet()->reset();
        $context->catalog()->removeProduct($product, 1);
    }

    public function name(): string
    {
        return self::STATUS_NAME;
    }
}
