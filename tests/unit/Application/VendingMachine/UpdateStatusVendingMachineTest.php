<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\UpdateStatusVendingMachine;
use App\Domain\Catalog\Catalog;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\NoProductStatus;
use App\Domain\VendingMachine\Status\ReadyStatus;
use App\Domain\VendingMachine\Status\StatusFactory;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\Wallet\Wallet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateStatusVendingMachineTest extends TestCase
{
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;
    /** @var StatusFactory|MockObject */
    private $mocked_status_factory;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
        $this->mocked_status_factory = $this->createMock(StatusFactory::class);
    }

    public function testItShouldNoPersistWhenBuildSameState(): void
    {
        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::empty());
        $this->mocked_status_factory->expects($this->once())->method('build')->willReturn(new NoProductStatus());
        $this->mocked_vending_machine_repository->expects($this->never())->method('persist');

        $sut = new UpdateStatusVendingMachine($this->mocked_vending_machine_repository, $this->mocked_status_factory);
        $sut->handle();
    }

    public function testItShouldUpdateStatusWhenStatusChange(): void
    {
        $expected_machine = new VendingMachine(new ReadyStatus(), Catalog::empty(), Wallet::empty(), Wallet::empty());

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::empty());
        $this->mocked_status_factory->expects($this->once())->method('build')->willReturn(new ReadyStatus());
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_machine);

        $sut = new UpdateStatusVendingMachine($this->mocked_vending_machine_repository, $this->mocked_status_factory);
        $sut->handle();
    }
}
