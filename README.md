# Trabajo Final PHP/MySQL

Sitio web desarrollado con HTML5, CSS3, JavaScript, PHP y MySQL como trabajo final del módulo.

## Estructura del Proyecto

```
trabajo_final_php_masterd/
├── database/
│   └── database.sql          # Script SQL con todas las tablas
├── config/
│   └── database.php         # Configuración de conexión a BD
├── includes/
│   ├── header.php          # Barra de navegación y header común
│   ├── footer.php          # Footer común
│   └── functions.php       # Funciones auxiliares
├── assets/
│   ├── css/
│   │   └── style.css       # Estilos principales
│   ├── js/
│   │   └── main.js         # JavaScript (si es necesario)
│   └── images/             # Imágenes del sitio y noticias
├── index.php                # Página de inicio
├── noticias.php             # Página de noticias
├── registro.php             # Página de registro
├── login.php                # Página de inicio de sesión
├── logout.php               # Cerrar sesión
├── perfil.php               # Perfil de usuario
├── citaciones.php           # Gestión de citas para usuarios
├── usuarios-administracion.php    # CRUD usuarios para admin
├── citas-administracion.php       # CRUD citas para admin
├── noticias-administracion.php    # CRUD noticias para admin
└── README.md                # Este archivo
```

## Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB 10.2 o superior)
- Servidor web (Apache, Nginx, o servidor integrado de PHP)
- Extensiones PHP requeridas:
  - PDO
  - PDO_MySQL
  - GD (para manejo de imágenes)

## Instalación

### 1. Configurar Base de Datos

1. Crea una base de datos MySQL:
```sql
CREATE DATABASE trabajo_final_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importa el archivo SQL:
```bash
mysql -u root -p trabajo_final_php < database/database.sql
```

O desde phpMyAdmin:
- Selecciona la base de datos `trabajo_final_php`
- Ve a la pestaña "Importar"
- Selecciona el archivo `database/database.sql`
- Haz clic en "Continuar"

### 2. Configurar Conexión a Base de Datos

Edita el archivo `config/database.php` y ajusta los valores según tu configuración:

```php
define('DB_HOST', 'localhost');  // Host de MySQL
define('DB_NAME', 'trabajo_final_php');  // Nombre de la base de datos
define('DB_USER', 'root');  // Usuario de MySQL
define('DB_PASS', '');  // Contraseña de MySQL
```

### 3. Configurar Permisos de Carpetas

Asegúrate de que la carpeta `assets/images/` tenga permisos de escritura para que se puedan subir imágenes:

```bash
chmod 755 assets/images/
```

En Windows, asegúrate de que el servidor web tenga permisos de escritura en esa carpeta.

### 4. Iniciar el Servidor

#### Opción 1: Servidor integrado de PHP
```bash
php -S localhost:8000
```

#### Opción 2: Apache/Nginx
Configura tu servidor web para apuntar al directorio del proyecto.

## Credenciales por Defecto

Después de importar la base de datos, puedes iniciar sesión como administrador con:

- **Usuario:** `admin`
- **Contraseña:** `admin123`

**IMPORTANTE:** Cambia estas credenciales después de la primera instalación por seguridad.

## Funcionalidades

### Para Visitantes (sin sesión)
- Ver página de inicio
- Ver noticias
- Registrarse como nuevo usuario
- Iniciar sesión

### Para Usuarios Registrados (rol: user)
- Ver página de inicio
- Ver noticias
- Gestionar citas (crear, editar, eliminar)
- Ver y editar perfil personal
- Cambiar contraseña

### Para Administradores (rol: admin)
- Todas las funcionalidades de usuario
- Administrar usuarios (crear, editar, eliminar)
- Administrar citas de cualquier usuario
- Administrar noticias (crear, editar, eliminar con imágenes)

## Estructura de Base de Datos

### Tabla: users_data
Almacena la información personal de los usuarios:
- idUser (PK, AUTO_INCREMENT)
- nombre (NOT NULL)
- apellidos (NOT NULL)
- email (UNIQUE, NOT NULL)
- telefono (NOT NULL)
- fecha_de_nacimiento (DATE, NOT NULL)
- direccion (TEXT)
- sexo (ENUM: 'Masculino', 'Femenino', 'Otro', NOT NULL)

### Tabla: users_login
Almacena la información de inicio de sesión:
- idLogin (PK, AUTO_INCREMENT)
- idUser (FK a users_data, UNIQUE, NOT NULL)
- usuario (UNIQUE, NOT NULL)
- password (VARCHAR(255), NOT NULL) - Encriptada con password_hash()
- rol (ENUM: 'admin', 'user', NOT NULL)

### Tabla: citas
Almacena las citas de los usuarios:
- idCita (PK, AUTO_INCREMENT)
- idUser (FK a users_data, NOT NULL)
- fecha_cita (DATE, NOT NULL)
- motivo_cita (TEXT)

### Tabla: noticias
Almacena las noticias creadas por administradores:
- idNoticia (PK, AUTO_INCREMENT)
- titulo (UNIQUE, NOT NULL)
- imagen (VARCHAR(255), NOT NULL) - Ruta al archivo
- texto (TEXT, NOT NULL)
- fecha (DATE, NOT NULL)
- idUser (FK a users_data, NOT NULL)

## Características de Seguridad

- Contraseñas encriptadas con `password_hash()` de PHP
- Protección contra SQL Injection mediante Prepared Statements
- Protección contra XSS mediante `htmlspecialchars()`
- Validación de sesiones y roles
- Validación de archivos subidos (tipo y tamaño)
- Sanitización de datos de entrada

## Notas de Desarrollo

- El proyecto utiliza PDO para todas las consultas SQL
- Las sesiones se gestionan mediante PHP sessions
- Los formularios se validan tanto en cliente (HTML5) como en servidor (PHP)
- El diseño es responsive y se adapta a dispositivos móviles

## Solución de Problemas

### Error de conexión a la base de datos
- Verifica que MySQL esté ejecutándose
- Comprueba las credenciales en `config/database.php`
- Asegúrate de que la base de datos existe

### Error al subir imágenes
- Verifica los permisos de la carpeta `assets/images/`
- Comprueba que la extensión GD esté habilitada en PHP
- Verifica el tamaño máximo de archivo permitido (php.ini: upload_max_filesize)

### Error de sesión
- Asegúrate de que las sesiones estén habilitadas en PHP
- Verifica los permisos de la carpeta de sesiones temporales

## Autor

Desarrollado como trabajo final del módulo PHP/MySQL.

## Licencia

Este proyecto es de uso educativo.

