<?php

declare(strict_types=1);

namespace App\Application\VendingMachine\Command;

use App\Domain\Catalog\Product\Product;
use App\Domain\Catalog\Product\ProductType;

class BuyProductVendingMachineCommand
{
    /** @var string */
    private $product_type;

    public function __construct(string $product_type)
    {
        $this->product_type = $product_type;
    }

    public function product(): Product
    {
        return new Product(ProductType::fromString($this->product_type));
    }
}
