<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Serialize;

interface Serializer
{
    public function serialize($data): string;
    public function unSerialize(string $data);
}
