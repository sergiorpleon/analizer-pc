# Instrucciones de Desarrollo - Proyecto Agente Vectorial PHP

## Contexto del Proyecto
Agente de información técnica basado en PHP 8.2+ con arquitectura MVC y principios SOLID. El sistema es multi-proveedor (IA y Datos) y se gestiona mediante variables de entorno.

## Reglas de Arquitectura
1. **Patrón MVC**: Separación estricta entre Controladores, Modelos y Vistas.
2. **SOLID & Design Patterns**:
   - **Strategy Pattern**: Para proveedores de IA (`EmbeddingServiceInterface`) y fuentes de datos (`DataSourceInterface`).
   - **Factory Pattern**: Para instanciar servicios dinámicamente según el entorno.
   - **Dependency Inversion**: Los controladores dependen de interfaces, no de implementaciones concretas.
3. **Multi-Proveedor**:
   - **IA**: Soporte para Ollama (Local) y Gemini (Remoto).
   - **Datos**: Soporte para Local (Filesystem) y Remoto (GitHub API).

## Tecnologías Específicas
- **Lenguaje**: PHP 8.2+ con tipado estricto (`declare(strict_types=1);`).
- **Características PHP**: Constructor Property Promotion, Readonly Properties, Enums.
- **Base de Datos**: PostgreSQL con extensión `pgvector`.
- **Docker**: Orquestación con perfiles (`local-ai` para Ollama).
- **Validación**: El sistema debe validar la integridad del entorno (`EnvironmentValidator`) al arrancar.

## Estructura del Proyecto
- **bin/**: Scripts de utilidad y mantenimiento.
- **src/**:
  - **Controllers/**: Gestión de peticiones y flujo de datos.
  - **Models/**: Representación de datos y acceso a DB.
  - **Services/**:
    - **Ai/**: Implementaciones de `EmbeddingServiceInterface`.
    - **Data/**: Implementaciones de `DataSourceInterface` e importadores.
    - **Validation/**: Lógica de salud del entorno.
  - **Views/**: Plantillas HTML con Tailwind CSS.
- **public/**: Punto de entrada (`index.php`) y assets.
- **tests/**: Suite de pruebas unitarias y de integración.

## Configuración de Entorno (.env)
- `EMBEDDING_PROVIDER`: `ollama` | `gemini`
- `DATA_SOURCE`: `local` | `github`
- `GEMINI_API_KEY`: Requerida si el proveedor es `gemini`.
- `OLLAMA_URL`: URL del servicio Ollama (default: http://ollama:11434).
