<?php

declare(strict_types=1);

namespace App\Infrastructure\Ui\Cli;

use App\Application\VendingMachine\Command\RefundUserWalletCommand;
use App\Infrastructure\Service\Bus\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function sprintf;

class RefundUserMoneyCommand extends Command
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
            ->setName('vending-machine:coin:refund')
            ->setDescription('Refund user coins');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->command_bus->handle(new RefundUserWalletCommand());
        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }

        $output->writeln('<info>The coins was refunded</info>');

        return 0;
    }
}
