<?php

declare(strict_types=1);

namespace App\Domain\Catalog\Event;

use App\Domain\Core\Event\DomainEvent;

class ProductLineWasRemoved extends DomainEvent
{
    /** @var string */
    private $product_type;
    /** @var float */
    private $price;
    /** @var int */
    private $quantity;

    public function __construct(string $product_type, float $price, int $quantity)
    {
        $this->product_type = $product_type;
        $this->price = $price;
        $this->quantity = $quantity;
        parent::__construct();
    }

    public function payload(): array
    {
        return [
            'product_type' => $this->product_type,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }
}
