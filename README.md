# Taller Mecánico - Sistema de Gestión Web

Sistema web completo desarrollado con PHP y MySQL para la gestión de un taller mecánico. Incluye funcionalidades de gestión de usuarios, citas, noticias y un sistema completo de monitorización con Prometheus y Grafana.

## 🚀 Características Principales

- ✅ **Gestión de Usuarios:** Sistema de registro, login y perfiles con roles (admin/user)
- ✅ **Gestión de Citas:** Los usuarios pueden crear, editar y eliminar sus citas
- ✅ **Sistema de Noticias:** Los administradores pueden publicar noticias con imágenes
- ✅ **Panel de Administración:** CRUD completo para usuarios, citas y noticias
- ✅ **Monitorización:** Sistema completo con Prometheus y Grafana (solo con Docker)
- ✅ **Seguridad:** Protección contra SQL Injection, XSS, validación de sesiones
- ✅ **Responsive:** Diseño adaptable a dispositivos móviles y tablets

## 🛠️ Tecnologías Utilizadas

- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Backend:** PHP 8.2
- **Base de Datos:** MySQL 8.0
- **Servidor Web:** Apache 2.4
- **Contenedores:** Docker & Docker Compose
- **Monitorización:** Prometheus, Grafana, Node Exporter, MySQL Exporter

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

## 📦 Instalación

### Opción 1: Instalación con Docker (Recomendado) 🐳

Para una instalación rápida y completa con monitorización incluida, consulta la [Guía de Despliegue con Docker](DOCKER_DEPLOYMENT.md).

**Inicio rápido:**

**En Linux/Mac:**
```bash
# Clonar o descargar el proyecto
git clone <url-del-repositorio>
cd taller_mecanico

# Configurar variables de entorno
cp .env.example .env

# Iniciar todos los servicios
docker-compose up -d

# Verificar que todo está funcionando
docker-compose ps
```

**En Windows (PowerShell):**
```powershell
# Clonar o descargar el proyecto
git clone <url-del-repositorio>
cd taller_mecanico

# Configurar variables de entorno
Copy-Item .env.example .env

# Iniciar todos los servicios
docker-compose up -d

# Verificar que todo está funcionando
docker-compose ps
```

**En Windows (CMD):**
```cmd
REM Clonar o descargar el proyecto
git clone <url-del-repositorio>
cd taller_mecanico

REM Configurar variables de entorno
copy .env.example .env

REM Iniciar todos los servicios
docker-compose up -d

REM Verificar que todo está funcionando
docker-compose ps
```

**Acceso a los servicios:**
- 🌐 **Aplicación Web:** http://localhost:8080
- 📊 **Grafana (Monitorización):** http://localhost:3000 (usuario: `admin`, contraseña: `admin123`)
- 📈 **Prometheus:** http://localhost:9090

**Nota para Windows:** Asegúrate de que Docker Desktop esté ejecutándose antes de ejecutar los comandos. La primera vez puede tardar varios minutos en descargar las imágenes.

### Opción 2: Instalación Local sin Docker 💻

> **💡 Para usuarios de Windows con XAMPP:** Consulta la [Guía de Despliegue Local con XAMPP](GUIA_DESPLIEGUE_LOCAL.md) para instrucciones paso a paso específicas de Windows.

> **📖 Para una guía rápida:** Consulta [INSTALL.md](INSTALL.md) para instrucciones de instalación rápida.

#### 1. Configurar Base de Datos

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

#### 2. Configurar Conexión a Base de Datos

Edita el archivo `config/database.php` y ajusta los valores según tu configuración:

**Para XAMPP (sin contraseña por defecto):**
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'trabajo_final_php');
define('DB_USER', 'root');
define('DB_PASS', '');  // Vacío para XAMPP por defecto
```

**Para MySQL instalado manualmente:**
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'trabajo_final_php');
define('DB_USER', 'root');
define('DB_PASS', 'tu_contraseña');  // Tu contraseña de MySQL
```

**Nota:** El archivo también soporta variables de entorno para Docker, pero en instalación local usa los valores por defecto mostrados arriba.

#### 3. Configurar Permisos de Carpetas

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

#### 4. Iniciar el Servidor

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

## 🔐 Credenciales por Defecto

Después de importar la base de datos, puedes iniciar sesión como administrador con:

- **Usuario:** `admin`
- **Contraseña:** `admin123`

**⚠️ IMPORTANTE:** 
- Cambia estas credenciales inmediatamente después de la primera instalación por seguridad
- Si la contraseña no funciona, puede que necesites regenerar el hash usando `generate_password_hash.php`

