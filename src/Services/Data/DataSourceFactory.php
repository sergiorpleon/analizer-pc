<?php

declare(strict_types=1);

namespace App\Services\Data;

use Exception;

/**
 * Factoría para decidir el origen de los datos.
 */
final class DataSourceFactory
{
    public static function create(): DataSourceInterface
    {
        // Usar $_ENV en lugar de getenv() porque Dotenv carga en $_ENV
        $source = $_ENV['DATA_SOURCE'] ?? getenv('DATA_SOURCE') ?: 'github';
        $config = require __DIR__ . '/../../../config/config.php';

        return match ($source) {
            'local' => new LocalDataSource(
                basePath: $config['data']['local_path'],
                files: $config['data']['files']
            ),
            'github' => new GitHubDataSource(
                baseUrl: $config['data']['base_url'],
                files: $config['data']['files']
            ),
            default => throw new Exception("Origen de datos no soportado: $source"),
        };
    }
}
