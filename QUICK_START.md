# 🎯 Guía Rápida - Nuevas Funcionalidades

## ✅ Funcionalidades Implementadas

### 1. 🧪 Testing con PHPUnit

```bash
# Ejecutar tests
docker-compose exec app composer test

# Ver resultados con formato testdox
docker-compose exec app vendor/bin/phpunit --testdox
```

### 2. 📁 Datos Locales o Externos

**Usar datos desde URL (por defecto):**
- No requiere configuración
- Descarga automática desde GitHub

**Usar datos locales:**
1. Coloca archivos CSV en `data/`
2. Edita `config/config.php`:
   ```php
   'source' => 'local'
   ```
3. Reinicia: `docker-compose restart app`

### 3. 🔐 Autenticación

**Credenciales:**
- Usuario: `admin`
- Contraseña: `admin123`

**Rutas:**
- Login: `http://localhost:8000/login`
- Logout: `http://localhost:8000/logout`
- Importar datos (requiere login): `http://localhost:8000/data`

## 🚀 Inicio Rápido

```bash
# 1. Levantar servicios
docker-compose up -d

# 2. Verificar que estén corriendo
docker-compose ps

# 3. Acceder a la aplicación
# http://localhost:8000/

# 4. Iniciar sesión
# http://localhost:8000/login
# Usuario: admin
# Contraseña: admin123

# 5. Importar datos
# http://localhost:8000/data

# 6. Ejecutar tests
docker-compose exec app composer test
```

## 📝 Archivos Importantes

- **`NEW_FEATURES.md`** - Documentación completa
- **`phpunit.xml`** - Configuración de tests
- **`tests/`** - Tests unitarios y de feature
- **`data/`** - Carpeta para CSVs locales
- **`config/config.php`** - Configuración de auth y data sources

## 🔒 Seguridad

⚠️ **CAMBIAR EN PRODUCCIÓN:**

Edita `config/config.php`:

```php
'auth' => [
    'admin' => [
        'username' => 'admin',
        'password' => password_hash('TU_PASSWORD_SEGURO', PASSWORD_BCRYPT)
    ]
]
```

## 📊 Estructura de Tests

```
tests/
├── Unit/
│   └── DatabaseTest.php      # Test del Singleton
└── Feature/
    └── HomeControllerTest.php # Test del controlador
```

## 🎨 UI Actualizada

La navegación ahora muestra:

**Sin autenticar:**
```
[Inicio] [Buscar]                    [Iniciar Sesión]
```

**Autenticado:**
```
[Inicio] [Buscar] [Importar]  [👤 admin] [Cerrar Sesión]
```

## ✅ Checklist

- [x] PHPUnit configurado
- [x] Tests creados
- [x] Soporte datos locales/externos
- [x] Sistema de login/logout
- [x] Protección de ruta /data
- [x] UI actualizada
- [x] Documentación completa

---

**¡Todo listo para usar!** 🚀
