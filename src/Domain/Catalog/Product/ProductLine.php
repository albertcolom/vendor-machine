<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Product;

use App\Domain\Catalog\Event\ProductLineWasCreated;
use App\Domain\Catalog\Event\ProductLineWasRemoved;
use App\Domain\Catalog\Exception\NotEnoughQuantityException;
use App\Domain\Core\Event\DomainEventRecorder;

class ProductLine
{
    /** @var Product */
    private $product;
    /** @var float */
    private $price;
    /** @var int */
    private $quantity;

    public function __construct(Product $product, float $price, int $quantity)
    {
        $this->product = $product;
        $this->price = $price;
        $this->quantity = $quantity;

        DomainEventRecorder::instance()->record(
            new ProductLineWasCreated($this->product->productType()->value(), $this->price(), $this->quantity())
        );
    }

    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function removeQuantity(int $quantity): void
    {
        if ($this->quantity < $quantity) {
            throw NotEnoughQuantityException::withMessage();
        }

        $this->quantity -= $quantity;

        DomainEventRecorder::instance()->record(
            new ProductLineWasRemoved($this->product->productType()->value(), $this->price(), $this->quantity())
        );
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
