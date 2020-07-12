<?php

declare(strict_types=1);

namespace App\Infrastructure\Ui\Cli;

use App\Application\VendingMachine\Command\CreateEmptyVendingMachineCommand;
use App\Infrastructure\Service\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function sprintf;

class CreateEmptyMachineCommand extends Command
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
            ->setName('vending-machine:create:empty')
            ->setDescription('Create empty vending machine');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->command_bus->handle(new CreateEmptyVendingMachineCommand());
        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        $output->writeln('<info>Empty vending machine was created</info>');

        return 0;
    }
}
