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
        'base_url' => 'https://raw.githubusercontent.com/docyx/pc-part-dataset/main/data/csv/',
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
    ]
];
