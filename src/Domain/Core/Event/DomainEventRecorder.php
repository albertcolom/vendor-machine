<?php

declare(strict_types=1);

namespace App\Domain\Core\Event;

class DomainEventRecorder
{
    /** @var DomainEventRecorder */
    private static $instance = null;
    /** @var DomainEvent[] */
    private $recordedEvents = [];

    public static function instance(): self
    {
        if (null === static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function record(DomainEvent $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function recordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;
        $this->eraseEvents();

        return $events;
    }

    public function eraseEvents(): void
    {
        $this->recordedEvents = [];
    }
}