## ✨ Funcionalidades

### 👤 Para Visitantes (sin sesión)
- Ver página de inicio con información del taller
- Ver noticias publicadas
- Registrarse como nuevo usuario
- Iniciar sesión con credenciales existentes

### 👥 Para Usuarios Registrados (rol: user)
- Todas las funcionalidades de visitante
- **Gestión de Citas:**
  - Crear nuevas citas
  - Editar citas futuras
  - Eliminar citas futuras
  - Ver historial de citas
- **Gestión de Perfil:**
  - Ver y editar datos personales
  - Cambiar contraseña
  - Actualizar información de contacto

### 🔧 Para Administradores (rol: admin)
- Todas las funcionalidades de usuario
- **Administración de Usuarios:**
  - Crear nuevos usuarios
  - Editar usuarios existentes
  - Eliminar usuarios
  - Cambiar roles (admin/user)
- **Administración de Citas:**
  - Ver todas las citas del sistema
  - Crear citas para cualquier usuario
  - Editar cualquier cita
  - Eliminar cualquier cita
- **Administración de Noticias:**
  - Crear noticias con imágenes
  - Editar noticias existentes
  - Eliminar noticias
  - Subir imágenes (JPG, PNG, máximo 5MB)
- **Monitorización (solo con Docker):**
  - Acceso a Grafana para visualizar métricas
  - Dashboards de sistema, aplicación, base de datos y negocio

## 🗄️ Estructura de Base de Datos

El sistema utiliza 4 tablas principales con relaciones mediante Foreign Keys:

### Tabla: `users_data`
Almacena la información personal de los usuarios:
- `idUser` (PK, AUTO_INCREMENT) - Identificador único
- `nombre` (VARCHAR, NOT NULL) - Nombre del usuario
- `apellidos` (VARCHAR, NOT NULL) - Apellidos del usuario
- `email` (VARCHAR, UNIQUE, NOT NULL) - Email único
- `telefono` (VARCHAR, NOT NULL) - Teléfono de contacto
- `fecha_de_nacimiento` (DATE, NOT NULL) - Fecha de nacimiento
- `direccion` (TEXT) - Dirección (opcional)
- `sexo` (ENUM: 'Masculino', 'Femenino', 'Otro', NOT NULL) - Sexo

### Tabla: `users_login`
Almacena las credenciales de acceso:
- `idLogin` (PK, AUTO_INCREMENT) - Identificador único
- `idUser` (FK a users_data, UNIQUE, NOT NULL) - Relación con users_data
- `usuario` (VARCHAR, UNIQUE, NOT NULL) - Nombre de usuario único
- `password` (VARCHAR(255), NOT NULL) - Hash bcrypt de la contraseña
- `rol` (ENUM: 'admin', 'user', NOT NULL) - Rol del usuario

**Relación:** Un usuario tiene una única cuenta de login (relación 1:1)

### Tabla: `citas`
Almacena las citas de los usuarios:
- `idCita` (PK, AUTO_INCREMENT) - Identificador único
- `idUser` (FK a users_data, NOT NULL) - Usuario propietario de la cita
- `fecha_cita` (DATE, NOT NULL) - Fecha de la cita
- `motivo_cita` (TEXT) - Motivo o descripción de la cita

**Relación:** Un usuario puede tener múltiples citas (relación 1:N)

### Tabla: `noticias`
Almacena las noticias publicadas por administradores:
- `idNoticia` (PK, AUTO_INCREMENT) - Identificador único
- `titulo` (VARCHAR, UNIQUE, NOT NULL) - Título único de la noticia
- `imagen` (VARCHAR(255), NOT NULL) - Ruta al archivo de imagen
- `texto` (TEXT, NOT NULL) - Contenido de la noticia
- `fecha` (DATE, NOT NULL) - Fecha de publicación
- `idUser` (FK a users_data, NOT NULL) - Administrador que creó la noticia

**Relación:** Un administrador puede crear múltiples noticias (relación 1:N)

**Características:**
- Charset: `utf8mb4` para soporte completo de Unicode
- Collation: `utf8mb4_unicode_ci`
- Foreign Keys con `ON DELETE CASCADE` para mantener integridad referencial

## 🔒 Características de Seguridad

El proyecto implementa múltiples capas de seguridad:

### Encriptación y Autenticación
- ✅ Contraseñas encriptadas con `password_hash()` usando bcrypt
- ✅ Verificación de contraseñas con `password_verify()`
- ✅ Regeneración de ID de sesión en login

