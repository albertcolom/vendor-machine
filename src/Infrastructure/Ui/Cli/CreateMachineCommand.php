<?php

declare(strict_types=1);

namespace App\Infrastructure\Ui\Cli;

use App\Application\VendingMachine\Command\CreateVendingMachineCommand;
use App\Infrastructure\Service\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function sprintf;

class CreateMachineCommand extends Command
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
            ->setName('vending-machine:create')
            ->setDescription('Create vending machine with catalog and wallet');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->command_bus->handle(new CreateVendingMachineCommand());
        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        $output->writeln('<info>Vending machine was created</info>');

        return 0;
    }
}
