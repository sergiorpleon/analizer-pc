# 🔧 Solución: Error "model llama3 not found"

## ❌ Error

```
Error al generar embedding: Client error: `POST http://ollama:11434/api/embeddings` 
resulted in a `404 Not Found` response: 
{"error":"model \"llama3\" not found, try pulling it first"}
```

## 🎯 Causa

El modelo `llama3` no está descargado en el contenedor de Ollama.

## ✅ Solución

### Opción 1: Descargar el Modelo (Recomendado)

```bash
# Descargar llama3 (4.7 GB - tarda unos minutos)
docker-compose exec ollama ollama pull llama3
```

**Espera a que termine la descarga.** Verás algo como:
```
pulling manifest
pulling 6a0746a1ec1a: 100%
pulling 4fa551d4f938: 100%
pulling 8ab4849b038c: 100%
...
success
```

### Opción 2: Usar un Modelo Más Pequeño

Si `llama3` es muy grande, puedes usar un modelo más pequeño:

#### 1. Descargar un modelo alternativo

```bash
# Llama3.2 (2 GB - más pequeño)
docker-compose exec ollama ollama pull llama3.2

# O Gemma 2B (1.4 GB - aún más pequeño)
docker-compose exec ollama ollama pull gemma:2b
```

#### 2. Actualizar la configuración

Edita **`config/config.php`**:

```php
'ollama' => [
    'url' => 'http://ollama:11434',
    'model' => 'llama3.2',  // o 'gemma:2b'
    'embedding_size' => 4096
],
```

#### 3. Reiniciar la aplicación

```bash
docker-compose restart app
```

### Opción 3: Verificar Modelos Disponibles

```bash
# Ver qué modelos están instalados
docker-compose exec ollama ollama list
```

## 🔍 Verificar que Funciona

Después de descargar el modelo:

1. **Accede a la página principal:**
   ```
   http://localhost:8000/
   ```

2. **Deberías ver:**
   ```
   ✅ Ollama responde: [mensaje de saludo]
   ```

3. **Prueba el buscador:**
   ```
   http://localhost:8000/search
   ```

## 📊 Modelos Disponibles y Tamaños

| Modelo | Tamaño | Velocidad | Calidad |
|--------|--------|-----------|---------|
| `llama3` | 4.7 GB | Media | Alta |
| `llama3.2` | 2.0 GB | Rápida | Buena |
| `llama3.2:1b` | 1.3 GB | Muy rápida | Media |
| `gemma:2b` | 1.4 GB | Muy rápida | Media |
| `phi3` | 2.3 GB | Rápida | Buena |

## 🚀 Comandos Útiles de Ollama

```bash
# Listar modelos instalados
docker-compose exec ollama ollama list

# Descargar un modelo
docker-compose exec ollama ollama pull <modelo>

# Eliminar un modelo
docker-compose exec ollama ollama rm <modelo>

# Probar un modelo
docker-compose exec ollama ollama run llama3 "Hola, ¿cómo estás?"

# Ver logs de Ollama
docker-compose logs ollama
```

## 🔄 Proceso de Descarga

La descarga de `llama3` puede tardar:
- **Conexión rápida (100 Mbps)**: 5-10 minutos
- **Conexión media (50 Mbps)**: 10-15 minutos
- **Conexión lenta (10 Mbps)**: 30-60 minutos

**Progreso típico:**
```
pulling manifest
pulling 6a0746a1ec1a:  10% ▕██        ▏ 470 MB/4.7 GB
pulling 6a0746a1ec1a:  50% ▕██████    ▏ 2.3 GB/4.7 GB
pulling 6a0746a1ec1a: 100% ▕██████████▏ 4.7 GB/4.7 GB
pulling 4fa551d4f938: 100%
pulling 8ab4849b038c: 100%
success
```

## ⚠️ Problemas Comunes

### 1. "Error: connection refused"

**Solución:**
```bash
# Verificar que Ollama esté corriendo
docker-compose ps ollama

# Si no está corriendo, iniciarlo
docker-compose up -d ollama
```

### 2. "Error: out of disk space"

**Solución:**
- Libera espacio en disco (necesitas ~5 GB libres)
- O usa un modelo más pequeño (ver Opción 2)

### 3. Descarga muy lenta

**Solución:**
```bash
# Detener y reiniciar la descarga
docker-compose exec ollama ollama pull llama3
```

## 📝 Resumen

1. ✅ **Ejecuta:** `docker-compose exec ollama ollama pull llama3`
2. ⏳ **Espera** a que termine la descarga (5-15 minutos)
3. 🔄 **Recarga** la página: `http://localhost:8000/`
4. ✅ **Verifica** que Ollama responde correctamente

---

**Estado actual:** El modelo se está descargando. Espera a que termine y luego recarga la página.
