#!/bin/sh
set -e
# Entrypoint script for mysqld-exporter
# Generates .my.cnf file from environment variables
# mysqld-exporter v0.15.0+ requires --config.my-cnf flag (DATA_SOURCE_NAME no longer supported)

# Get password from environment variable or use default
MYSQL_PASSWORD=${MYSQL_ROOT_PASSWORD:-rootpassword}
MYSQL_HOST=${MYSQL_HOST:-mysql}
MYSQL_PORT=${MYSQL_PORT:-3306}

# Create .my.cnf file in /etc directory (standard location)
mkdir -p /etc
cat > /etc/.my.cnf <<EOF
[client]
user=root
password=${MYSQL_PASSWORD}
host=${MYSQL_HOST}
port=${MYSQL_PORT}
EOF

# Make sure the file has correct permissions
chmod 600 /etc/.my.cnf

# Verify the file was created and has content
if [ ! -f /etc/.my.cnf ]; then
    echo "ERROR: Failed to create /etc/.my.cnf" >&2
    exit 1
fi

# Wait for MySQL to be ready before starting exporter
echo "Esperando a que MySQL esté listo..."
MAX_ATTEMPTS=60
ATTEMPT=0
MYSQL_READY=0

# Check if mysql client is available (for connectivity test)
if command -v mysql >/dev/null 2>&1; then
    MYSQL_CLIENT="mysql"
elif [ -f /usr/bin/mysql ]; then
    MYSQL_CLIENT="/usr/bin/mysql"
else
    echo "ADVERTENCIA: mysql client no encontrado, saltando verificación de conectividad"
    MYSQL_CLIENT=""
fi

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
    if [ -n "$MYSQL_CLIENT" ]; then
        # Try to connect to MySQL
        if $MYSQL_CLIENT --defaults-file=/etc/.my.cnf -e "SELECT 1;" >/dev/null 2>&1; then
            MYSQL_READY=1
            break
        fi
    else
        # Fallback: try to ping MySQL using netcat or similar
        if command -v nc >/dev/null 2>&1; then
            if nc -z "${MYSQL_HOST}" "${MYSQL_PORT}" 2>/dev/null; then
                MYSQL_READY=1
                break
            fi
        else
            # If no tools available, just wait a bit and assume it's ready
            if [ $ATTEMPT -gt 10 ]; then
                echo "ADVERTENCIA: No se puede verificar MySQL, asumiendo que está listo"
                MYSQL_READY=1
                break
            fi
        fi
    fi
    ATTEMPT=$((ATTEMPT + 1))
    echo "Intento $ATTEMPT/$MAX_ATTEMPTS: MySQL no está listo, esperando..."
    sleep 2
done

if [ $MYSQL_READY -eq 0 ]; then
    echo "ERROR: MySQL no está disponible después de $MAX_ATTEMPTS intentos" >&2
    exit 1
fi

echo "MySQL está listo! Iniciando mysqld_exporter..."

# Find mysqld_exporter binary
MYSQLD_EXPORTER=""
if command -v mysqld_exporter >/dev/null 2>&1; then
    MYSQLD_EXPORTER="mysqld_exporter"
elif [ -x /bin/mysqld_exporter ]; then
    MYSQLD_EXPORTER="/bin/mysqld_exporter"
elif [ -x /usr/bin/mysqld_exporter ]; then
    MYSQLD_EXPORTER="/usr/bin/mysqld_exporter"
elif [ -x /usr/local/bin/mysqld_exporter ]; then
    MYSQLD_EXPORTER="/usr/local/bin/mysqld_exporter"
else
    echo "ADVERTENCIA: mysqld_exporter no encontrado en rutas estándar, buscando..." >&2
    # Use find and capture first result
    FOUND_PATH=$(find /usr /bin /sbin -name mysqld_exporter -type f -executable 2>/dev/null | head -1)
    if [ -n "$FOUND_PATH" ] && [ -x "$FOUND_PATH" ]; then
        MYSQLD_EXPORTER="$FOUND_PATH"
        echo "Encontrado en: $MYSQLD_EXPORTER"
    else
        echo "ERROR: No se pudo encontrar el binario mysqld_exporter" >&2
        echo "Rutas verificadas: /bin, /usr/bin, /usr/local/bin" >&2
        exit 1
    fi
fi

echo "Usando mysqld_exporter: $MYSQLD_EXPORTER"

# Execute mysqld_exporter with --config.my-cnf flag pointing to our config file
# Version 0.18.0+ requires this flag instead of DATA_SOURCE_NAME
exec "$MYSQLD_EXPORTER" --config.my-cnf=/etc/.my.cnf "$@"
