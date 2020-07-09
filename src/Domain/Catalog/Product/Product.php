<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Product;

class Product
{
    /** @var ProductType */
    private $product_type;
    /** @var string */
    private $name;

    public function __construct(ProductType $product_type, ?string $name = null)
    {
        $this->product_type = $product_type;
        $this->name = $name ?? $product_type->value();
    }

    public function productType(): ProductType
    {
        return $this->product_type;
    }

    public function name(): string
    {
        return $this->name;
    }
}
