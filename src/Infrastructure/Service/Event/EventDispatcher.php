<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Event;

use function get_class;

class EventDispatcher
{
    /** @var callable[] */
    private $listeners = [];

    public function dispatch($event)
    {
        foreach ($this->getListeners(get_class($event)) as $listener) {
            $listener($event);
        }
    }

    public function addListener($eventName, callable $listener)
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function getListeners($eventName)
    {
        if (!$this->hasListeners($eventName)) {
            return [];
        }

        return $this->listeners[$eventName];
    }

    public function hasListeners($eventName)
    {
        return isset($this->listeners[$eventName]);
    }

    public function addSubscriber(EventSubscriber $eventSubscriber)
    {
        foreach ($eventSubscriber->getSubscribedEvents() as $eventName => $listeners) {
            $this->addListener($eventName, $listeners);
        }
    }
}
