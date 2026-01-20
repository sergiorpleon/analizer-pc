# 🧪 Guía de Pruebas - Arquitectura MVC

## Verificar la Estructura

### 1. Verificar archivos creados

Ejecuta este comando para ver la estructura:

```bash
tree /F src
```

Deberías ver:
```
src/
├── Controllers/
│   ├── DataController.php
│   ├── HomeController.php
│   └── SearchController.php
├── Models/
│   ├── Component.php
│   ├── Database.php
│   └── OllamaService.php
└── Views/
    ├── home.php
    ├── search.php
    └── layouts/
        └── main.php
```

## Probar el Proyecto

### 1. Iniciar Docker

```bash
docker-compose up -d
```

Espera a que todos los servicios estén listos (puede tomar 1-2 minutos la primera vez).

### 2. Verificar servicios

```bash
docker-compose ps
```

Deberías ver 3 servicios corriendo:
- `web` (Apache + PHP)
- `db` (PostgreSQL)
- `ollama` (Ollama AI)

### 3. Probar las rutas

#### A. Página Principal (Tests de Conexión)
```
http://localhost:8000/
```

**Resultado esperado:**
- ✅ Conexión a Postgres y PGVector exitosa
- ✅ Ollama responde: [mensaje de saludo]

#### B. Buscador de Componentes
```
http://localhost:8000/search
```

**Resultado esperado:**
- Formulario de búsqueda visible
- Al buscar (ej: "procesador gaming"), debería mostrar resultados si hay datos

#### C. Importar Datos
```
http://localhost:8000/data?key=12345
```

**Resultado esperado:**
- Mensaje: "Iniciando poblamiento de Base de Datos..."
- Procesamiento de archivos CSV
- Importación de componentes con embeddings
- Mensaje final: "¡Base de datos cargada con éxito!"

**Nota:** Este proceso puede tardar varios minutos dependiendo de la velocidad de Ollama.

### 4. Probar búsqueda con datos

Después de importar datos:

1. Ve a: `http://localhost:8000/search`
2. Escribe: "procesador para gaming barato"
3. Haz clic en "Buscar"

**Resultado esperado:**
- Lista de componentes similares
- Cada resultado muestra:
  - Nombre del componente
  - Categoría
  - Similitud (0-1)
  - Detalles completos

## Verificar Logs

### Ver logs de Apache/PHP
```bash
docker-compose logs web
```

### Ver logs de PostgreSQL
```bash
docker-compose logs db
```

### Ver logs de Ollama
```bash
docker-compose logs ollama
```

## Solución de Problemas

### Problema: "Acceso no autorizado"
**Solución:** Asegúrate de incluir `?key=12345` en la URL de importación.

### Problema: Error de conexión a BD
**Solución:** 
```bash
docker-compose restart db
docker-compose restart web
```

### Problema: Ollama no responde
**Solución:**
```bash
# Verificar que Ollama está corriendo
docker-compose exec ollama ollama list

# Si no está el modelo, descargarlo
docker-compose exec ollama ollama pull llama3
```

### Problema: 404 en las rutas
**Solución:** Verifica que `.htaccess` existe y que `mod_rewrite` está habilitado en Apache.

## Tests Manuales

### Test 1: Verificar Database Singleton
Accede dos veces a la página principal. La conexión a BD debería reutilizarse (patrón Singleton).

### Test 2: Verificar búsqueda vectorial
Busca términos similares:
- "procesador rápido"
- "CPU veloz"
- "chip potente"

Deberían devolver resultados similares.

### Test 3: Verificar vistas
Inspecciona el HTML generado. Deberías ver:
- Layout principal con navegación
- Estilos CSS inline
- Estructura semántica

## Comandos Útiles

### Reiniciar todo
```bash
docker-compose down
docker-compose up -d
```

### Ver estado de contenedores
```bash
docker-compose ps
```

### Acceder al contenedor web
```bash
docker-compose exec web bash
```

### Acceder a PostgreSQL
```bash
docker-compose exec db psql -U user -d ai_db
```

Dentro de PostgreSQL:
```sql
-- Ver componentes
SELECT COUNT(*) FROM componentes_pc;

-- Ver categorías
SELECT DISTINCT categoria FROM componentes_pc;

-- Ver un componente
SELECT nombre, categoria FROM componentes_pc LIMIT 1;
```

## Checklist de Funcionalidad

- [ ] Página principal carga correctamente
- [ ] Tests de conexión muestran ✅
- [ ] Formulario de búsqueda es visible
- [ ] Importación de datos funciona
- [ ] Búsqueda devuelve resultados
- [ ] Navegación entre páginas funciona
- [ ] Estilos CSS se aplican correctamente
- [ ] No hay errores en logs

## Próximos Pasos

Una vez que todo funcione:

1. ✅ Eliminar archivos `*_old.php`
2. ✅ Agregar más categorías de componentes en `config/config.php`
3. ✅ Personalizar estilos en `src/Views/layouts/main.php`
4. ✅ Agregar validación de formularios
5. ✅ Implementar paginación de resultados
6. ✅ Agregar caché para búsquedas

---

**¡Disfruta tu nueva arquitectura MVC!** 🎉
