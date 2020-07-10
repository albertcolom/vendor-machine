<?php

declare(strict_types=1);

namespace App\Application\VendingMachine\Command;

use App\Domain\Catalog\Product\Product;
use App\Domain\Catalog\Product\ProductType;

class AddProductVendingMachineCommand
{
    /** @var string */
    private $product_type;
    /** @var float */
    private $price;

    public function __construct(string $product_type, float $price)
    {
        $this->product_type = $product_type;
        $this->price = $price;
    }

    public function product(): Product
    {
        return new Product(ProductType::fromString($this->product_type));
    }

    public function price(): float
    {
        return $this->price;
    }
}
