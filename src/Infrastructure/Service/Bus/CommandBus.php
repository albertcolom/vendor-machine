<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Bus;

interface CommandBus
{
    public function handle($command);
}
