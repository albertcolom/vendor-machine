<?php

declare(strict_types=1);

namespace App\Infrastructure\Ui\Cli;

use App\Application\VendingMachine\Command\AddProductVendingMachineCommand;
use App\Application\VendingMachine\Command\BuyProductVendingMachineCommand;
use App\Infrastructure\Service\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function sprintf;

class BuyProductMachineCommand extends Command
{
    /** @var CommandBus */
    private CommandBus $command_bus;

    public function __construct(CommandBus $command_bus)
    {
        $this->command_bus = $command_bus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('vending-machine:product:buy')
            ->setDescription('Buy product')
            ->addArgument('product_type', InputArgument::REQUIRED, 'Insert a valid product_type');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $product_type = $input->getArgument('product_type');

        try {
            $this->command_bus->handle(new BuyProductVendingMachineCommand($product_type));
        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        $output->writeln('<info>The product was purchased</info>');

        return 0;
    }
}
