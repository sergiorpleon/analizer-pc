<?php

declare(strict_types=1);

namespace App\Services\Data;

use Exception;

/**
 * Fuente de datos remota (GitHub).
 */
final class GitHubDataSource implements DataSourceInterface
{
    public function __construct(
        private string $baseUrl,
        private array $files
    ) {
    }

    public function getDocuments(): array
    {
        $documents = [];
        foreach ($this->files as $file) {
            $url = rtrim($this->baseUrl, '/') . '/' . $file;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $content !== false) {
                $documents[$file] = $content;
            }
        }
        return $documents;
    }
}
