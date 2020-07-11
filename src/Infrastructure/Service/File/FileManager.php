<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\File;

interface FileManager
{
    public function getContent(string $path): string;
    public function saveContent(string $path, string $content): void;
    public function addContent(string $path, string $content): void;
}
