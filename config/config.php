<?php
// config/config.php

return [
    'database' => [
        'host' => 'db',
        'dbname' => 'ai_db',
        'user' => 'user',
        'password' => 'password',
        'dsn' => 'pgsql:host=db;dbname=ai_db'
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
        'base_url' => 'https://raw.githubusercontent.com/docyx/pc-part-dataset/main/data/csv/',

        // Configuración para fuente local
        'local_path' => __DIR__ . '/../data/',

        // Archivos a procesar
        'files' => [
            'cpu.csv',
            'video-card.csv',
            'motherboard.csv',
            'memory.csv',
            'monitor.csv'
        ],
        'import_limit' => 10, // Límite de filas por archivo
        'access_key' => '12345' // Clave de seguridad para data.php
    ],
    'app' => [
        'name' => 'Analizador de Componentes PC',
        'search_limit' => 5 // Número de resultados a mostrar
    ],
    'auth' => [
        'session_name' => 'analizer_pc_session'
        // Las credenciales ahora se gestionan en la base de datos (tabla users)
        // Usuario por defecto: admin / admin123
    ]
];
