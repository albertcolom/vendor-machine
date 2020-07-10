<?php

declare(strict_types=1);

namespace App\Application\VendingMachine\Response;

use App\Domain\Catalog\Catalog;
use App\Domain\VendingMachine\Status\Status;
use App\Domain\Wallet\Wallet;

class GetVendingMachineSummaryResponse
{
    /** @var string */
    private $status_name;
    /** @var Catalog */
    private $catalog;
    /** @var Wallet */
    private $machine_wallet;
    /** @var Wallet */
    private $user_wallet;

    public function __construct(string $status_name, Catalog $catalog, Wallet $machine_wallet, Wallet $user_wallet)
    {
        $this->status_name = $status_name;
        $this->catalog = $catalog;
        $this->machine_wallet = $machine_wallet;
        $this->user_wallet = $user_wallet;
    }

    public function statusName(): string
    {
        return $this->status_name;
    }

    public function catalog(): Catalog
    {
        return $this->catalog;
    }

    public function machineWallet(): Wallet
    {
        return $this->machine_wallet;
    }

    public function userWallet(): Wallet
    {
        return $this->user_wallet;
    }
}
