# 📦 PSR-4 Autoloading - Guía Completa

## ¿Qué es PSR-4?

**PSR-4** es un estándar de PHP que define cómo organizar y cargar automáticamente las clases de tu proyecto. Es parte de las **PHP Standard Recommendations** (PSR) del PHP-FIG (PHP Framework Interop Group).

## Ventajas de PSR-4

✅ **Autoloading automático**: No necesitas `require` o `include` manual
✅ **Organización clara**: Estructura de carpetas predecible
✅ **Rendimiento optimizado**: Carga solo las clases que necesitas
✅ **Estándar de la industria**: Usado por Laravel, Symfony, etc.
✅ **Compatibilidad**: Funciona con Composer

## Configuración en este Proyecto

### composer.json

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "App\\Tests\\": "tests/"
        }
    }
}
```

### Mapeo de Namespaces

| Namespace | Directorio | Uso |
|-----------|------------|-----|
| `App\` | `src/` | Código de producción |
| `App\Controllers\` | `src/Controllers/` | Controladores |
| `App\Models\` | `src/Models/` | Modelos |
| `App\Tests\` | `tests/` | Tests (solo desarrollo) |

## Cómo Funciona

### Ejemplo 1: Cargar un Controlador

**Clase**: `App\Controllers\HomeController`

**Archivo esperado**: `src/Controllers/HomeController.php`

```php
<?php
namespace App\Controllers;

class HomeController {
    // ...
}
```

**Uso**:
```php
use App\Controllers\HomeController;

$controller = new HomeController();
```

### Ejemplo 2: Cargar un Modelo

**Clase**: `App\Models\Database`

**Archivo esperado**: `src/Models/Database.php`

```php
<?php
namespace App\Models;

class Database {
    // ...
}
```

**Uso**:
```php
use App\Models\Database;

$db = Database::getInstance();
```

## Estructura de Archivos PSR-4

```
src/
├── Controllers/
│   ├── HomeController.php       → App\Controllers\HomeController
│   ├── SearchController.php     → App\Controllers\SearchController
│   └── DataController.php       → App\Controllers\DataController
├── Models/
│   ├── Database.php             → App\Models\Database
│   ├── Component.php            → App\Models\Component
│   └── OllamaService.php        → App\Models\OllamaService
└── Views/
    ├── home.php                 (No necesita namespace, es una vista)
    └── search.php
```

## Reglas PSR-4

### 1. Namespace debe coincidir con la ruta

```php
// ✅ CORRECTO
// Archivo: src/Controllers/HomeController.php
namespace App\Controllers;

// ❌ INCORRECTO
// Archivo: src/Controllers/HomeController.php
namespace App\Models; // No coincide con la ruta
```

### 2. Nombre de clase debe coincidir con nombre de archivo

```php
// ✅ CORRECTO
// Archivo: HomeController.php
class HomeController { }

// ❌ INCORRECTO
// Archivo: HomeController.php
class Home { } // Nombre no coincide
```

### 3. Un archivo, una clase

```php
// ✅ CORRECTO
// Archivo: HomeController.php
class HomeController { }

// ❌ INCORRECTO
// Archivo: Controllers.php
class HomeController { }
class SearchController { } // Múltiples clases
```

### 4. Capitalización importa

```php
// ✅ CORRECTO
// Archivo: HomeController.php (con C mayúscula)
class HomeController { }

// ❌ INCORRECTO
// Archivo: homecontroller.php (minúsculas)
class HomeController { } // No coincide
```

## Uso en el Proyecto

### Antes (Sin PSR-4)

```php
// ❌ Autoloader manual
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});
```

### Ahora (Con PSR-4 de Composer)

```php
// ✅ Autoloader de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Importar clases
use App\Controllers\HomeController;
use App\Models\Database;

// Usar directamente
$controller = new HomeController();
$db = Database::getInstance();
```

## Comandos de Composer

### Regenerar Autoloader

```bash
composer dump-autoload
```

### Regenerar con Optimización

```bash
composer dump-autoload -o
```

**Recomendado para producción**: Crea un mapa de clases optimizado.

### Regenerar con Autorización

```bash
composer dump-autoload -a
```

**Más optimizado**: Escanea todas las clases y crea un classmap completo.

## Verificar PSR-4

### Script de Verificación

Ejecuta este comando para verificar que PSR-4 está funcionando:

```bash
php -r "require 'vendor/autoload.php'; var_dump(class_exists('App\Controllers\HomeController'));"
```

**Resultado esperado**: `bool(true)`

## Agregar Nuevas Clases

### Paso 1: Crear el archivo en la ubicación correcta

```
src/Services/EmailService.php
```

### Paso 2: Definir namespace correcto

```php
<?php
namespace App\Services;

class EmailService {
    public function send($to, $subject, $body) {
        // ...
    }
}
```

### Paso 3: Usar la clase

```php
use App\Services\EmailService;

$email = new EmailService();
$email->send('user@example.com', 'Hello', 'World');
```

**No necesitas regenerar el autoloader** - Composer lo hace automáticamente.

## Namespaces Anidados

Puedes crear subestructuras:

```
src/
└── Models/
    ├── Database/
    │   ├── Connection.php    → App\Models\Database\Connection
    │   └── Query.php         → App\Models\Database\Query
    └── Component.php         → App\Models\Component
```

```php
<?php
// src/Models/Database/Connection.php
namespace App\Models\Database;

class Connection {
    // ...
}
```

```php
// Uso
use App\Models\Database\Connection;

$conn = new Connection();
```

## Mejores Prácticas

### 1. Usar `use` para importar

```php
// ✅ RECOMENDADO
use App\Controllers\HomeController;
use App\Models\Database;

$controller = new HomeController();
$db = Database::getInstance();
```

```php
// ❌ NO RECOMENDADO
$controller = new \App\Controllers\HomeController();
$db = \App\Models\Database::getInstance();
```

### 2. Agrupar imports relacionados

```php
// ✅ RECOMENDADO
use App\Controllers\HomeController;
use App\Controllers\SearchController;
use App\Controllers\DataController;

use App\Models\Database;
use App\Models\Component;
```

### 3. Alias para evitar conflictos

```php
use App\Models\Database as AppDatabase;
use External\Library\Database as ExternalDatabase;

$appDb = new AppDatabase();
$extDb = new ExternalDatabase();
```

## Troubleshooting

### Problema: "Class not found"

**Solución**:
1. Verifica que el namespace coincida con la ruta
2. Verifica que el nombre de clase coincida con el archivo
3. Regenera el autoloader: `composer dump-autoload`

### Problema: "Cannot redeclare class"

**Solución**:
1. Verifica que no tengas múltiples clases con el mismo nombre
2. Verifica que no estés usando `require` manual además de autoloading

### Problema: Cambios no se reflejan

**Solución**:
```bash
composer dump-autoload -o
```

## Recursos Adicionales

- [PSR-4 Specification](https://www.php-fig.org/psr/psr-4/)
- [Composer Autoloading](https://getcomposer.org/doc/01-basic-usage.md#autoloading)
- [PHP Namespaces](https://www.php.net/manual/en/language.namespaces.php)

## Resumen

✅ PSR-4 está **completamente configurado** en este proyecto
✅ Todas las clases siguen el estándar PSR-4
✅ Autoloading optimizado con Composer
✅ No necesitas `require` manual para clases del proyecto
✅ Fácil agregar nuevas clases siguiendo la convención

---

**¡Tu proyecto ahora usa PSR-4 de forma profesional!** 🎉
