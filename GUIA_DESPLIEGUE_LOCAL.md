# Guía Paso a Paso: Desplegar el Proyecto en Local con XAMPP (Windows)

Esta guía te ayudará a desplegar el proyecto PHP/MySQL usando **XAMPP** en tu máquina local paso a paso.

---

## 📋 Requisitos Previos

Solo necesitas tener instalado **XAMPP**, que incluye:
- PHP (con todas las extensiones necesarias)
- MySQL/MariaDB
- Apache
- phpMyAdmin

---

## 🔧 Paso 1: Instalar XAMPP

### 1.1. Descargar XAMPP

1. Ve a: https://www.apachefriends.org/
2. Descarga la versión más reciente de XAMPP para Windows
3. Ejecuta el instalador

### 1.2. Instalar XAMPP

1. Durante la instalación, elige la carpeta de destino (por defecto: `C:\xampp`)
2. **IMPORTANTE:** Desmarca las opciones de instalar servicios adicionales si no los necesitas
3. Completa la instalación

### 1.3. Verificar la instalación

Abre el **Panel de Control de XAMPP** desde el menú de inicio o desde `C:\xampp\xampp-control.exe`

Deberías ver módulos como: Apache, MySQL, FileZilla, Mercury, Tomcat

---

## 🚀 Paso 2: Iniciar los Servicios de XAMPP

### 2.1. Abrir el Panel de Control de XAMPP

- Busca "XAMPP Control Panel" en el menú de inicio de Windows
- O navega a `C:\xampp` y ejecuta `xampp-control.exe`

### 2.2. Iniciar Apache y MySQL

En el Panel de Control de XAMPP:

1. Haz clic en el botón **"Start"** junto a **Apache**
   - Espera a que el botón se ponga verde y aparezca "Running"
   
2. Haz clic en el botón **"Start"** junto a **MySQL**
   - Espera a que el botón se ponga verde y aparezca "Running"

**¡IMPORTANTE!** Debes mantener estos servicios ejecutándose mientras trabajas con el proyecto.

### 2.3. Verificar que los servicios están funcionando

1. Abre tu navegador y ve a: **http://localhost**
   - Deberías ver la página de bienvenida de XAMPP

2. Ve a: **http://localhost/phpmyadmin**
   - Deberías ver la interfaz de phpMyAdmin

Si ambos funcionan, ¡XAMPP está configurado correctamente! ✅

---

## 📁 Paso 3: Preparar el Proyecto

### 3.1. Ubicar tu proyecto

Tienes dos opciones:

**Opción A: Mover el proyecto a la carpeta htdocs de XAMPP (Recomendado)**

1. Copia o mueve tu carpeta del proyecto a: `C:\xampp\htdocs\`
2. El proyecto debería quedar en: `C:\xampp\htdocs\trabajo_final_php_masterd\`

**Opción B: Dejar el proyecto donde está**

Si prefieres dejar el proyecto en su ubicación actual, puedes crear un enlace simbólico o configurar Apache (más avanzado).

**Para esta guía, usaremos la Opción A (mover a htdocs).**

### 3.2. Verificar estructura de carpetas

Asegúrate de que exista la carpeta `assets/images/`. Si no existe:

1. Abre el Explorador de Archivos
2. Navega a `C:\xampp\htdocs\trabajo_final_php_masterd\assets\`
3. Crea una carpeta llamada `images` si no existe

---

## 🗃️ Paso 4: Crear e Importar la Base de Datos

### 4.1. Abrir phpMyAdmin

1. Asegúrate de que MySQL esté ejecutándose en XAMPP
2. Abre tu navegador y ve a: **http://localhost/phpmyadmin**

### 4.2. Crear la base de datos

1. En phpMyAdmin, haz clic en la pestaña **"Bases de datos"** (o "Databases")
2. En el campo **"Crear base de datos"** (o "Create database"), escribe:
   ```
   trabajo_final_php
   ```
3. En el menú desplegable **"Cotejamiento"** (o "Collation"), selecciona:
   ```
   utf8mb4_unicode_ci
   ```
4. Haz clic en el botón **"Crear"** (o "Create")

Deberías ver la nueva base de datos en la lista del lado izquierdo.

### 4.3. Importar el esquema de la base de datos

1. Haz clic en la base de datos `trabajo_final_php` en el menú izquierdo
2. Ve a la pestaña **"Importar"** (o "Import")
3. Haz clic en el botón **"Elegir archivo"** (o "Choose File")
4. Navega a tu proyecto y selecciona: `database\database.sql`
   - Ruta completa: `C:\xampp\htdocs\trabajo_final_php_masterd\database\database.sql`
5. Haz clic en el botón **"Continuar"** (o "Go") en la parte inferior

Espera a que termine la importación. Deberías ver un mensaje de éxito.

### 4.4. Verificar que las tablas se crearon

1. En el menú izquierdo, expande `trabajo_final_php`
2. Deberías ver estas tablas:
   - `users_data`
   - `users_login`
   - `citas`
   - `noticias`

Si ves todas las tablas, ¡la base de datos está lista! ✅

---

## ⚙️ Paso 5: Configurar la Conexión a la Base de Datos

### 5.1. Abrir el archivo de configuración

1. Abre el archivo `config\database.php` con tu editor de código favorito
   - Ruta completa: `C:\xampp\htdocs\trabajo_final_php_masterd\config\database.php`

### 5.2. Ajustar las credenciales para XAMPP

Por defecto, XAMPP no tiene contraseña para el usuario `root`. Modifica el archivo así:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'trabajo_final_php');
define('DB_USER', 'root');
define('DB_PASS', '');  // Vacío porque XAMPP no tiene contraseña por defecto
```

