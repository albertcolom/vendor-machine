<?php

declare(strict_types=1);

namespace App\Infrastructure\Ui\Cli;

use App\Application\VendingMachine\Command\AddCoinVendingMachineCommand;
use App\Infrastructure\Service\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function sprintf;

class AddCoinMachineCommand extends Command
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
            ->setName('vending-machine:coin:add')
            ->setDescription('Add coin into the machine')
            ->addArgument('coin', InputArgument::REQUIRED, 'Insert a valid coin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $coin = (float)$input->getArgument('coin');

        try {
            $this->command_bus->handle(new AddCoinVendingMachineCommand($coin));
        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        $output->writeln('<info>The coin was added</info>');

        return 0;
    }
}
