# Guía de Instalación Rápida

## Paso 1: Configurar Base de Datos

1. Crea la base de datos:
```sql
CREATE DATABASE trabajo_final_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importa el archivo SQL:
```bash
mysql -u root -p trabajo_final_php < database/database.sql
```

## Paso 2: Configurar Conexión

Edita `config/database.php` y ajusta las credenciales:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'trabajo_final_php');
define('DB_USER', 'root');
define('DB_PASS', 'tu_contraseña');
```

## Paso 3: Generar Hash de Contraseña del Admin

El hash en `database.sql` es un ejemplo. Para generar uno correcto:

1. Ejecuta: `php generate_password_hash.php`
2. Copia el hash generado
3. Actualiza la línea en `database.sql`:
```sql
VALUES (1, 'admin', 'TU_HASH_AQUI', 'admin');
```

O simplemente cambia la contraseña después de iniciar sesión desde el perfil.

## Paso 4: Permisos de Carpetas

Asegúrate de que `assets/images/` tenga permisos de escritura:
```bash
chmod 755 assets/images/
```

## Paso 5: Iniciar Servidor

```bash
php -S localhost:8000
```

Visita: http://localhost:8000

## Credenciales por Defecto

- Usuario: `admin`
- Contraseña: `admin123` (si usas el hash del ejemplo, puede que necesites regenerarlo)

