# Trabajo Final PHP/MySQL

Sitio web desarrollado con HTML5, CSS3, JavaScript, PHP y MySQL como trabajo final del módulo.

## Estructura del Proyecto

```
taller_mecanico/
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
│   └── images/             # Imágenes del sitio y noticias
├── monitoring/
│   ├── prometheus/
│   │   └── prometheus.yml   # Configuración de Prometheus
│   ├── grafana/
│   │   ├── provisioning/   # Configuración automática de Grafana
│   │   └── dashboards/      # Dashboards de Grafana
│   └── php-exporter/
│       └── metrics.php     # Endpoint de métricas PHP
├── docker/
│   ├── init-db.sh          # Script de inicialización BD
│   └── entrypoint.sh       # Script de entrada Docker
├── logs/                    # Logs de métricas
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
├── Dockerfile               # Imagen Docker de la aplicación
├── docker-compose.yml       # Orquestación de servicios
├── .env.example             # Ejemplo de variables de entorno
├── README.md                # Este archivo
├── GUIA_USUARIO.md          # Guía de usuario completa
├── STACK_TECNOLOGICO.md     # Stack tecnológico detallado
└── DOCKER_DEPLOYMENT.md     # Guía de despliegue Docker
```

## Requisitos Previos

### Para Instalación con Docker
- Docker Engine 20.10 o superior
- Docker Compose 2.0 o superior
- Al menos 2GB de RAM disponible
- Al menos 5GB de espacio en disco

**Para Windows:**
- Docker Desktop para Windows (incluye Docker Engine y Docker Compose)
- Windows 10 64-bit (Build 19041+) o Windows 11 64-bit
- WSL 2 habilitado (se instala automáticamente con Docker Desktop)
- Ver [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md) para instrucciones detalladas de instalación en Windows

### Para Instalación Local
- PHP 7.4 o superior (recomendado PHP 8.2+)
- MySQL 5.7 o superior (recomendado MySQL 8.0)
- Servidor web (Apache 2.4+, Nginx 1.18+, o servidor integrado de PHP)
- Extensiones PHP requeridas:
  - PDO
  - PDO_MySQL
  - GD (para manejo de imágenes)
  - Session
  - Filter
  - Hash

**Para Windows:**
- **Opción recomendada:** XAMPP (incluye PHP, MySQL, Apache y phpMyAdmin)
  - Descarga desde: https://www.apachefriends.org/
  - Ver [GUIA_DESPLIEGUE_LOCAL.md](GUIA_DESPLIEGUE_LOCAL.md) para guía paso a paso
- **Alternativa:** WAMP Server o instalación manual de PHP y MySQL

## Instalación

### Opción 1: Instalación con Docker (Recomendado)

Para una instalación rápida y completa con monitorización incluida, consulta la [Guía de Despliegue con Docker](DOCKER_DEPLOYMENT.md).

**Inicio rápido:**

**En Linux/Mac:**
```bash
cp .env.example .env
docker-compose up -d
```

**En Windows (PowerShell):**
```powershell
Copy-Item .env.example .env
docker-compose up -d
```

**En Windows (CMD):**
```cmd
copy .env.example .env
docker-compose up -d
```

Accede a:
- **Aplicación:** http://localhost:8080
- **Grafana (Monitorización):** http://localhost:3000
- **Prometheus:** http://localhost:9090

**Nota para Windows:** Asegúrate de que Docker Desktop esté ejecutándose antes de ejecutar los comandos.

### Opción 2: Instalación Local sin Docker

> **Para usuarios de Windows con XAMPP:** Consulta la [Guía de Despliegue Local con XAMPP](GUIA_DESPLIEGUE_LOCAL.md) para instrucciones paso a paso específicas de Windows.

### 1. Configurar Base de Datos

