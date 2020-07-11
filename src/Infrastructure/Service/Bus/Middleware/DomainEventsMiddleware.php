<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bus\Middleware;

use App\Domain\Core\Event\DomainEventRecorder;
use App\Domain\Core\Event\Repository\EventRepository;
use App\Infrastructure\Service\Event\EventDispatcher;

use League\Tactician\Middleware;

class DomainEventsMiddleware implements Middleware
{
    /** @var EventDispatcher */
    private $event_dispatcher;
    /** @var EventRepository */
    private $event_repository;

    public function __construct(EventDispatcher $event_dispatcher, EventRepository $event_repository)
    {
        $this->event_repository = $event_repository;
        $this->event_dispatcher = $event_dispatcher;
    }

    public function execute($command, callable $next)
    {
        $next($command);
        $this->persistDomainEvent();
    }

    private function persistDomainEvent(): void
    {
        foreach (DomainEventRecorder::instance()->releaseEvents() as $event) {
            $this->event_repository->add($event);
            $this->event_dispatcher->dispatch($event);
        }
    }
}
