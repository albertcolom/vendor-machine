<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\GetVendingMachineSummary;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\VendingMachine;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GetVendingMachineSummaryTest extends TestCase
{
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldGetExpectedSummaryResponseWhenVendingMachineIsEmpty(): void
    {
        $expected_vending_machine = VendingMachine::empty();
        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn($expected_vending_machine);

       $sut = new GetVendingMachineSummary($this->mocked_vending_machine_repository);
       $sut_response = $sut->handle();

        $this->assertSame($expected_vending_machine->status()->name(), $sut_response->statusName());
        $this->assertSame($expected_vending_machine->catalog(), $sut_response->catalog());
        $this->assertSame($expected_vending_machine->machineWallet(), $sut_response->machineWallet());
        $this->assertSame($expected_vending_machine->userWallet(), $sut_response->userWallet());
    }

    public function testItShouldGetExpectedSummaryResponseWhenVendingMachineIsNotEmpty(): void
    {
        $expected_vending_machine = VendingMachine::withCatalogAndChange();
        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn($expected_vending_machine);

        $sut = new GetVendingMachineSummary($this->mocked_vending_machine_repository);
        $sut_response = $sut->handle();

        $this->assertSame($expected_vending_machine->status()->name(), $sut_response->statusName());
        $this->assertSame($expected_vending_machine->catalog(), $sut_response->catalog());
        $this->assertSame($expected_vending_machine->machineWallet(), $sut_response->machineWallet());
        $this->assertSame($expected_vending_machine->userWallet(), $sut_response->userWallet());
    }
}
