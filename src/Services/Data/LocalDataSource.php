<?php

declare(strict_types=1);

namespace App\Services\Data;

use Exception;

/**
 * Fuente de datos local (archivos en el disco).
 */
final class LocalDataSource implements DataSourceInterface
{
    public function __construct(
        private string $basePath,
        private array $files
    ) {
    }

    public function getDocuments(): array
    {
        $documents = [];
        foreach ($this->files as $file) {
            $path = rtrim($this->basePath, '/') . '/' . $file;
            if (!file_exists($path)) {
                continue;
            }
            $documents[$file] = file_get_contents($path);
        }
        return $documents;
    }
}
