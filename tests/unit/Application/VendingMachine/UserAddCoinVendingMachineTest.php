<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\Command\UserAddCoinVendingMachineCommand;
use App\Application\VendingMachine\UserAddCoinVendingMachine;
use App\Domain\Catalog\Catalog;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\NoProductStatus;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\Wallet\Coin\Coin;
use App\Domain\Wallet\Coin\CoinAmount;
use App\Domain\Wallet\Exception\InvalidCoinException;
use App\Domain\Wallet\Wallet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserAddCoinVendingMachineTest extends TestCase
{
    private const VALID_COIN = 1;
    private const INVALID_COIN = 2;
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldGetInvalidCoinExceptionWhenInsertInvalidCoinType(): void
    {
        $this->expectException(InvalidCoinException::class);
        $this->expectExceptionMessage('2 is not a valid Coin. Valid types: "0.05, 0.1, 0.25, 1"');

        $sut = new UserAddCoinVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new UserAddCoinVendingMachineCommand(self::INVALID_COIN));
    }

    public function testItShouldInsertCoinOnUserWallet(): void
    {
        $expected_vending_machine = new VendingMachine(
            new NoProductStatus(),
            Catalog::empty(),
            new Wallet([new CoinAmount(Coin::withOne(), 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)])
        );

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::empty());
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new UserAddCoinVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new UserAddCoinVendingMachineCommand(self::VALID_COIN));
    }

    public function testItShouldSumMoneyWhenCoinExistInWallet(): void
    {
        $expected_vending_machine = VendingMachine::withCatalogAndChange();
        $expected_vending_machine->addUserCoin(Coin::withOne());

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::withCatalogAndChange());
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new UserAddCoinVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new UserAddCoinVendingMachineCommand(self::VALID_COIN));
    }
}
