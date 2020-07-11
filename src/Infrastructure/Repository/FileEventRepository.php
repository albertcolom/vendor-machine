<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Core\Event\DomainEvent;
use App\Domain\Core\Event\Repository\EventRepository;
use App\Infrastructure\Service\File\SystemFileManager;
use function sprintf;

class FileEventRepository implements EventRepository
{
    private const PATH =  __DIR__ . '/../../../var/log/domain_events.log';
    /** @var SystemFileManager */
    private $system_file_manager;

    public function __construct(SystemFileManager $system_file_manager)
    {
        $this->system_file_manager = $system_file_manager;
    }

    public function add(DomainEvent $event): void
    {
        $domain_event = sprintf(
            '[%s] "%s" %s',
            $event->occurredOn()->format('Y-m-d H:i:s'),
            $event->eventName(),
            $event->jsonPayload()
        );

        $this->system_file_manager->addContent(self::PATH, $domain_event);
    }
}
