<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine\Status;

use App\Domain\Catalog\Product\Product;
use App\Domain\VendingMachine\VendingMachine;

interface Status
{
    public function buyProduct(VendingMachine $context, Product $product): void;
    public function name(): string;
}
