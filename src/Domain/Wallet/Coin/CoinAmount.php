<?php

declare(strict_types=1);

namespace App\Domain\Wallet\Coin;

use App\Domain\Core\Event\DomainEventRecorder;
use App\Domain\Wallet\Event\CoinAmountWasCreated;
use App\Domain\Wallet\Event\CoinAmountWasRemoved;
use App\Domain\Wallet\Exception\NotEnoughMoneyException;

class CoinAmount
{
    /** @var Coin */
    private $coin;
    /** @var int */
    private $quantity;

    public function __construct(Coin $coin, int $quantity)
    {
        $this->coin = $coin;
        $this->quantity = $quantity;

        DomainEventRecorder::instance()->record(new CoinAmountWasCreated($coin->value(), $this->quantity));
    }

    public function coin(): Coin
    {
        return $this->coin;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function total(): float
    {
        return $this->coin->value() * $this->quantity;
    }

    public function addQuantity(int $quantity): void
    {
        $this->quantity += $quantity;
    }

    public function removeQuantity(int $quantity): void
    {
        $this->assertEnoughQuantity($quantity);
        $this->quantity -= $quantity;

        DomainEventRecorder::instance()->record(new CoinAmountWasRemoved($this->coin->value(), $this->quantity));
    }

    private function assertEnoughQuantity(int $quantity): void
    {
        if ($quantity > $this->quantity) {
            throw NotEnoughMoneyException::withMessage();
        }
    }
}
