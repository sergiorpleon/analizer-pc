# ✅ Sistema de Usuarios en Base de Datos

## 🎯 Cambio Implementado

El sistema de autenticación ahora usa una **tabla `users` en PostgreSQL** en lugar de credenciales hardcodeadas en `config.php`.

---

## 📊 Tabla `users`

### Estructura

```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Usuario por Defecto

Al inicializar la tabla, se crea automáticamente:

| Campo | Valor |
|-------|-------|
| **username** | `admin` |
| **password** | `admin123` (hasheado con BCrypt) |
| **email** | `admin@example.com` |

---

## 🔧 Archivos Creados/Modificados

### Nuevos Archivos

1. **`src/Models/User.php`** - Modelo para gestionar usuarios
   - `create()` - Crear usuario
   - `findByUsername()` - Buscar usuario
   - `verify()` - Verificar credenciales
   - `updatePassword()` - Cambiar contraseña
   - `getAll()` - Listar usuarios
   - `delete()` - Eliminar usuario

2. **`init_users.php`** - Script de inicialización
   - Crea la tabla `users`
   - Inserta usuario admin por defecto

### Archivos Modificados

1. **`src/Models/Auth.php`**
   - Ahora usa `User` model
   - Verifica contra base de datos
   - Inicializa tabla automáticamente

2. **`config/config.php`**
   - Removidas credenciales hardcodeadas
   - Solo mantiene configuración de sesión

---

## 🚀 Uso

### Inicializar Tabla de Usuarios

```bash
# Ejecutar script de inicialización
docker-compose exec app php init_users.php
```

**Salida:**
```
🔧 Inicializando tabla de usuarios...

✅ Tabla 'users' creada exitosamente
✅ Usuario admin creado (si no existía)

📋 Credenciales por defecto:
   Usuario: admin
   Contraseña: admin123

👥 Usuarios en la base de datos:
   - admin (admin@example.com) - Creado: 2026-01-20 17:15:26
```

### Login

El login ahora verifica contra la base de datos:

```
http://localhost:8000/login
Usuario: admin
Contraseña: admin123
```

---

## 💻 Gestión de Usuarios (Programática)

### Crear Nuevo Usuario

```php
use App\Models\User;

$userModel = new User();
$userModel->create('nuevo_usuario', 'password123', 'email@example.com');
```

### Verificar Credenciales

```php
$user = $userModel->verify('admin', 'admin123');
if ($user) {
    echo "Login exitoso: " . $user['username'];
}
```

### Cambiar Contraseña

```php
$userModel->updatePassword('admin', 'nueva_password_segura');
```

### Listar Usuarios

```php
$users = $userModel->getAll();
foreach ($users as $user) {
    echo $user['username'] . " - " . $user['email'];
}
```

### Eliminar Usuario

```php
$userModel->delete('usuario_a_eliminar');
```

---

## 🔒 Seguridad

### Passwords

- ✅ **Hasheados con BCrypt** (PASSWORD_BCRYPT)
- ✅ **Nunca se retornan** en consultas (excepto para verificación)
- ✅ **Salt automático** generado por PHP

### Cambiar Contraseña de Admin

**Opción 1: Desde código**

Crea un archivo `change_admin_password.php`:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

$userModel = new User();
$userModel->updatePassword('admin', 'TU_NUEVA_PASSWORD_SEGURA');

echo "✅ Contraseña actualizada\n";
```

Ejecuta:
```bash
docker-compose exec app php change_admin_password.php
```

**Opción 2: Desde PostgreSQL**

```bash
# Conectar a PostgreSQL
docker-compose exec db psql -U user -d ai_db

# Generar hash (en PHP)
php -r "echo password_hash('TuNuevaPassword', PASSWORD_BCRYPT);"

# Actualizar en PostgreSQL
UPDATE users 
SET password = '$2y$10$HASH_GENERADO_AQUI', 
    updated_at = CURRENT_TIMESTAMP 
WHERE username = 'admin';
```

---

## 🔍 Verificar Tabla

### Desde PostgreSQL

```bash
# Conectar
docker-compose exec db psql -U user -d ai_db

# Ver estructura
\d users

# Ver usuarios
SELECT id, username, email, created_at FROM users;

# Salir
\q
```

### Desde PHP

```bash
docker-compose exec app php init_users.php
```

---

## 📝 Ventajas del Nuevo Sistema

| Aspecto | Antes | Ahora |
|---------|-------|-------|
| **Almacenamiento** | Config file | ✅ Base de datos |
| **Escalabilidad** | 1 usuario | ✅ Múltiples usuarios |
| **Seguridad** | Hash en código | ✅ Hash en BD |
| **Gestión** | Manual | ✅ Programática |
| **Auditoría** | No | ✅ Timestamps |
| **Flexibilidad** | Baja | ✅ Alta |

---

## 🔄 Migración Automática

El sistema **inicializa automáticamente** la tabla cuando:
- Se crea una instancia de `Auth`
- Se ejecuta `init_users.php`

**No requiere migración manual** si ya usabas el sistema anterior.

---

## 🆘 Troubleshooting

### "Usuario admin no existe"

**Solución:**
```bash
docker-compose exec app php init_users.php
```

### "Contraseña incorrecta"

**Verificar:**
```bash
docker-compose exec db psql -U user -d ai_db -c "SELECT username FROM users WHERE username='admin';"
```

Si no existe, ejecutar `init_users.php`.

### "Tabla users no existe"

**Solución:**
```bash
docker-compose exec app php init_users.php
```

---

## 📚 API del Modelo User

```php
// Crear usuario
$userModel->create($username, $password, $email);

// Buscar usuario
$user = $userModel->findByUsername($username);

// Verificar credenciales
$user = $userModel->verify($username, $password);

// Actualizar contraseña
$userModel->updatePassword($username, $newPassword);

// Listar todos
$users = $userModel->getAll();

// Eliminar
$userModel->delete($username);

// Inicializar tabla
$userModel->initializeTable();
```

---

## ✅ Resumen

- ✅ Tabla `users` creada en PostgreSQL
- ✅ Usuario `admin` / `admin123` creado
- ✅ Auth usa base de datos
- ✅ Config limpio (sin credenciales)
- ✅ Sistema escalable y seguro

**¡El sistema de usuarios está completamente funcional!** 🎉
