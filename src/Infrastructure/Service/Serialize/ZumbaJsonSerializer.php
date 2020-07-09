<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Serialize;

use Zumba\JsonSerializer\JsonSerializer;

class ZumbaJsonSerializer implements Serializer
{
    /** @var JsonSerializer */
    private JsonSerializer $json_serialize;

    public function __construct()
    {
        $this->json_serialize = new JsonSerializer();
    }

    public function serialize($data): string
    {
        return $this->json_serialize->serialize($data);
    }

    public function unSerialize(string $string)
    {
        return $this->json_serialize->unserialize($string);
    }
}
