# Correcciones en la Importación de Datos con Embeddings de Gemini

## Problemas Encontrados y Solucionados

### 1. **Error en DataImporter: Parámetro incorrecto en insert()**
**Problema:** El método `processRowDirectly()` en `DataImporter.php` estaba pasando `$filename` directamente como primer parámetro al método `insert()` del modelo `Component`, pero este método espera `$categoria` como primer parámetro.

**Solución:** Se modificó `DataImporter.php` para extraer la categoría del nombre del archivo (eliminando la extensión `.csv`) antes de llamar a `insert()`.

```php
// Antes
$this->componentModel->insert($filename, $nombre, $detalles, $embedding);

// Después
$categoria = str_replace('.csv', '', $filename);
$this->componentModel->insert($categoria, $nombre, $detalles, $embedding);
```

**Archivo modificado:** `src/Services/Data/DataImporter.php` (línea 73-77)

---

### 2. **Dimensiones incorrectas del vector según el proveedor**
**Problema:** La configuración tenía un valor fijo de `VECTOR_DIMENSION=4096` que es correcto para Ollama, pero Gemini usa el modelo `text-embedding-004` que genera embeddings de **768 dimensiones**.

**Solución:** Se modificó `config/config.php` para detectar automáticamente las dimensiones correctas según el proveedor configurado:
- **Gemini (text-embedding-004):** 768 dimensiones
- **Ollama (llama3):** 4096 dimensiones

```php
'vector_dimension' => (int) ($_ENV['VECTOR_DIMENSION'] ?? (
    ($_ENV['EMBEDDING_PROVIDER'] ?? 'ollama') === 'gemini' ? 768 : 4096
))
```

**Archivo modificado:** `config/config.php` (línea 17-25)

---

### 3. **Configuración cacheada en Database::initializeTable()**
**Problema:** El método `initializeTable()` usaba `$this->config` que se carga una vez en el constructor del singleton `Database`. Esto causaba que la tabla se creara con las dimensiones incorrectas si la configuración cambiaba.

**Solución:** Se modificó `initializeTable()` para recargar la configuración cada vez que se ejecuta, asegurando que use las dimensiones correctas.

```php
public function initializeTable(): void
{
    // Recargar configuración para obtener las dimensiones correctas
    $config = require __DIR__ . '/../../config/config.php';
    $embeddingSize = $config['ai']['vector_dimension'];
    // ...
}
```

**Archivo modificado:** `src/Models/Database.php` (línea 51-55)

---

### 4. **EmbeddingFactory usando getenv() en lugar de $_ENV**
**Problema:** La factoría `EmbeddingFactory` usaba `getenv()` para obtener las variables de entorno, pero Dotenv carga las variables en `$_ENV`. Dependiendo de la configuración de PHP, `getenv()` puede no ver estas variables, causando que siempre se usara Ollama en lugar de Gemini.

**Solución:** Se modificó `EmbeddingFactory::create()` para priorizar `$_ENV` sobre `getenv()`.

```php
// Antes
$provider = getenv('EMBEDDING_PROVIDER') ?: 'ollama';

// Después
$provider = $_ENV['EMBEDDING_PROVIDER'] ?? getenv('EMBEDDING_PROVIDER') ?: 'ollama';
```

**Archivo modificado:** `src/Services/Ai/EmbeddingFactory.php` (línea 16, 21)

---

## Archivos Modificados

1. `src/Services/Data/DataImporter.php` - Corrección del parámetro categoria
2. `config/config.php` - Detección automática de dimensiones
3. `src/Models/Database.php` - Recarga de configuración en initializeTable()
4. `src/Services/Ai/EmbeddingFactory.php` - Uso de $_ENV en lugar de getenv()
5. `.env.example` - Documentación actualizada sobre VECTOR_DIMENSION

## Scripts de Prueba Creados

Para facilitar el diagnóstico y testing, se crearon los siguientes scripts en `bin/`:

- `test_gemini.php` - Prueba el servicio de Gemini y generación de embeddings
- `test_import.php` - Prueba la importación de datos
- `test_import_verbose.php` - Prueba de importación con output detallado
- `check_config.php` - Verifica la configuración de dimensiones
- `check_dimensions.php` - Verifica las dimensiones del embedding generado
- `check_table.php` - Verifica la estructura de la tabla en la base de datos
- `debug_env.php` - Diagnóstico de carga de variables de entorno

## Cómo Usar

### Con Gemini (recomendado para producción)
1. Configurar en `.env`:
   ```
   EMBEDDING_PROVIDER=gemini
   GEMINI_API_KEY=tu_clave_api_aqui
   # VECTOR_DIMENSION se auto-detecta como 768
   ```

2. Ejecutar importación:
   ```bash
   docker exec php-app php bin/test_import.php
   ```

### Con Ollama (para desarrollo local)
1. Configurar en `.env`:
   ```
   EMBEDDING_PROVIDER=ollama
   # VECTOR_DIMENSION se auto-detecta como 4096
   ```

2. Asegurarse de que Ollama esté corriendo:
   ```bash
   docker-compose --profile local-ai up -d
   ```

## Verificación

Para verificar que todo funciona correctamente:

```bash
# Verificar configuración
docker exec php-app php bin/check_config.php

# Verificar servicio de embeddings
docker exec php-app php bin/check_dimensions.php

# Probar importación
docker exec php-app php bin/test_import_verbose.php
```

## Notas Importantes

- La variable `VECTOR_DIMENSION` en `.env` es **opcional**. Si no se especifica, se detecta automáticamente según el proveedor.
- Si cambias de proveedor (de Ollama a Gemini o viceversa), debes **reinicializar la tabla** porque las dimensiones del vector son diferentes.
- Los embeddings de Gemini (768 dim) y Ollama (4096 dim) **no son compatibles** entre sí en la misma tabla.
