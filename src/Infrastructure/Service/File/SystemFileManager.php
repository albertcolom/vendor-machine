<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\File;

use function file_get_contents;

class SystemFileManager implements FileManager
{
    public function getContent(string $path): string
    {
        return file_get_contents($path);
    }

    public function saveContent(string $path, string $content): void
    {
        file_put_contents($path, $content);
    }
}
