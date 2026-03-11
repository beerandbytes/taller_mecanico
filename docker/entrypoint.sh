#!/bin/bash
set -e

# Crear directorio de logs si no existe
mkdir -p /var/www/html/logs
chown -R www-data:www-data /var/www/html/logs
chmod -R 755 /var/www/html/logs

# Normalizar variables de BD (Coolify/managed DBs suelen exponer MYSQL_*)
DB_HOST="${DB_HOST:-${MYSQL_HOST:-mysql}}"
DB_PORT="${DB_PORT:-${MYSQL_PORT:-}}"
DB_USER="${DB_USER:-${MYSQL_USER:-root}}"
DB_PASS="${DB_PASS:-${MYSQL_PASSWORD:-rootpassword}}"
DB_NAME="${DB_NAME:-${MYSQL_DATABASE:-trabajo_final_php}}"

# Soportar DB_HOST con formato host:puerto (común en .env local)
DB_HOSTNAME="$DB_HOST"
DB_HOSTPORT="$DB_PORT"
if [[ "$DB_HOST" == *:* ]]; then
  DB_HOSTNAME="${DB_HOST%%:*}"
  DB_HOSTPORT="${DB_HOST##*:}"
fi
if [[ -z "$DB_HOSTPORT" ]]; then
  DB_HOSTPORT="3306"
fi

# Crear archivo temporal .my.cnf para evitar exponer contraseña en procesos
TMP_CNF=$(mktemp)
trap "rm -f $TMP_CNF" EXIT

cat > "$TMP_CNF" <<EOF
[client]
host=${DB_HOSTNAME}
port=${DB_HOSTPORT}
user=${DB_USER}
password=${DB_PASS}
ssl=0
EOF
chmod 600 "$TMP_CNF"

mysqladmin_ping() {
  mysqladmin --defaults-extra-file="$TMP_CNF" --protocol=tcp --connect-timeout=2 ping >/dev/null 2>&1 && return 0
  # Fallback por compatibilidad (algunas builds no soportan ciertas flags)
  mysqladmin --defaults-extra-file="$TMP_CNF" ping >/dev/null 2>&1
}

# Esperar a que MySQL esté listo con timeout
echo "Esperando a que MySQL esté listo..."
MAX_ATTEMPTS=60
ATTEMPT=0
MYSQL_READY=0

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
    if mysqladmin_ping; then
        MYSQL_READY=1
        break
    fi
    ATTEMPT=$((ATTEMPT + 1))
    echo "Intento $ATTEMPT/$MAX_ATTEMPTS: MySQL no está listo, esperando..."
    sleep 2
done

if [ $MYSQL_READY -eq 0 ]; then
    echo "ERROR: MySQL no está disponible después de $MAX_ATTEMPTS intentos" >&2
    exit 1
fi

echo "MySQL está listo!"

# Verificar que la base de datos existe
echo "Verificando que la base de datos '${DB_NAME}' existe..."
if ! mysql --defaults-extra-file="$TMP_CNF" --protocol=tcp -e "USE ${DB_NAME};" 2>/dev/null; then
    echo "ADVERTENCIA: La base de datos '${DB_NAME}' no existe aún. Puede que se esté inicializando..." >&2
    echo "La aplicación intentará conectarse cuando la base de datos esté disponible."
else
    echo "Base de datos '${DB_NAME}' verificada correctamente."

    # Si la BD existe pero faltan tablas base, importar el esquema (útil en despliegues tipo Coolify)
    AUTO_SCHEMA_IMPORT="${AUTO_SCHEMA_IMPORT:-1}"
    SCHEMA_FILE="${SCHEMA_FILE:-/var/www/html/database/database.sql}"

    table_exists() {
      local table="$1"
      local found
      found=$(mysql --defaults-extra-file="$TMP_CNF" --protocol=tcp -N -B -e "SELECT 1 FROM information_schema.TABLES WHERE TABLE_SCHEMA='${DB_NAME}' AND TABLE_NAME='${table}' LIMIT 1;" 2>/dev/null || true)
      [[ "$found" == "1" ]]
    }

    if [ "$AUTO_SCHEMA_IMPORT" = "1" ] && ! table_exists "users_data"; then
        if [ -f "$SCHEMA_FILE" ]; then
            echo "ADVERTENCIA: No existe la tabla base 'users_data'. Importando esquema desde ${SCHEMA_FILE} (AUTO_SCHEMA_IMPORT=${AUTO_SCHEMA_IMPORT})..."
            mysql --defaults-extra-file="$TMP_CNF" --protocol=tcp "${DB_NAME}" < "$SCHEMA_FILE"

            if table_exists "users_data"; then
                echo "Esquema importado correctamente."
            else
                echo "ERROR: Se importó el esquema pero 'users_data' sigue sin existir. Revisa el contenido de ${SCHEMA_FILE}." >&2
                exit 1
            fi
        else
            echo "ERROR: Falta la tabla base 'users_data' y no se encontró el archivo de esquema: ${SCHEMA_FILE}" >&2
            exit 1
        fi
    fi

    # Aplicar migración idempotente para instalaciones existentes (volumen mysql_data ya creado)
    if [ "${AUTO_MIGRATE:-1}" = "1" ] && [ -f /var/www/html/scripts/update_schema_v2.php ]; then
        echo "Aplicando migraciones (AUTO_MIGRATE=${AUTO_MIGRATE:-1})..."
        if ! php /var/www/html/scripts/update_schema_v2.php; then
            echo "ADVERTENCIA: Falló la migración automática. La aplicación seguirá arrancando, pero el registro puede fallar si faltan columnas." >&2
        fi
    fi
fi

# Limpiar archivo temporal
rm -f "$TMP_CNF"
trap - EXIT

# Ejecutar el comando original
exec "$@"
