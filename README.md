# Analizador de Componentes PC con IA

Sistema de búsqueda de componentes de PC usando embeddings vectoriales con Ollama y PostgreSQL (pgvector).

## 🏗️ Arquitectura MVC

El proyecto ha sido reestructurado siguiendo el patrón **Model-View-Controller (MVC)**:

```
analizer-pc/
├── config/
│   └── config.php              # Configuración centralizada
├── src/
│   ├── Controllers/            # Lógica de negocio
│   │   ├── HomeController.php
│   │   ├── SearchController.php
│   │   └── DataController.php
│   ├── Models/                 # Acceso a datos
│   │   ├── Database.php
│   │   ├── Component.php
│   │   └── OllamaService.php
│   └── Views/                  # Presentación
│       ├── layouts/
│       │   └── main.php
│       ├── home.php
│       └── search.php
├── public/
│   └── index.php              # Front Controller (punto de entrada)
├── vendor/                     # Dependencias de Composer
├── .htaccess                  # Reescritura de URLs
└── docker-compose.yaml        # Configuración de Docker
```

## 📋 Componentes del MVC

### Models (Modelos)
- **Database.php**: Gestión de conexión a PostgreSQL con patrón Singleton
- **Component.php**: CRUD de componentes y búsqueda vectorial
- **OllamaService.php**: Interacción con Ollama para embeddings y generación de texto

### Views (Vistas)
- **layouts/main.php**: Layout principal con navegación
- **home.php**: Página de inicio con tests de conexión
- **search.php**: Interfaz de búsqueda de componentes

### Controllers (Controladores)
- **HomeController.php**: Maneja la página principal
- **SearchController.php**: Gestiona búsquedas de componentes
- **DataController.php**: Importación de datos desde CSV

## 🚀 Uso

### Iniciar el proyecto con Docker

```bash
docker-compose up -d
```

### Acceder a las rutas

1. **Página principal** (test de conexiones):
   ```
   http://localhost:8000/
   ```

2. **Buscador de componentes**:
   ```
   http://localhost:8000/search
   ```

3. **Importar datos** (requiere clave):
   ```
   http://localhost:8000/data?key=12345
   ```

## 🔧 Configuración

Edita `config/config.php` para cambiar:
- Credenciales de base de datos
- URL de Ollama
- Modelo de IA a usar
- Límites de importación
- Clave de acceso

## 📦 PSR-4 Autoloading

Este proyecto usa **PSR-4** para autoloading de clases con Composer:

### Namespaces Configurados

| Namespace | Directorio | Uso |
|-----------|------------|-----|
| `App\` | `src/` | Código de producción |
| `App\Controllers\` | `src/Controllers/` | Controladores |
| `App\Models\` | `src/Models/` | Modelos |
| `App\Tests\` | `tests/` | Tests unitarios |

### Ejemplo de Uso

```php
// Importar clases
use App\Controllers\HomeController;
use App\Models\Database;

// Usar directamente (sin require manual)
$controller = new HomeController();
$db = Database::getInstance();
```

### Regenerar Autoloader

Si agregas nuevas clases, regenera el autoloader:

```bash
# En Windows
.\regenerate-autoload.ps1

# En Linux/Mac
./regenerate-autoload.sh

# O manualmente con Composer
composer dump-autoload -o
```

**Documentación completa**: Ver [PSR4_GUIDE.md](PSR4_GUIDE.md)

## 📦 Dependencias

- PHP 8.x
- PostgreSQL con extensión pgvector
- Ollama con modelo llama3
- Composer (guzzlehttp/guzzle)

## 🎯 Ventajas de la arquitectura MVC

1. **Separación de responsabilidades**: Cada capa tiene una función específica
2. **Mantenibilidad**: Código más organizado y fácil de mantener
3. **Reutilización**: Los modelos y vistas pueden reutilizarse
4. **Testabilidad**: Más fácil escribir tests unitarios
5. **Escalabilidad**: Fácil agregar nuevas funcionalidades

## 📝 Archivos antiguos

Los archivos originales (`index.php`, `data.php`, `question.php`) pueden eliminarse ya que su funcionalidad ha sido migrada a la nueva estructura MVC.

## 🔄 Migración desde la versión anterior

La nueva estructura mantiene toda la funcionalidad original pero organizada de forma más profesional:

- `index.php` → `HomeController::index()`
- `data.php` → `DataController::import()`
- `question.php` → `SearchController::index()`
