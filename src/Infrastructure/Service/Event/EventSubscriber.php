<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Event;

interface EventSubscriber
{
    public function getSubscribedEvents(): array;
}