**Si configuraste una contraseña para MySQL en XAMPP**, ponla en `DB_PASS`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'trabajo_final_php');
define('DB_USER', 'root');
define('DB_PASS', 'tu_contraseña_aqui');
```

### 5.3. Guardar el archivo

Guarda los cambios en `config\database.php`

---

## 🔐 Paso 6: Configurar Contraseña del Administrador (Opcional)

### 6.1. Verificar credenciales por defecto

Por defecto, después de importar `database.sql`, las credenciales del administrador son:
- **Usuario:** `admin`
- **Contraseña:** `admin123`

Estas deberían funcionar directamente. Si quieres cambiarlas, sigue los pasos siguientes.

### 6.2. Generar hash de contraseña (si quieres cambiar la contraseña)

1. Abre PowerShell o CMD
2. Navega a la carpeta del proyecto:
   ```powershell
   cd C:\xampp\htdocs\trabajo_final_php_masterd
   ```
3. Ejecuta:
   ```powershell
   C:\xampp\php\php.exe generate_password_hash.php
   ```

Esto generará un hash para la contraseña `admin123`. Si quieres otra contraseña:

1. Abre `generate_password_hash.php` con tu editor
2. Cambia la línea:
   ```php
   $password = 'admin123';  // Cambia 'admin123' por tu contraseña deseada
   ```
3. Guarda y ejecuta nuevamente el script

### 6.3. Actualizar la contraseña en la base de datos (si es necesario)

1. Ve a phpMyAdmin: **http://localhost/phpmyadmin**
2. Selecciona la base de datos `trabajo_final_php`
3. Haz clic en la tabla `users_login`
4. Haz clic en la pestaña **"Examinar"** (o "Browse")
5. Haz clic en el icono de editar (lápiz) junto al usuario `admin`
6. Pega el hash generado en el campo `password`
7. Haz clic en **"Continuar"** (o "Go")

---

## 🌐 Paso 7: Acceder al Proyecto

### 7.1. Verificar que Apache está ejecutándose

Asegúrate de que Apache esté en estado **"Running"** (verde) en el Panel de Control de XAMPP.

### 7.2. Abrir el proyecto en el navegador

Abre tu navegador y ve a:

```
http://localhost/trabajo_final_php_masterd
```

O si moviste el proyecto directamente a htdocs sin la carpeta:

```
http://localhost
```

Deberías ver la página de inicio del proyecto.

### 7.3. Probar las páginas principales

1. **Página de inicio:** http://localhost/trabajo_final_php_masterd/
2. **Página de noticias:** http://localhost/trabajo_final_php_masterd/noticias.php
3. **Página de registro:** http://localhost/trabajo_final_php_masterd/registro.php
4. **Página de login:** http://localhost/trabajo_final_php_masterd/login.php

---

## ✅ Paso 8: Verificar el Despliegue

### 8.1. Iniciar sesión como administrador

1. Ve a: **http://localhost/trabajo_final_php_masterd/login.php**
2. Ingresa las credenciales:
   - **Usuario:** `admin`
   - **Contraseña:** `admin123`
3. Haz clic en "Iniciar sesión"

Si puedes iniciar sesión correctamente y ver el panel de administración, ¡el despliegue fue exitoso! 🎉

### 8.2. Probar funcionalidades básicas

- ✅ Ver noticias
- ✅ Crear una nueva cuenta de usuario
- ✅ Iniciar sesión con el nuevo usuario
- ✅ Crear una cita (si eres usuario normal)
- ✅ Administrar usuarios (si eres admin)
- ✅ Crear una noticia con imagen (si eres admin)

---

## 🔧 Solución de Problemas Comunes

### Error: "Error de conexión a la base de datos"

**Causas posibles:**
- MySQL no está ejecutándose en XAMPP
- Credenciales incorrectas en `config/database.php`
- La base de datos no existe

**Solución:**
1. Abre el Panel de Control de XAMPP
2. Verifica que MySQL esté en estado "Running" (verde)
3. Si no está corriendo, haz clic en "Start"
4. Revisa las credenciales en `config/database.php` (debería ser `root` sin contraseña)
5. Verifica en phpMyAdmin que la base de datos `trabajo_final_php` existe

### Error: "No se puede subir la imagen"

**Causas posibles:**
- La carpeta `assets/images/` no tiene permisos de escritura
- La extensión GD no está habilitada en PHP

**Solución:**
1. Verifica que la carpeta `assets/images/` exista
2. En Windows, haz clic derecho en `assets/images/` → Propiedades → Seguridad
3. Asegúrate de que "Usuarios" tenga permisos de "Control total"
4. Verifica que la extensión GD esté habilitada:
   - Abre `C:\xampp\php\php.ini`
   - Busca `;extension=gd` y quita el `;` al inicio: `extension=gd`
   - Reinicia Apache en XAMPP

### Error: "404 Not Found" o página en blanco

**Causas posibles:**
- Apache no está ejecutándose
- El proyecto no está en la carpeta correcta
- La URL es incorrecta

**Solución:**
1. Verifica que Apache esté en estado "Running" en XAMPP
2. Asegúrate de que el proyecto esté en `C:\xampp\htdocs\trabajo_final_php_masterd\`
3. Verifica la URL: `http://localhost/trabajo_final_php_masterd/`
4. Si el proyecto está directamente en htdocs, usa: `http://localhost/`

