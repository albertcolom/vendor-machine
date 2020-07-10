<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\RefundUserWallet;
use App\Domain\Catalog\Catalog;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\NoProductStatus;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\Wallet\Coin\Coin;
use App\Domain\Wallet\Coin\CoinAmount;
use App\Domain\Wallet\Wallet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RefundUserWalletTest extends TestCase
{
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldNotUpdateVendingMachineWhenUserWalletIsEmpty(): void
    {
        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::empty());
        $this->mocked_vending_machine_repository->expects($this->never())->method('persist');

        $sut = new RefundUserWallet($this->mocked_vending_machine_repository);
        $sut->handle();
    }

    public function testItShouldRefundUserWallet(): void
    {
        $expected_vending_machine = new VendingMachine(
            new NoProductStatus(),
            Catalog::empty(),
            new Wallet([new CoinAmount(Coin::withOne(), 0)]),
            Wallet::empty()
        );
        $vending_machine = new VendingMachine(
            new NoProductStatus(),
            Catalog::empty(),
            new Wallet([new CoinAmount(Coin::withOne(), 1)]),
            new Wallet([new CoinAmount(Coin::withOne(), 1)])
        );

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn($vending_machine);
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new RefundUserWallet($this->mocked_vending_machine_repository);
        $sut->handle();
    }
}