### Protección contra Ataques
- ✅ **SQL Injection:** Todas las consultas usan Prepared Statements con PDO
- ✅ **XSS (Cross-Site Scripting):** Sanitización con `htmlspecialchars()` en toda salida
- ✅ **CSRF:** Validación de sesiones en todas las operaciones críticas

### Validación y Sanitización
- ✅ Validación de entrada en cliente (HTML5) y servidor (PHP)
- ✅ Sanitización de datos con `strip_tags()` y `trim()`
- ✅ Validación de emails con `filter_var()`
- ✅ Validación de archivos subidos:
  - Tipo MIME verificado
  - Solo JPG y PNG permitidos
  - Límite de tamaño: 5MB máximo

### Control de Acceso
- ✅ Validación de sesiones en páginas protegidas
- ✅ Control de roles (admin/user) con verificación en cada página
- ✅ Protección de archivos sensibles mediante `.htaccess`
- ✅ Timeout de sesión configurado

### Configuración Segura
- ✅ Variables de entorno para credenciales (Docker)
- ✅ No hardcodeo de contraseñas en el código
- ✅ Archivo `.env.example` como plantilla segura

## 💻 Notas de Desarrollo

### Arquitectura
- **Backend:** PHP 8.2 con PDO para acceso a base de datos
- **Frontend:** HTML5 semántico, CSS3 con Flexbox, JavaScript vanilla
- **Base de Datos:** MySQL 8.0 con InnoDB y Foreign Keys
- **Patrón:** Arquitectura MVC simplificada con separación de lógica

### Características Técnicas
- ✅ PDO con Prepared Statements para todas las consultas SQL
- ✅ Gestión de sesiones con PHP sessions
- ✅ Validación dual: cliente (HTML5) y servidor (PHP)
- ✅ Diseño responsive con media queries
- ✅ Soporte para variables de entorno (Docker) y configuración tradicional
- ✅ Sistema de logs para métricas y errores

### Estructura de Código
- `config/` - Configuración de base de datos
- `includes/` - Componentes reutilizables (header, footer, functions)
- `assets/` - Recursos estáticos (CSS, imágenes)
- `admin/` - Páginas de administración
- `monitoring/` - Configuración de monitorización (Docker)

## 📊 Monitorización

El proyecto incluye un sistema completo de monitorización con Prometheus y Grafana (disponible solo con Docker):

### Componentes de Monitorización

**Prometheus** - Motor de métricas
- Puerto: 9090 (configurable en `.env`)
- Retención de datos: 15 días
- Intervalo de scraping: 15 segundos
- Targets configurados:
  - Prometheus mismo
  - Aplicación PHP (metrics.php)
  - Node Exporter (métricas del sistema)
  - MySQL Exporter (métricas de base de datos)

**Grafana** - Visualización de métricas
- Puerto: 3000 (configurable en `.env`)
- Credenciales por defecto: `admin` / `admin123`
- Datasource configurado automáticamente
- Dashboards preconfigurados:
  - 📈 **Sistema** (`sistema.json`) - CPU, memoria, disco, red
  - 🌐 **Aplicación** (`aplicacion.json`) - Requests HTTP, tiempos de respuesta, sesiones
  - 🗄️ **Base de Datos** (`base-datos.json`) - Consultas, conexiones, rendimiento MySQL
  - 💼 **Negocio** (`negocio.json`) - Usuarios, citas, noticias, métricas de negocio

### Métricas Disponibles

- **Sistema (Node Exporter):** CPU, memoria, disco, red, procesos
- **Aplicación (PHP Exporter):** Requests HTTP por método/estado, tiempos de respuesta, sesiones activas
- **Base de Datos (MySQL Exporter):** Conexiones, consultas por segundo, operaciones de lectura/escritura, tamaño de BD
- **Negocio (PHP Exporter):** Total de usuarios, usuarios por rol, total de citas, total de noticias

**⚠️ Nota:** La monitorización solo está disponible cuando se despliega con Docker. Para más información, consulta [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md).

## 📚 Documentación Adicional

- 📖 **[GUIA_USUARIO.md](GUIA_USUARIO.md)** - Guía completa de uso para todos los tipos de usuarios (visitantes, usuarios registrados y administradores)
- 🔧 **[STACK_TECNOLOGICO.md](STACK_TECNOLOGICO.md)** - Detalles técnicos del stack tecnológico utilizado
- 🐳 **[DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md)** - Guía completa de despliegue con Docker y monitorización (incluye instrucciones para Windows, Linux y Mac)
- 💻 **[GUIA_DESPLIEGUE_LOCAL.md](GUIA_DESPLIEGUE_LOCAL.md)** - Guía paso a paso para desplegar con XAMPP en Windows
- ⚡ **[INSTALL.md](INSTALL.md)** - Guía de instalación rápida sin Docker (incluye comandos para Windows, Linux y Mac)

