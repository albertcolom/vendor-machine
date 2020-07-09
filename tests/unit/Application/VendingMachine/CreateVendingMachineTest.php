<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\CreateVendingMachine;
use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product\Product;
use App\Domain\Catalog\Product\ProductLine;
use App\Domain\Catalog\Product\ProductType;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\ReadyStatus;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\Wallet\Coin\Coin;
use App\Domain\Wallet\Coin\CoinAmount;
use App\Domain\Wallet\Wallet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateVendingMachineTest extends TestCase
{
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldCreateAndPersistExpectedVendingMachine(): void
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

        $expected_vending_machine = new VendingMachine(new ReadyStatus(), new Catalog($products_line), new Wallet($coins_amount), Wallet::empty());

        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new CreateVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle();
    }
}
