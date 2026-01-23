#!/bin/bash
set -e

# Crear directorio de logs si no existe
mkdir -p /var/www/html/logs
chown -R www-data:www-data /var/www/html/logs
chmod -R 755 /var/www/html/logs

# Configuración de variables de entorno con valores por defecto
DB_HOST="${DB_HOST:-mysql}"
DB_USER="${DB_USER:-root}"
DB_PASS="${DB_PASS:-rootpassword}"
DB_NAME="${DB_NAME:-trabajo_final_php}"

# Crear archivo temporal .my.cnf para evitar exponer contraseña en procesos
TMP_CNF=$(mktemp)
trap "rm -f $TMP_CNF" EXIT

cat > "$TMP_CNF" <<EOF
[client]
host=${DB_HOST}
user=${DB_USER}
password=${DB_PASS}
ssl=0
EOF
chmod 600 "$TMP_CNF"

# Esperar a que MySQL esté listo con timeout
echo "Esperando a que MySQL esté listo..."
MAX_ATTEMPTS=60
ATTEMPT=0
MYSQL_READY=0

while [ $ATTEMPT -lt $MAX_ATTEMPTS ]; do
    if mysqladmin ping -h "${DB_HOST}" -u "${DB_USER}" -p"${DB_PASS}" --skip-ssl --silent 2>/dev/null; then
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
if ! mysql -h "${DB_HOST}" -u "${DB_USER}" -p"${DB_PASS}" --skip-ssl -e "USE ${DB_NAME};" 2>/dev/null; then
    echo "ADVERTENCIA: La base de datos '${DB_NAME}' no existe aún. Puede que se esté inicializando..." >&2
    echo "La aplicación intentará conectarse cuando la base de datos esté disponible."
else
    echo "Base de datos '${DB_NAME}' verificada correctamente."
fi

# Limpiar archivo temporal
rm -f "$TMP_CNF"
trap - EXIT

# Ejecutar el comando original
exec "$@"
