<?php

declare(strict_types=1);

namespace App\Domain\Core\Event;

use DateTimeImmutable;
use function json_encode;

abstract class DomainEvent
{
    /** @var DateTimeImmutable */
    private $occurred_on;

    public function __construct()
    {
        $this->occurred_on = new DateTimeImmutable();
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurred_on;
    }

    public function eventName(): string
    {
        return substr(strrchr(get_class($this), "\\"), 1);
    }

    public function jsonPayload(): string
    {
        return json_encode($this->payload());
    }

    abstract public function payload(): array;
}
