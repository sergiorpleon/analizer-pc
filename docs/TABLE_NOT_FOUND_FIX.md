# 🔧 Error: Tabla "componentes_pc" no existe

## ❌ Error

```
SQLSTATE[42P01]: Undefined table: 7 ERROR: 
relation "componentes_pc" does not exist
```

## 🎯 Causa

La tabla `componentes_pc` **no existe** porque aún no has importado datos.

## ✅ Solución: Importar Datos

### Paso 1: Iniciar Sesión

1. Ve a: `http://localhost:8000/login`
2. Ingresa credenciales:
   - **Usuario:** `admin`
   - **Contraseña:** `admin123`
3. Haz clic en "Iniciar Sesión"

### Paso 2: Importar Datos

1. Una vez logueado, ve a: `http://localhost:8000/data`
2. Verás el proceso de importación:
   ```
   Iniciando poblamiento de Base de Datos...
   ✅ Tabla inicializada correctamente.
   
   Iniciando importación de componentes...
   Fuente de datos: URL
   
   Procesando cpu.csv...
   ✅ Importado: Intel Core i9-13900K
   ✅ Importado: AMD Ryzen 9 7950X
   ...
   ```

3. **Espera** a que termine (5-10 minutos)
4. Verás: `¡Base de datos cargada con éxito!`

### Paso 3: Usar el Buscador

Ahora sí puedes buscar:

1. Ve a: `http://localhost:8000/search`
2. Busca: "procesador para gaming barato"
3. Verás resultados con componentes similares

---

## 🚀 Inicio Rápido (Paso a Paso)

```bash
# 1. Verificar que los servicios estén corriendo
docker-compose ps

# 2. Si no están corriendo, iniciarlos
docker-compose up -d

# 3. Verificar logs (opcional)
docker-compose logs -f app
```

**Luego en el navegador:**

1. **Login:** `http://localhost:8000/login`
   - Usuario: `admin`
   - Contraseña: `admin123`

2. **Importar:** `http://localhost:8000/data`
   - Espera a que termine

3. **Buscar:** `http://localhost:8000/search`
   - Prueba búsquedas

---

## 📊 Flujo Correcto

```
1. Login (/login)
   ↓
2. Importar Datos (/data)
   ↓ (esperar 5-10 min)
3. Buscar (/search)
   ↓
4. Ver Resultados ✅
```

---

## ⚠️ Importante

- ❌ **NO puedes buscar** sin importar datos primero
- ✅ **Debes estar logueado** para importar datos
- ⏳ **La importación tarda** 5-10 minutos (genera embeddings con IA)
- 📊 **Se importan** 10 componentes por archivo (configurable)

---

## 🔍 Verificar que la Tabla Existe

Después de importar, puedes verificar:

```bash
# Conectar a PostgreSQL
docker-compose exec db psql -U user -d ai_db

# Ver tablas
\dt

# Ver datos
SELECT COUNT(*) FROM componentes_pc;

# Salir
\q
```

**Resultado esperado:**
```
 count 
-------
    50
(1 row)
```

---

## 🎨 Configurar Importación

Puedes ajustar cuántos componentes importar en `config/config.php`:

```php
'data' => [
    'import_limit' => 10,  // Cambiar a 20, 50, 100, etc.
]
```

**Nota:** Más componentes = más tiempo de importación.

---

## 📝 Resumen

1. ✅ **Inicia sesión** primero
2. ✅ **Importa datos** (crea la tabla)
3. ✅ **Busca componentes**

**Estado actual:** ❌ Tabla no existe → ✅ Importar datos

---

## 🆘 Problemas Comunes

### "No puedo acceder a /data"

**Solución:** Debes estar logueado primero.

### "La importación es muy lenta"

**Solución:** Es normal, genera embeddings con IA para cada componente.

### "Error de conexión a Ollama"

**Solución:**
```bash
docker-compose restart ollama
docker-compose exec ollama ollama list
```

---

**¡Importa los datos y podrás buscar!** 🚀
