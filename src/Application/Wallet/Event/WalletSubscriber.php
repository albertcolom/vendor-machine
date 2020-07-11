<?php

declare(strict_types=1);

namespace App\Application\Wallet\Event;

use App\Infrastructure\Service\Event\EventSubscriber;

class WalletSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [];
    }
}