### Error: "Access denied for user 'root'@'localhost'"

**Causa:**
- Configuraste una contraseña para MySQL pero no la pusiste en `config/database.php`

**Solución:**
1. Abre `config/database.php`
2. Si configuraste una contraseña, ponla en `DB_PASS`
3. Si no configuraste contraseña, deja `DB_PASS` vacío: `define('DB_PASS', '');`

### El puerto 80 está ocupado (Apache no inicia)

**Causa:**
- Otra aplicación está usando el puerto 80 (Skype, IIS, etc.)

**Solución:**
1. Cierra otras aplicaciones que puedan usar el puerto 80
2. O cambia el puerto de Apache:
   - Abre `C:\xampp\apache\conf\httpd.conf`
   - Busca `Listen 80` y cámbialo a `Listen 8080`
   - Guarda y reinicia Apache
   - Accede al proyecto con: `http://localhost:8080/trabajo_final_php_masterd/`

### El puerto 3306 está ocupado (MySQL no inicia)

**Causa:**
- Otra instancia de MySQL está ejecutándose

**Solución:**
1. Abre el Administrador de Tareas (Ctrl + Shift + Esc)
2. Ve a la pestaña "Servicios"
3. Busca servicios de MySQL y detén los que no sean de XAMPP
4. O cambia el puerto de MySQL en XAMPP (más avanzado)

---

## 📝 Notas Adicionales

### Detener los servicios de XAMPP

Cuando termines de trabajar:

1. Abre el Panel de Control de XAMPP
2. Haz clic en **"Stop"** junto a Apache
3. Haz clic en **"Stop"** junto a MySQL

### Iniciar XAMPP automáticamente al iniciar Windows

1. En el Panel de Control de XAMPP, haz clic en **"Config"** junto a Apache
2. Selecciona **"Service"** → **"Install"**
3. Repite lo mismo para MySQL
4. Ahora los servicios se iniciarán automáticamente al iniciar Windows

**Nota:** Esto puede ralentizar el inicio de Windows. Considera si realmente lo necesitas.

### Usar un editor de código

Para editar los archivos PHP, te recomiendo usar:
- **Visual Studio Code** (gratis, recomendado)
- **PhpStorm** (de pago, muy completo)
- **Notepad++** (gratis, simple)

### Ver los logs de errores

Si algo no funciona:

1. **Logs de Apache:** `C:\xampp\apache\logs\error.log`
2. **Logs de MySQL:** `C:\xampp\mysql\data\mysql_error.log`
3. **Logs de PHP:** Revisa `C:\xampp\php\logs\php_error_log`

### Estructura de URLs

Con XAMPP, las URLs siguen este patrón:

```
http://localhost/[nombre_carpeta]/[archivo.php]
```

Ejemplo:
- `http://localhost/trabajo_final_php_masterd/index.php`
- `http://localhost/trabajo_final_php_masterd/login.php`

---

## 🎯 Resumen Rápido

1. ✅ **Instalar XAMPP** desde apachefriends.org
2. ✅ **Iniciar Apache y MySQL** desde el Panel de Control de XAMPP
3. ✅ **Mover proyecto** a `C:\xampp\htdocs\trabajo_final_php_masterd\`
4. ✅ **Crear base de datos** `trabajo_final_php` en phpMyAdmin
5. ✅ **Importar** `database\database.sql` desde phpMyAdmin
6. ✅ **Configurar** `config/database.php` (usuario: `root`, contraseña: vacía)
7. ✅ **Acceder** a http://localhost/trabajo_final_php_masterd/
8. ✅ **Login** con usuario `admin` y contraseña `admin123`

---

## 🎉 ¡Listo!

Tu proyecto debería estar funcionando correctamente con XAMPP. Si tienes algún problema, revisa la sección de "Solución de Problemas Comunes" o verifica los logs de Apache y MySQL.

**Recordatorio importante:** Siempre mantén Apache y MySQL ejecutándose en XAMPP mientras trabajas con el proyecto.
