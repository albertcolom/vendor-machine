<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\VendingMachine\Repository\VendingMachineRepository;
use App\Domain\VendingMachine\VendingMachine;
use App\Infrastructure\Service\File\SystemFileManager;
use App\Infrastructure\Service\Serialize\Serializer;

class JsonFileVendingMachineRepository implements VendingMachineRepository
{
    private const JSON_PATH =  __DIR__ . '/../../../var/data/VendingMachine.json';
    /** @var SystemFileManager */
    private $system_file_manager;
    /** @var Serializer */
    private $serializer;

    public function __construct(SystemFileManager $system_file_manager, Serializer $serializer)
    {
        $this->serializer = $serializer;
        $this->system_file_manager = $system_file_manager;
    }

    public function get(): VendingMachine
    {
        return $this->serializer->unSerialize($this->system_file_manager->getContent(self::JSON_PATH));
    }

    public function persist(VendingMachine $vending_machine): void
    {
        $serialized_data = $this->serializer->serialize($vending_machine);

        $this->system_file_manager->saveContent(self::JSON_PATH, $serialized_data);
    }
}