1. Crea una base de datos MySQL:
```sql
CREATE DATABASE trabajo_final_php CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importa el archivo SQL:

**En Linux/Mac:**
```bash
mysql -u root -p trabajo_final_php < database/database.sql
```

**En Windows (si MySQL está en el PATH):**
```cmd
mysql -u root -p trabajo_final_php < database\database.sql
```

**O desde phpMyAdmin (recomendado para Windows):**
- Abre phpMyAdmin en tu navegador (http://localhost/phpmyadmin)
- Selecciona la base de datos `trabajo_final_php` (o créala primero)
- Ve a la pestaña "Importar"
- Selecciona el archivo `database\database.sql`
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

**En Linux/Mac:**
```bash
chmod 755 assets/images/
```

**En Windows:**
1. Abre el Explorador de Archivos
2. Navega a la carpeta `assets\images\`
3. Haz clic derecho → Propiedades → Pestaña "Seguridad"
4. Asegúrate de que "Usuarios" tenga permisos de "Control total" o al menos "Modificar"
5. Si usas XAMPP, Apache necesita permisos de escritura en esta carpeta

### 4. Iniciar el Servidor

#### Opción 1: Servidor integrado de PHP

**En Linux/Mac:**
```bash
php -S localhost:8000
```

**En Windows (si PHP está en el PATH):**
```cmd
php -S localhost:8000
```

**En Windows con XAMPP:**
```cmd
C:\xampp\php\php.exe -S localhost:8000
```

#### Opción 2: Apache/Nginx

**En Linux/Mac:** Configura tu servidor web para apuntar al directorio del proyecto.

**En Windows con XAMPP:**
- Coloca el proyecto en `C:\xampp\htdocs\taller_mecanico\`
- Inicia Apache desde el Panel de Control de XAMPP
- Accede vía: http://localhost/taller_mecanico

**En Windows con WAMP:**
- Coloca el proyecto en `C:\wamp64\www\taller_mecanico\`
- Inicia los servicios desde WAMP
- Accede vía: http://localhost/taller_mecanico

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

## Monitorización

El proyecto incluye un sistema completo de monitorización con Prometheus y Grafana:

- **Prometheus:** Recopila métricas del sistema, aplicación y base de datos
- **Grafana:** Visualiza métricas en dashboards interactivos
- **Métricas disponibles:**
  - Sistema: CPU, memoria, disco, red
  - Aplicación: Requests HTTP, tiempos de respuesta, sesiones activas
  - Base de datos: Consultas, conexiones, rendimiento MySQL
  - Negocio: Usuarios, citas, noticias

Para más información sobre monitorización, consulta [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md).

## Documentación Adicional

- **[GUIA_USUARIO.md](GUIA_USUARIO.md)** - Guía completa de uso para todos los tipos de usuarios
- **[STACK_TECNOLOGICO.md](STACK_TECNOLOGICO.md)** - Detalles técnicos del stack tecnológico
- **[DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md)** - Guía de despliegue con Docker y monitorización (incluye instrucciones para Windows)
- **[GUIA_DESPLIEGUE_LOCAL.md](GUIA_DESPLIEGUE_LOCAL.md)** - Guía paso a paso para desplegar con XAMPP en Windows
- **[INSTALL.md](INSTALL.md)** - Guía de instalación rápida (incluye comandos para Windows)

## Solución de Problemas

### Error de conexión a la base de datos
- Verifica que MySQL esté ejecutándose
- Comprueba las credenciales en `config/database.php` o `.env` (si usas Docker)
- Asegúrate de que la base de datos existe
- Si usas Docker: `docker-compose logs mysql`

### Error al subir imágenes
- Verifica los permisos de la carpeta `assets/images/`
  - **Windows:** Asegúrate de que la carpeta tenga permisos de escritura (ver sección de permisos arriba)
  - **Linux/Mac:** Ejecuta `chmod 755 assets/images/`
- Comprueba que la extensión GD esté habilitada en PHP
  - **Windows con XAMPP:** Edita `C:\xampp\php\php.ini` y descomenta `;extension=gd` (quita el `;`)
- Verifica el tamaño máximo de archivo permitido (php.ini: upload_max_filesize)
- Si usas Docker: `docker-compose exec web chmod -R 755 /var/www/html/assets/images`

### Error de sesión
- Asegúrate de que las sesiones estén habilitadas en PHP
- Verifica los permisos de la carpeta de sesiones temporales

### Problemas con Docker
Consulta la sección "Solución de Problemas" en [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md) para problemas específicos de Docker.

## Autor

Desarrollado como trabajo final del módulo PHP/MySQL.

## Licencia

Este proyecto es de uso educativo.

