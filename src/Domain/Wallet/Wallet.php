<?php

declare(strict_types=1);

namespace App\Domain\Wallet;

use App\Domain\Wallet\Coin\Coin;
use App\Domain\Wallet\Coin\CoinAmount;
use App\Domain\Wallet\Exception\CoinNotExistInWalletException;
use App\Domain\Wallet\Exception\NotEnoughChangeException;
use App\Domain\Wallet\Exception\NotEnoughMoneyException;
use function array_key_exists;
use function array_reduce;
use function floor;
use function round;

class Wallet
{
    /** @var CoinAmount[] */
    private $coins_amount;

    public function __construct(array $coins_amount)
    {
        $this->coins_amount = [];
        /** @var CoinAmount $coin_amount */
        foreach ($coins_amount as $coin_amount) {
            $this->addCoinAmount($coin_amount);
        }
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public function addCoinAmount(CoinAmount $coin_amount): void
    {
        if ($this->coinAlreadyExist($coin_amount->coin())) {
            $this->coins_amount[(string)$coin_amount->coin()->value()]->addQuantity($coin_amount->quantity());
            return;
        }

        $this->coins_amount[(string)$coin_amount->coin()->value()] = $coin_amount;
    }

    public function getChange(float $amount): Wallet
    {
        $change_Wallet = self::empty();
        $remain = round($amount, 2);

        if (0.0 === $remain) {
            return $change_Wallet;
        }

        if (0.0 > $remain) {
            throw NotEnoughMoneyException::withMessage();
        }

        krsort($this->coins_amount);
        /** @var CoinAmount $coin_amount */
        foreach ($this->coins_amount as $coin_amount) {
            if ($remain < $coin_amount->coin()->value()) {
                continue;
            }

            $coin_number = (int)floor(round($remain / $coin_amount->coin()->value(), 2));
            $coin_number = ($coin_number <= $coin_amount->quantity()) ? $coin_number : $coin_amount->quantity();
            $change_Wallet->addCoinAmount(new CoinAmount($coin_amount->coin(), $coin_number));
            $remain -= round($coin_amount->coin()->value() * $coin_number, 2);

            if (0.0 === $remain) {
                break;
            }
        }

        if (0 < $remain) {
            throw NotEnoughChangeException::withMessage();
        }

        return $change_Wallet;
    }

    public function removeCoinsAmount(array $coins_amount): void
    {
        /** @var CoinAmount $coin_amount */
        foreach ($coins_amount as $coin_amount) {
            $this->removeCoin($coin_amount->coin(), $coin_amount->quantity());
        }
    }

    public function removeCoin(Coin $coin, int $quantity = 1): void
    {
        if (!$this->coinAlreadyExist($coin)) {
            throw CoinNotExistInWalletException::withCoinMessage($coin->value());
        }

        $this->coins_amount[(string)$coin->value()]->removeQuantity($quantity);
    }

    public function coinsAmount(): array
    {
        return $this->coins_amount;
    }

    public function totalAmount(): float
    {
        return array_reduce($this->coins_amount, static function($total, CoinAmount $coins_amount) {
            return $total + $coins_amount->total();
        }, 0);
    }

    public function reset(): void
    {
        $this->coins_amount = [];
    }

    private function coinAlreadyExist(Coin $coin): bool
    {
        return array_key_exists((string)$coin->value(), $this->coins_amount);
    }
}
