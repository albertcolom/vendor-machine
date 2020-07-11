<?php

declare(strict_types=1);

namespace App\Domain\Core\Event\Repository;

use App\Domain\Core\Event\DomainEvent;

interface EventRepository
{
    public function add(DomainEvent $event): void;
}
