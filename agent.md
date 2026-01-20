# instrucciones de desarrollo - Proyecto Agente Vectorial PHP

## Contexto del proyecto
Estoy construyendo un agente de informacion tecnica usando PHP simple(sin framework pesados) bajo arquitectura PHP manual.

## Reglas de arquitectura
1. **Patron MVC** Separa estrictamente Controladores, Modelos y Vistas
2. **Patron SOLID** Implementa principios SOLID (S - Single Responsibility, O - Open/Closed, L - Liskov Substitution, I - Interface Segregation, D - Dependency Inversion)

## Tecnologias especificas
- **Base de Datos**: Postgres con extension PGVector
- **IA**: Ollama
- **Lenguaje**: PHP 8.2+ con tipado estricto ('declare(strict_types=1);')
- **Composer**: 2.2.15
- **Docker**: Todo debe correr en una red bridge

## Estructura del proyecto
- **app/**: Directorio de la aplicacion
- **vendor/**: Directorio de composer
- **src/**:
  **Core/**          # Routing, Container de dependencias, Config.
  **Controllers/**   # Recepción de inputs y retorno de respuestas (JSON/HTML).
  **Models/**        # Entidades de datos puras (Data Objects).
  **Services/**      # Lógica de negocio (Conector Ollama, Procesamiento RAG).
  **Repositories/**  # Persistencia de datos y lógica de PGVector (SQL).
- **public/**        # Archivos publicos
- **tests/**:
  - **Unit/**: Tests para Services y Models (sin DB, usando Mocks).
  - **Integration/**: Tests que validan la conexión con Ollama y PGVector.
  - **TestCase.php**: Clase base para configurar el entorno de pruebas.
- **data.php**: Script para poblar la base de datos
- **index.php**: Script para buscar en la base de datos
- **question.php**: Script para buscar en la base de datos


