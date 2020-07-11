<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Event;

use App\Domain\Core\Event\DomainEvent;

class CoinAmountWasCreated extends DomainEvent
{
    /** @var float */
    private $coin_type;
    /** @var int */
    private $quantity;

    public function __construct(float $coin_type, int $quantity)
    {
        $this->coin_type = $coin_type;
        $this->quantity = $quantity;
        parent::__construct();
    }

    public function payload(): array
    {
        return [
            'coin_type' => $this->coin_type,
            'quantity' => $this->quantity,
        ];
    }
}
