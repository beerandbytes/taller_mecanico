# Despliegue en Coolify (MySQL)

Esta guía cubre el despliegue en Coolify con MySQL y los pasos “antes de darle a Deploy” (recursos, variables, volúmenes y comprobaciones).

El fallo más común en Coolify es que la app arranca sin `DB_*` y termina intentando conectar a `localhost` (dentro del contenedor), o que Coolify expone la BD como `MYSQL_*` en lugar de `DB_*`.

Este repo ya soporta ambos: `DB_*` y alias `MYSQL_*`.

## Antes de empezar (recomendado)

- Para Coolify, usa `docker-compose.coolify.yml` (mínimo: app + MySQL; sin monitorización).
- Si quieres monitorización (Prometheus/Grafana), despliega esos servicios en un segundo stack o adapta `docker-compose.dokploy.yml` más adelante.
- No subas `.env` al repo. En Coolify configura variables/secretos desde la UI.
- Decide **una** de estas estrategias de BD:
  - **A) MySQL dentro del mismo Docker Compose** (más simple).
  - **B) MySQL gestionado por Coolify** y “link” a la app (Coolify suele inyectar `MYSQL_*`).

## 1) Crear recursos en Coolify

### Opción A (recomendada): app + MySQL en el mismo Compose

1. Coolify → **New Resource** → **Docker Compose** → selecciona el repo.
2. En **Compose file**, usa `docker-compose.coolify.yml`.
3. En **Domains/Routes**, apunta tu dominio a la app (servicio `web`, puerto interno `80`).
4. En **Storage/Volumes**, deja los volúmenes nombrados tal cual (persisten datos).

### Opción B: MySQL gestionado por Coolify (y app por Dockerfile o Compose)

1. Crea una **Database (MySQL)** en Coolify.
2. En tu app (resource), usa **Link/Connect to database** (nombre puede variar por versión).
3. Verifica que Coolify inyecta variables tipo `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_DATABASE` (o equivalentes).

## 2) Variables de entorno (obligatorio)

Configura estas variables en Coolify (Resource → **Environment Variables / Secrets**).

### Variables mínimas de la app

- `APP_ENV=production`
- `APP_DEBUG=false`

### Variables de base de datos (elige un set)

**Set 1: `DB_*` (explícitas)**

- `DB_HOST` (hostname del servicio MySQL en la red Docker; en Compose suele ser `mysql`)
- `DB_PORT` (normalmente `3306`)
- `DB_NAME`
- `DB_USER`
- `DB_PASS`

**Set 2: `MYSQL_*` (si Coolify las inyecta al linkar la BD)**

- `MYSQL_HOST`
- `MYSQL_PORT`
- `MYSQL_DATABASE`
- `MYSQL_USER`
- `MYSQL_PASSWORD`

Si Coolify te crea `MYSQL_*` automáticamente, **no necesitas duplicarlas**: la app las mapeará a `DB_*`.

### Variables si usas MySQL dentro del mismo Compose

El servicio `mysql` del compose usa estas (pon valores seguros):

- `MYSQL_ROOT_PASSWORD` (obligatoria)
- `MYSQL_DATABASE`
- `MYSQL_USER`
- `MYSQL_PASSWORD`

Ejemplo típico (no copies contraseñas débiles):

```env
APP_ENV=production
APP_DEBUG=false

MYSQL_ROOT_PASSWORD=CAMBIA_ESTO
MYSQL_DATABASE=trabajo_final_php
MYSQL_USER=app_user
MYSQL_PASSWORD=CAMBIA_ESTO

# Opcional si quieres forzar el set DB_* (si no, el repo ya usa alias MYSQL_*)
DB_HOST=mysql
DB_PORT=3306
DB_NAME=trabajo_final_php
DB_USER=app_user
DB_PASS=CAMBIA_ESTO
```

## 3) Persistencia y primera inicialización (database.sql)

Si usas `mysql_data` (volumen persistente), el script `database/database.sql` **solo se ejecuta la primera vez** (cuando el volumen está vacío).

Qué revisar antes del primer deploy:

- MySQL debe tener un volumen persistente (en el compose: `mysql_data:/var/lib/mysql`).
- El SQL de arranque se carga desde `/docker-entrypoint-initdb.d/` (en `docker-compose.coolify.yml` se monta el directorio `./database:/docker-entrypoint-initdb.d:ro`).

Si cambias credenciales o necesitas reinicializar el esquema, borra el **volumen** de MySQL (o la DB) en Coolify y despliega de nuevo.

## 4) Puertos y red (muy importante)

- En Coolify, **no uses `DB_HOST=localhost`** para la app.
  - `localhost` dentro del contenedor apunta al propio contenedor de PHP, no al de MySQL.
- Si MySQL vive en el mismo Compose, el host suele ser el **nombre del servicio**: `mysql`.
- La app escucha en el puerto interno `80` (Coolify enruta el dominio a ese puerto).

## 5) Checklist rápido antes de “Deploy”

- El resource apunta al compose `docker-compose.dokploy.yml` (o equivalente sin bind-mount del código).
- Dominio/Route → servicio `web` → puerto `80`.
- Variables mínimas: `APP_ENV=production`, `APP_DEBUG=false`.
- Variables de BD presentes (`DB_*` o `MYSQL_*`).
- Si usas MySQL del compose: `MYSQL_ROOT_PASSWORD` definido y volumen `mysql_data` creado.

## 6) Diagnóstico (si falla la conexión)

Errores típicos:

- `SQLSTATE[HY000] [2002] Connection refused` → host/puerto incorrecto o MySQL aún no está listo.
- `Access denied for user` → credenciales/usuario/privilegios.
- `Unknown database` → `MYSQL_DATABASE`/`DB_NAME` no coincide o el init no se ejecutó (volumen ya existente).

Pasos:

1. Revisa logs del servicio **mysql** y confirma que está “healthy/ready”.
2. Revisa logs del servicio **web** (PHP) y confirma qué variables de entorno están configuradas.
3. Si necesitas ver detalles en pantalla temporalmente: pon `APP_DEBUG=true` y vuelve a desplegar (después vuelve a `false`).
