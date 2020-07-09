<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\CreateEmptyVendingMachine;
use App\Domain\Catalog\Catalog;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\NoProductStatus;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\Wallet\Wallet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateEmptyVendingMachineTest extends TestCase
{
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldCreateAndPersistExpectedVendingMachine(): void
    {
        $expected_vending_machine = new VendingMachine(new NoProductStatus(), Catalog::empty(), Wallet::empty(), Wallet::empty());

        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new CreateEmptyVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle();
    }
}
