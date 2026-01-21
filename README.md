# 🔍 Analizador de Películas con IA

Sistema avanzado de búsqueda semántica de películas utilizando **IA (Embeddings)**, **PostgreSQL (pgvector)** y una arquitectura **MVC** robusta.

## 🌟 Características Principales

- **Búsqueda Semántica**: Encuentra películas por descripción natural (ej: "película de ciencia ficción con viajes en el tiempo") gracias a Ollama o Gemini.
- **Arquitectura MVC**: Separación clara de responsabilidades para un código mantenible y escalable.
- **Sistema de Autenticación**: Gestión de usuarios y sesiones segura.
- **Exportación Multiformato**: Genera informes de resultados en **JSON, XML, CSV y PDF**.
- **CI/CD Integrado**: Pipeline de GitHub Actions para pruebas automáticas con Docker.
- **Diseño Moderno**: Interfaz limpia inspirada en Google, con CSS moderno y layouts organizados.

## 🏗️ Estructura del Proyecto

```text
analizer-pc/
├── .github/workflows/      # CI/CD (GitHub Actions)
├── bin/                    # Scripts de utilidad (init-db.php, init_users.php)
├── config/                 # Configuración centralizada
├── data/                   # Datos locales (CSV) para importación
├── public/                 # Punto de entrada (index.php) y assets
├── src/
│   ├── Controllers/        # Lógica de control (Auth, Search, Data, etc.)
│   ├── Enums/              # Enumeraciones (SessionKey)
│   ├── Models/             # Modelos de datos
│   ├── Services/           # Servicios de IA, Datos y Exportación
│   └── Views/              # Plantillas y layouts
├── tests/                  # Suite de pruebas (Unitarias y Feature)
├── compose.yaml            # Orquestación de contenedores
└── Dockerfile              # Configuración de la imagen PHP
```

## 🚀 Instalación y Uso Rápido

### Requisitos
- Docker y Docker Compose
- API Key de Gemini (opcional) u Ollama local.

### 1. Configuración
Copia el archivo de ejemplo y configura tus variables:
```bash
cp .env.example .env
```

### 2. Iniciar con Docker
Si usas **Ollama (Local)**, debes activar el perfil correspondiente:
```bash
docker compose --profile local-ai up -d --build
```

Si usas **Gemini (Remoto)**, basta con:
```bash
docker compose up -d --build
```

### 3. Inicializar la Base de Datos y Usuarios
```bash
docker exec php-app php bin/init-db.php
docker exec php-app php bin/init_users.php
```

### 4. Acceso
- **Web**: [http://localhost:8000](http://localhost:8000)
- **Login**: admin / admin123 (por defecto)

## 🛠️ Guía de Comandos Útiles

### 🧪 Testing
El proyecto incluye scripts simplificados para ejecutar pruebas:

**En Windows (PowerShell):**
```powershell
.\run-tests.ps1 all      # Ejecutar todos los tests
.\run-tests.ps1 unit     # Solo tests unitarios
.\run-tests.ps1 feature  # Solo tests de integración
```

**En Linux/Mac (Bash):**
```bash
./run-tests.sh all
```

### 🐳 Gestión de Docker
| Acción | Comando |
|--------|---------|
| **Levantar todo** | `docker compose up -d` |
| **Levantar y reconstruir** | `docker compose up -d --build` |
| **Detener contenedores** | `docker compose stop` |
| **Apagar y eliminar redes** | `docker compose down` |
| **Ver logs en tiempo real** | `docker compose logs -f` |
| **Entrar a la consola PHP** | `docker exec -it php-app bash` |

### 🗄️ Base de Datos y Usuarios
| Acción | Comando |
|--------|---------|
| **Inicializar DB (Tablas)** | `docker exec php-app php bin/init-db.php` |
| **Crear usuario admin** | `docker exec php-app php bin/init_users.php` |
| **Verificar estructura MVC** | `docker exec php-app php bin/test_mvc.php` |

### 📦 Composer y Dependencias
| Acción | Comando |
|--------|---------|
| **Instalar dependencias** | `docker exec php-app composer install` |
| **Añadir nueva librería** | `docker exec php-app composer require <nombre>` |
| **Regenerar autoloader** | `docker exec php-app composer dump-autoload -o` |

## ⚙️ Configuración (.env)
- `EMBEDDING_PROVIDER`: `gemini` o `ollama`.
- `GEMINI_API_KEY`: Requerido si el proveedor es `gemini`.
- `DATA_SOURCE`: `github` (remoto) o `local`.
- `VECTOR_DIMENSION`: Opcional. Se detecta automáticamente (768 para Gemini, 4096 para Ollama).

## 📈 CI/CD
Cada `push` dispara un flujo en GitHub Actions que valida el entorno, instala dependencias y ejecuta la suite de tests completa.

---
Desarrollado por **Sergio RP Leon**
