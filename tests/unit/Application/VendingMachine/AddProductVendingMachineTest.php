<?php

declare(strict_types=1);

namespace AppTest\Application\VendingMachine;

use App\Application\VendingMachine\AddProductVendingMachine;
use App\Application\VendingMachine\Command\AddProductVendingMachineCommand;
use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Exception\InvalidProductTypeException;
use App\Domain\Catalog\Product\Product;
use App\Domain\Catalog\Product\ProductLine;
use App\Domain\Catalog\Product\ProductType;
use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\Status\NoProductStatus;
use App\Domain\VendingMachine\VendingMachine;
use App\Domain\Wallet\Wallet;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddProductVendingMachineTest extends TestCase
{
    private const VALID_PRODUCT_TYPE = 'water';
    private const INVALID_PRODUCT_TYPE = 'foo';
    private const PRODUCT_PRICE = 1;
    /** @var VendingMachineRepository|MockObject */
    private $mocked_vending_machine_repository;

    public function setUp(): void
    {
        $this->mocked_vending_machine_repository = $this->createMock(VendingMachineRepository::class);
    }

    public function testItShouldGetInvalidCoinExceptionWhenInsertInvalidCoinType(): void
    {
        $this->expectException(InvalidProductTypeException::class);
        $this->expectExceptionMessage('foo is not a valid product type. Valid types: "water, juice, soda"');

        $sut = new AddProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new AddProductVendingMachineCommand(self::INVALID_PRODUCT_TYPE, self::PRODUCT_PRICE));
    }

    public function testItShouldInsertCoinOnVendingMachineCatalog(): void
    {
        $expected_vending_machine = new VendingMachine(
            new NoProductStatus(),
            new Catalog([new ProductLine(new Product(ProductType::withWater()), self::PRODUCT_PRICE, 1)]),
            Wallet::empty(),
            Wallet::empty()
        );

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::empty());
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new AddProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new AddProductVendingMachineCommand(self::VALID_PRODUCT_TYPE, self::PRODUCT_PRICE));
    }

    public function testItShouldSumProductWhenProductExistInCatalog(): void
    {
        $expected_vending_machine = VendingMachine::withCatalogAndChange();
        $expected_vending_machine->addProduct(new Product(ProductType::withWater()), self::PRODUCT_PRICE);

        $this->mocked_vending_machine_repository->expects($this->once())->method('get')->willReturn(VendingMachine::withCatalogAndChange());
        $this->mocked_vending_machine_repository->expects($this->once())->method('persist')->with($expected_vending_machine);

        $sut = new AddProductVendingMachine($this->mocked_vending_machine_repository);
        $sut->handle(new AddProductVendingMachineCommand(self::VALID_PRODUCT_TYPE, self::PRODUCT_PRICE));
    }
}
