<?php

declare(strict_types=1);

namespace App\Infrastructure\Ui\Cli;

use App\Application\VendingMachine\Request\GetVendingMachineSummaryRequest;
use App\Application\VendingMachine\Response\GetVendingMachineSummaryResponse;
use App\Domain\Catalog\Catalog;
use App\Domain\Catalog\Product\ProductLine;
use App\Domain\Wallet\Coin\CoinAmount;
use App\Domain\Wallet\Wallet;
use App\Infrastructure\Service\Bus\QueryBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function array_map;
use function sprintf;

class GetMachineSummaryCommand extends Command
{
    /** @var QueryBus */
    private QueryBus $query_bus;

    public function __construct(QueryBus $query_bus)
    {
        $this->query_bus = $query_bus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('vending-machine:summary')
            ->setDescription('Vending machine summary');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var GetVendingMachineSummaryResponse $response */
            $response = $this->query_bus->handle(new GetVendingMachineSummaryRequest());

            $output->writeln(sprintf('<fg=black;bg=cyan>STATUS: %s</>', $response->statusName()));

            $this->renderTableCatalogSummary($response->catalog(), $output);
            $this->renderTableWalletSummary($response->machineWallet(), 'Machine Wallet', $output);
            $this->renderTableWalletSummary($response->userWallet(), 'User Wallet', $output);

        } catch (Throwable $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            return 1;
        }

        return 0;
    }

    private function renderTableCatalogSummary(Catalog $catalog, OutputInterface $output): void
    {
        $catalog_summary_row = array_map(static function (ProductLine $product_line) {
            return [
                $product_line->product()->productType()->value(),
                $product_line->product()->name(),
                $product_line->quantity(),
                $product_line->price(),
            ];
        }, $catalog->productLines());

        $table = new Table($output);
        $table
            ->setHeaderTitle('Catalog')
            ->setHeaders(['Type', 'Name', 'Quantity', 'Price'])
            ->setRows($catalog_summary_row)
        ;
        $table->render();
    }

    private function renderTableWalletSummary(Wallet $machine_wallet, string $title, OutputInterface $output): void
    {
        $wallet_summary_row = array_map(static function (CoinAmount $coin_amount) {
            return [$coin_amount->coin()->value(), $coin_amount->quantity(), $coin_amount->total()];
        }, $machine_wallet->coinsAmount());

        $table = new Table($output);
        $table
            ->setHeaderTitle($title)
            ->setHeaders(['Coin', 'Quantity', 'Total'])
            ->setRows($wallet_summary_row)
        ;
        $table->render();

        $output->writeln(sprintf('<info>Total amount: %s</info>', $machine_wallet->totalAmount()));
    }
}
