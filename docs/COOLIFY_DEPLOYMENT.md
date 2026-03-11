# Despliegue en Coolify (MySQL)

El fallo más común en Coolify es que la app arranca sin `DB_*` y termina intentando conectar a `localhost` (dentro del contenedor), o que Coolify expone la BD como `MYSQL_*` en lugar de `DB_*`.

Este repo ya soporta ambos: `DB_*` y alias `MYSQL_*`.

## 1) Crear/adjuntar una BD MySQL

- Opción A: **Usar un servicio MySQL** dentro del mismo proyecto (recomendado).
- Opción B: **Usar la Base de Datos gestionada** de Coolify y “linkarla” a la app (Coolify suele inyectar `MYSQL_HOST`, `MYSQL_PORT`, `MYSQL_USER`, `MYSQL_PASSWORD`, `MYSQL_DATABASE`).

## 2) Variables de entorno (obligatorio)

Define estas variables en Coolify (o asegúrate de que existen vía “link” a la BD):

- `DB_HOST` (hostname del servicio MySQL en la red de Docker; a menudo `mysql`)
- `DB_PORT` (normalmente `3306`)
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `APP_ENV=production`
- `APP_DEBUG=false`

Si Coolify te crea `MYSQL_*` automáticamente, **no necesitas duplicarlas**: la app las mapeará a `DB_*`.

## 3) Inicialización del esquema (database.sql)

Si usas `mysql_data` (volumen persistente), el script `database/database.sql` **solo se ejecuta la primera vez** (cuando el volumen está vacío).

Si cambias credenciales o necesitas reinicializar, borra el volumen de MySQL en Coolify y despliega de nuevo.

