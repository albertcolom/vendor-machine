<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\BuyProductVendingMachine;
use App\Application\VendingMachine\Command\BuyProductVendingMachineCommand;
use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product\Product;
use App\Domain\Catalog\Product\ProductLine;
use App\Domain\Catalog\Product\ProductType;
use App\Domain\VendingMachine\Exception\NoChangeMachineException;
use App\Domain\VendingMachine\Exception\NoProductMachineException;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\NoChangeStatus;
use App\Domain\VendingMachine\Status\ReadyStatus;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\Wallet\Coin\Coin;
use App\Domain\Wallet\Coin\CoinAmount;
use App\Domain\Wallet\Exception\NotEnoughChangeException;
use App\Domain\Wallet\Wallet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BuyProductVendingMachineTest extends TestCase
{
    private const PRODUCT_PRICE = 1;
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldExpectedVendingMachineBehavior(): void
    {
        $expected_vending_machine = new VendingMachine(
            new ReadyStatus(),
            new Catalog([new ProductLine(new Product(ProductType::withJuice()), self::PRODUCT_PRICE, 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)]),
            Wallet::empty()
        );

        $vending_machine = new VendingMachine(
            new ReadyStatus(),
            new Catalog([new ProductLine(new Product(ProductType::withJuice()), self::PRODUCT_PRICE, 2)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)])
        );

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn($vending_machine);
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new BuyProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new BuyProductVendingMachineCommand(ProductType::withJuice()->value()));
    }

    public function testItShouldGetNoProductMachineExceptionWhenStatusIsWithoutProducts(): void
    {
        $this->expectException(NoProductMachineException::class);
        $this->expectExceptionMessage('You can not buy the machine does not have any product');

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::empty());

        $sut = new BuyProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new BuyProductVendingMachineCommand(ProductType::withWater()->value()));
    }

    public function testItShouldGetNoChangeMachineExceptionWhenStatusIsWithoutChangeAndTryBuyWithoutExactMoney(): void
    {
        $this->expectException(NoChangeMachineException::class);
        $this->expectExceptionMessage('The machine doesn\'t have change. Please insert the exact money');

        $vending_machine = VendingMachine::withCatalogAndChange();
        $vending_machine->setStatus(new NoChangeStatus());

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn($vending_machine);

        $sut = new BuyProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new BuyProductVendingMachineCommand(ProductType::withWater()->value()));
    }

    public function testItShouldGetNotEnoughMoneyExceptionWhenNotEnoughMoney(): void
    {
        $this->expectException(NotEnoughChangeException::class);
        $this->expectExceptionMessage('Not enough change');

        $vending_machine = new VendingMachine(
            new ReadyStatus(),
            new Catalog([new ProductLine(new Product(ProductType::withWater()), 0.5, 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)])
        );

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn($vending_machine);

        $sut = new BuyProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new BuyProductVendingMachineCommand(ProductType::withWater()->value()));
    }

    public function testItShouldCanBuyWithExactMoneyWhenMachineNotHaveChange(): void
    {
        $expected_vending_machine = new VendingMachine(
            new NoChangeStatus(),
            new Catalog([new ProductLine(new Product(ProductType::withJuice()), self::PRODUCT_PRICE, 0)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)]),
            Wallet::empty()
        );

        $vending_machine = new VendingMachine(
            new NoChangeStatus(),
            new Catalog([new ProductLine(new Product(ProductType::withJuice()), self::PRODUCT_PRICE, 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)])
        );

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn($vending_machine);
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new BuyProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new BuyProductVendingMachineCommand(ProductType::withJuice()->value()));
    }
}
