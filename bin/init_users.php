<?php
/**
 * Script para inicializar la tabla de usuarios
 * Ejecutar: docker-compose exec app php init_users.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

use App\Models\User;

echo "🔧 Inicializando tabla de usuarios...\n\n";

try {
    $userModel = new User();
    $userModel->initializeTable();

    echo "✅ Tabla 'users' creada exitosamente\n";
    echo "✅ Usuario admin creado (si no existía)\n\n";

    echo "📋 Credenciales por defecto:\n";
    echo "   Usuario: admin\n";
    echo "   Contraseña: admin123\n\n";

    echo "⚠️  IMPORTANTE: Cambia la contraseña en producción\n\n";

    // Mostrar usuarios existentes
    $users = $userModel->getAll();
    echo "👥 Usuarios en la base de datos:\n";
    foreach ($users as $user) {
        echo "   - {$user['username']} ({$user['email']}) - Creado: {$user['created_at']}\n";
    }

} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ Inicialización completada\n";
