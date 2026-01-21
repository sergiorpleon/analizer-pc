# 🔍 Analizador de Componentes PC con IA

Sistema avanzado de búsqueda semántica de componentes de PC utilizando **IA (Embeddings)**, **PostgreSQL (pgvector)** y una arquitectura **MVC** robusta.

## 🌟 Características Principales

- **Búsqueda Semántica**: Encuentra componentes por descripción natural (ej: "procesador para gaming barato") gracias a Ollama o Gemini.
- **Arquitectura MVC**: Separación clara de responsabilidades para un código mantenible y escalable.
- **Sistema de Autenticación**: Gestión de usuarios y sesiones segura.
- **Exportación Multiformato**: Genera informes de resultados en **JSON, XML, CSV y PDF**.
- **CI/CD Integrado**: Pipeline de GitHub Actions para pruebas automáticas con Docker.
- **Diseño Moderno**: Interfaz limpia con CSS moderno y layouts organizados.

## 🏗️ Estructura del Proyecto

```text
analizer-pc/
├── .github/workflows/      # CI/CD (GitHub Actions)
├── bin/                    # Scripts de utilidad (init-db.php, init_users.php)
├── config/                 # Configuración centralizada
├── public/                 # Punto de entrada (index.php) y assets
├── src/
│   ├── Controllers/        # Lógica de control (Auth, Search, Data, etc.)
│   ├── Enums/              # Enumeraciones (SessionKey)
│   ├── Models/             # Modelos de datos y servicios core
│   ├── Services/           # Servicios de IA y Exportación (SOLID)
│   └── Views/              # Plantillas y layouts
├── tests/                  # Suite de pruebas (Unitarias y Feature)
├── compose.yaml            # Orquestación de contenedores
└── Dockerfile              # Configuración de la imagen PHP
```

## 🚀 Instalación y Uso Rápido

### Requisitos
- Docker y Docker Compose
- API Key de Gemini (opcional) u Ollama local.


### 1. Iniciar con Docker
Si usas **Ollama (Local)**, debes activar el perfil correspondiente:
```bash
docker compose --profile local-ai up -d --build
```

Si usas **Gemini (Remoto)**, basta con:
```bash
docker compose up -d --build
```

### 2. Inicializar la Base de Datos y Usuarios
```bash
docker exec php-app php bin/init-db.php
docker exec php-app php bin/init_users.php
```

### 3. Acceso
- **Web**: [http://localhost:8000](http://localhost:8000)
- **Login**: admin / admin123 (por defecto)



## 🛠️ Guía de Comandos Útiles

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

### 🧪 Testing
| Acción | Comando |
|--------|---------|
| **Ejecutar todos los tests** | `docker exec php-app ./vendor/bin/phpunit` |
| **Tests con formato legible** | `docker exec php-app ./vendor/bin/phpunit --testdox` |
| **Ejecutar un test específico** | `docker exec php-app ./vendor/bin/phpunit tests/Feature/SearchControllerTest.php` |

### 📦 Composer y Dependencias
| Acción | Comando |
|--------|---------|
| **Instalar dependencias** | `docker exec php-app composer install` |
| **Añadir nueva librería** | `docker exec php-app composer require <nombre>` |
| **Regenerar autoloader** | `docker exec php-app composer dump-autoload -o` |

## ⚙️ Configuración (.env)
El proyecto utiliza un archivo `.env` para gestionar información sensible. Asegúrate de configurar:
- `GEMINI_API_KEY`: Tu clave de Google AI.
- `EMBEDDING_PROVIDER`: `gemini` o `ollama`.
- `VECTOR_DIMENSION`: `768` para Gemini o `4096` para Ollama.

## 🛠️ Tecnologías Utilizadas
- **Backend**: PHP 8.0+
- **Base de Datos**: PostgreSQL + pgvector
- **IA**: Google Gemini API / Ollama (Llama3)
- **Librerías**: GuzzleHttp, Dompdf, PHPUnit, PHP Dotenv

## 📈 CI/CD
Cada `push` a este repositorio dispara un flujo de trabajo en GitHub Actions que:
1. Levanta el entorno completo en Docker.
2. Instala dependencias con Composer.
3. Inicializa la base de datos.
4. Ejecuta la suite de tests completa.

---
Desarrollado por **Sergio RP Leon**
