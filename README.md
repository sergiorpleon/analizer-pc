# 🔍 Analizador de Componentes PC con IA

Sistema avanzado de búsqueda semántica de componentes de PC utilizando **IA (Embeddings)**, **PostgreSQL (pgvector)** y una arquitectura **MVC** robusta.

## 🌟 Características Principales

- **Búsqueda Semántica**: Encuentra componentes por descripción natural (ej: "procesador para gaming barato") gracias a Ollama.
- **Arquitectura MVC**: Separación clara de responsabilidades para un código mantenible y escalable.
- **Sistema de Autenticación**: Gestión de usuarios y sesiones segura.
- **Exportación Multiformato**: Genera informes de resultados en **JSON, XML, CSV y PDF**.
- **CI/CD Integrado**: Pipeline de GitHub Actions para pruebas automáticas con Docker.
- **Diseño Moderno**: Interfaz limpia con CSS moderno y layouts organizados.

## 🏗️ Estructura del Proyecto

```text
analizer-pc/
├── .github/workflows/      # CI/CD (GitHub Actions)
├── bin/                    # Scripts de utilidad (init-db.php)
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
- Ollama (opcional si se usa el contenedor incluido)

### 1. Iniciar con Docker
```bash
docker compose up -d --build
```

### 2. Inicializar la Base de Datos
```bash
docker exec php-app php bin/init-db.php
```

### 3. Acceso
- **Web**: [http://localhost:8000](http://localhost:8000)
- **Login**: admin / admin123 (por defecto)

## 🧪 Testing

El proyecto cuenta con una suite completa de tests usando PHPUnit.

```bash
# Ejecutar todos los tests
docker exec php-app ./vendor/bin/phpunit

# Ejecutar con detalles
docker exec php-app ./vendor/bin/phpunit --testdox
```

## 🛠️ Tecnologías Utilizadas

- **Backend**: PHP 8.0+ (Compatible con Enums vía clases de constantes)
- **Base de Datos**: PostgreSQL + pgvector
- **IA**: Ollama (Modelo llama3 por defecto)
- **Librerías**: 
  - GuzzleHttp (Peticiones API)
  - Dompdf (Generación de PDF)
  - PHPUnit (Testing)

## 📈 CI/CD

Cada `push` a este repositorio dispara un flujo de trabajo en GitHub Actions que:
1. Levanta el entorno completo en Docker.
2. Instala dependencias con Composer.
3. Inicializa la base de datos.
4. Ejecuta la suite de tests completa.

---
Desarrollado por **Sergio RP Leon**