## 🔧 Solución de Problemas

### Error de conexión a la base de datos

**Síntomas:** Mensaje "Error de conexión a la base de datos" al acceder a la aplicación

**Soluciones:**
1. Verifica que MySQL esté ejecutándose
   - **XAMPP:** Panel de Control → MySQL debe estar en "Running"
   - **Docker:** `docker-compose ps mysql`
2. Comprueba las credenciales:
   - **Local:** Revisa `config/database.php`
   - **Docker:** Revisa `.env` (variables `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`)
3. Verifica que la base de datos existe:
   - **phpMyAdmin:** http://localhost/phpmyadmin
   - **Docker:** `docker-compose exec mysql mysql -u root -p -e "SHOW DATABASES;"`
4. Revisa los logs:
   - **Docker:** `docker-compose logs mysql`

### Error al subir imágenes

**Síntomas:** No se pueden subir imágenes al crear/editar noticias

**Soluciones:**
1. **Permisos de carpeta:**
   - **Windows:** Propiedades → Seguridad → Otorgar "Control total" a "Usuarios"
   - **Linux/Mac:** `chmod 755 assets/images/`
   - **Docker:** `docker-compose exec web chmod -R 755 /var/www/html/assets/images`
2. **Extensión GD de PHP:**
   - **XAMPP:** Edita `C:\xampp\php\php.ini` → Busca `;extension=gd` → Quita el `;`
   - **Docker:** Ya está incluida en la imagen
3. **Límite de tamaño:**
   - Verifica `upload_max_filesize` y `post_max_size` en `php.ini`
   - Máximo permitido: 5MB
4. **Formato de archivo:**
   - Solo se permiten JPG y PNG
   - Verifica que el archivo no esté corrupto

### Error de sesión

**Síntomas:** Sesiones que no se mantienen, redirecciones constantes al login

**Soluciones:**
1. Verifica que las sesiones estén habilitadas en PHP
2. Revisa los permisos de la carpeta de sesiones temporales
3. Verifica que las cookies estén habilitadas en el navegador
4. Limpia las cookies del sitio y vuelve a iniciar sesión

### Problemas con Docker

**Síntomas:** Contenedores que no inician, puertos ocupados, errores de conexión

**Soluciones comunes:**
- **Puertos ocupados:** Cambia los puertos en `.env` o detén los servicios que los usan
- **Contenedores no inician:** Revisa `docker-compose logs` para ver errores
- **Docker Desktop no inicia (Windows):** Verifica que WSL 2 esté instalado y habilitado

📖 **Para más ayuda:** Consulta la sección "Solución de Problemas" en [DOCKER_DEPLOYMENT.md](DOCKER_DEPLOYMENT.md) para problemas específicos de Docker.

## 🎯 Estado del Proyecto

✅ **Completado** - El proyecto está funcional y listo para uso

### Funcionalidades Implementadas
- ✅ Sistema de autenticación y autorización
- ✅ CRUD completo de usuarios, citas y noticias
- ✅ Gestión de perfiles de usuario
- ✅ Sistema de noticias con imágenes
- ✅ Panel de administración completo
- ✅ Sistema de monitorización (Docker)
- ✅ Diseño responsive
- ✅ Validación y seguridad implementadas

### Posibles Mejoras Futuras
- 🔄 Sistema de notificaciones por email
- 📅 Calendario de citas mejorado
- 📱 Aplicación móvil
- 🔍 Sistema de búsqueda avanzada
- 📊 Reportes y estadísticas adicionales

## 👤 Autor

Desarrollado como trabajo final del módulo PHP/MySQL.

## 📄 Licencia

Este proyecto es de uso educativo.

---

## 🙏 Contribuciones

Las contribuciones son bienvenidas. Si encuentras algún problema o tienes sugerencias, por favor:
1. Abre un issue describiendo el problema o sugerencia
2. Si quieres contribuir código, crea un pull request con una descripción clara de los cambios

## 📞 Soporte

Para obtener ayuda:
1. Revisa la documentación en las guías mencionadas arriba
2. Consulta la sección de "Solución de Problemas"
3. Revisa los logs de la aplicación y servicios

