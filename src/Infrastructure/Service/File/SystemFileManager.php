<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\File;

use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function touch;
use function var_dump;

class SystemFileManager implements FileManager
{
    public function getContent(string $path): string
    {
        $this->ensureFileExist($path);
        return file_get_contents($path);
    }

    public function saveContent(string $path, string $content): void
    {
        file_put_contents($path, $content);
    }

    public function addContent(string $path, string $content): void
    {
        file_put_contents($path, $content . PHP_EOL, FILE_APPEND);
    }

    private function ensureFileExist($path): void
    {
        if (!file_exists($path)) {
            touch($path);
        }
    }
}
