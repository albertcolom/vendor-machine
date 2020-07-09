<?php

declare(strict_types=1);

namespace App\Domain\Catalog;

use App\Domain\Catalog\Exception\NotEnoughQuantityException;
use App\Domain\Catalog\Exception\ProductNotExistException;
use App\Domain\Catalog\Product\Product;
use App\Domain\Catalog\Product\ProductLine;
use App\Domain\Catalog\Product\ProductType;
use function array_key_exists;

class Catalog
{
    /** @var ProductLine[] */
    private $product_lines;

    public function __construct(array $product_lines)
    {
        $this->product_lines = [];
        foreach ($product_lines as $product_line){
            $this->addProductLine($product_line);
        }
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function addProductLine(ProductLine $product_line): void
    {
        if ($this->productAlreadyExist($product_line->product())) {
            $this->product_lines[$product_line->product()->productType()->value()]->addQuantity($product_line->quantity());
            return;
        }

        $this->product_lines[$product_line->product()->productType()->value()] = $product_line;
    }

    public function removeProduct(Product $product, int $quantity): void
    {
        if (!$this->productAlreadyExist($product)) {
            throw ProductNotExistException::withProductTypeMessage($product->productType()->value());
        }

        if (0 >= $this->productLine($product->productType())->quantity()) {
            throw NotEnoughQuantityException::withMessage();
        }

        $this->product_lines[$product->productType()->value()]->removeQuantity($quantity);
    }

    public function productLine(ProductType $product_type): ProductLine
    {
        return $this->product_lines[$product_type->value()];
    }

    private function productAlreadyExist(Product $product): bool
    {
        return array_key_exists($product->productType()->value(), $this->product_lines);
    }
}
