<?php
// config/config.php

return [
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'db',
        'dbname' => $_ENV['DB_NAME'] ?? 'ai_db',
        'user' => $_ENV['DB_USER'] ?? 'user',
        'password' => $_ENV['DB_PASSWORD'] ?? 'password',
        'dsn' => sprintf(
            'pgsql:host=%s;dbname=%s;port=%s',
            $_ENV['DB_HOST'] ?? 'db',
            $_ENV['DB_NAME'] ?? 'ai_db',
            $_ENV['DB_PORT'] ?? '5432'
        )
    ],
    'ai' => [
        'provider' => $_ENV['EMBEDDING_PROVIDER'] ?? getenv('EMBEDDING_PROVIDER') ?: 'ollama',
        'gemini_key' => $_ENV['GEMINI_API_KEY'] ?? getenv('GEMINI_API_KEY') ?: '',
        'vector_dimension' => (int) ($_ENV['VECTOR_DIMENSION'] ?? getenv('VECTOR_DIMENSION') ?: (($_ENV['EMBEDDING_PROVIDER'] ?? getenv('EMBEDDING_PROVIDER') ?: 'ollama') === 'ollama' ? 4096 : 768))
    ],
    'ollama' => [
        'url' => 'http://ollama:11434',
        'model' => 'llama3',
        'embedding_size' => 4096
    ],
    'data' => [
        // Fuente de datos: 'url' o 'local'
        'source' => $_ENV['DATA_SOURCE'] ?? 'url',

        // Configuración para fuente URL
        'base_url' => 'https://raw.githubusercontent.com/krishna-koly/IMDB_TOP_1000/refs/heads/main/imdb_top_1000.csv',

        // Configuración para fuente local
        'local_path' => __DIR__ . '/../data/',

        // Archivos a procesar
        'files' => [
            'imdb_top_1000.csv'
        ],
        'import_limit' => 1000, // Límite de filas por archivo
        'access_key' => '12345' // Clave de seguridad para data.php
    ],
    'app' => [
        'name' => 'Analizador de Películas',
        'search_limit' => 5 // Número de resultados a mostrar
    ],
    'auth' => [
        'session_name' => 'analizer_pc_session'
        // Las credenciales ahora se gestionan en la base de datos (tabla users)
        // Usuario por defecto: admin / admin123
    ]
];
