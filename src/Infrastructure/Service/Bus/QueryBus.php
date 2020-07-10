<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bus;

interface QueryBus
{
    public function handle($command);
}
