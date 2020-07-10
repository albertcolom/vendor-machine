<?php

declare(strict_types=1);

namespace App\Domain\VendingMachine;

use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product\Product;
use App\Domain\Catalog\Product\ProductLine;
use App\Domain\Catalog\Product\ProductType;
use App\Domain\VendingMachine\Status\NoProductStatus;
use App\Domain\VendingMachine\Status\ReadyStatus;
use App\Domain\VendingMachine\Status\Status;
use App\Domain\Wallet\Coin\Coin;
use App\Domain\Wallet\Coin\CoinAmount;
use App\Domain\Wallet\Wallet;

class VendingMachine
{
    /** @var Status */
    private Status $status;
    /** @var Catalog */
    private $catalog;
    /** @var Wallet */
    private $machine_wallet;
    /** @var Wallet */
    private $user_wallet;

    public function __construct(Status $status, Catalog $catalog, Wallet $machine_wallet, Wallet $user_wallet)
    {
        $this->status = $status;
        $this->catalog = $catalog;
        $this->machine_wallet = $machine_wallet;
        $this->user_wallet = $user_wallet;
    }

    public static function empty(): self
    {
        return new self(new NoProductStatus(), Catalog::empty(), Wallet::empty(), Wallet::empty());
    }

    public static function withCatalogAndChange(): self
    {
        $products_line = [
            new ProductLine(new Product(ProductType::withWater()), 0.65, 1),
            new ProductLine(new Product(ProductType::withJuice()), 1, 1),
            new ProductLine(new Product(ProductType::withSoda()), 1.5, 1),
        ];

        $coins_amount = [
            new CoinAmount(Coin::withFiveCents(), 1),
            new CoinAmount(Coin::withTenCents(), 1),
            new CoinAmount(Coin::withTwentyFiveCents(), 1),
            new CoinAmount(Coin::withOne(), 1),
        ] ;

        return new self(new ReadyStatus(), new Catalog($products_line), new Wallet($coins_amount), Wallet::empty());
    }

    public function addProduct(Product $product, float $price, int $quantity = 1): void
    {
        $this->catalog->addProductLine(new ProductLine($product, $price, $quantity));
    }

    public function buyProduct(Product $product): void
    {
        $this->status->buyProduct($this, $product);
    }

    public function refund(): void
    {
        $this->machine_wallet->removeCoinsAmount($this->user_wallet->coinsAmount());
        $this->user_wallet->reset();
    }

    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    public function addUserCoin(Coin $coin): void
    {
        $this->user_wallet->addCoin($coin, 1);
        $this->machine_wallet->addCoin($coin, 1);
    }

    public function addMachineCoin(Coin $coin): void
    {
        $this->machine_wallet->addCoin($coin, 1);
    }

    public function status(): Status
    {
        return $this->status;
    }

    public function catalog(): Catalog
    {
        return $this->catalog;
    }

    public function userWallet(): Wallet
    {
        return $this->user_wallet;
    }

    public function machineWallet(): Wallet
    {
        return $this->machine_wallet;
    }
}
