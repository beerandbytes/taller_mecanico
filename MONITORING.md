# Guía de Monitoreo - Taller Mecánico

Este documento describe el sistema de monitoreo implementado con Prometheus y Grafana para el proyecto Taller Mecánico.

## 📊 Arquitectura de Monitoreo

El sistema de monitoreo está compuesto por:

- **Prometheus**: Recolector y almacén de métricas
- **Grafana**: Visualización de métricas y dashboards
- **Node Exporter**: Métricas del sistema operativo
- **MySQL Exporter**: Métricas de la base de datos MySQL
- **PHP Metrics Endpoint**: Endpoint personalizado que expone métricas de la aplicación PHP

## 🚀 Inicio Rápido

### Con Docker Compose

El sistema de monitoreo se inicia automáticamente con Docker Compose:

```bash
docker-compose up -d
```

Una vez iniciado, puedes acceder a:

- **Grafana**: http://localhost:3000
  - **Usuario:** `admin` (por defecto)
  - **Contraseña:** `admin123` (por defecto)
- **Prometheus**: http://localhost:9090
- **Aplicación Web**: http://localhost:8080
- **Endpoint de Métricas**: http://localhost:8080/metrics.php

## 🔐 Credenciales de Acceso a Grafana

### Credenciales por Defecto

Para acceder a Grafana, utiliza las siguientes credenciales:

- **URL:** http://localhost:3000
- **Usuario:** `admin`
- **Contraseña:** `admin123`

### Configuración de Credenciales

Las credenciales de Grafana se pueden personalizar en el archivo `.env`:

```env
# Configuración de Grafana
GRAFANA_ADMIN_USER=admin
GRAFANA_ADMIN_PASSWORD=admin123
```

**⚠️ IMPORTANTE:** 
- Cambia estas credenciales por defecto en entornos de producción
- Las credenciales se aplican al reiniciar el contenedor de Grafana
- Si cambias las credenciales, reinicia el servicio: `docker-compose restart grafana`

## 📈 Métricas Disponibles

### Métricas de la Aplicación PHP

El endpoint `/metrics.php` expone las siguientes métricas:

#### Métricas HTTP
- `app_http_requests_total{method, status}` - Contador total de requests HTTP por método y código de estado
- `app_http_response_time_seconds` - Tiempo de respuesta HTTP (summary con quantiles: 0.5, 0.9, 0.95, 0.99)
- `app_http_response_time_seconds_max` - Tiempo máximo de respuesta
- `app_http_response_time_seconds_min` - Tiempo mínimo de respuesta

#### Métricas de Base de Datos
- `app_db_connection_healthy` - Estado de salud de la conexión a la base de datos (1=healthy, 0=unhealthy)

#### Métricas de Negocio
- `app_users_total` - Total de usuarios registrados
- `app_users_by_role{role}` - Usuarios agrupados por rol (admin/user)
- `app_citas_total` - Total de citas
- `app_citas_by_status{status}` - Citas agrupadas por estado (futura/pasada)
- `app_noticias_total` - Total de noticias
- `app_consejos_total` - Total de consejos
- `app_sessions_active` - Sesiones activas (aproximado)

### Métricas del Sistema (Node Exporter)

- `node_cpu_seconds_total` - Uso de CPU
- `node_memory_*` - Uso de memoria
- `node_filesystem_*` - Uso de disco
- Y muchas más métricas del sistema

### Métricas de MySQL (MySQL Exporter)

- `mysql_global_status_*` - Estado global de MySQL
- `mysql_global_variables_*` - Variables globales de MySQL
- `mysql_*` - Otras métricas de rendimiento

## 📊 Dashboards de Grafana

El sistema incluye 4 dashboards preconfigurados:

### 1. Dashboard de Aplicación
- **UID**: `aplicacion-dashboard`
- **Métricas**: Requests HTTP, tiempos de respuesta, errores
- **Uso**: Monitoreo del rendimiento de la aplicación web

### 2. Dashboard de Base de Datos
- **UID**: `base-datos-dashboard`
- **Métricas**: Conexiones MySQL, consultas, rendimiento
- **Uso**: Monitoreo de la base de datos

### 3. Dashboard de Negocio
- **UID**: `negocio-dashboard`
- **Métricas**: Usuarios, citas, noticias, consejos
- **Uso**: Métricas de negocio y uso de la aplicación

### 4. Dashboard de Sistema
- **UID**: `sistema-dashboard`
- **Métricas**: CPU, memoria, disco, red
- **Uso**: Monitoreo de la infraestructura

## 🔧 Configuración

### Configuración de Prometheus

El archivo de configuración se encuentra en `monitoring/prometheus/prometheus.yml`.

**Intervalos de scraping:**
- Prometheus: 15 segundos
- Aplicación PHP: 10 segundos
- Node Exporter: 15 segundos
- MySQL Exporter: 30 segundos

**Retención de datos:**
- Por defecto: 15 días
- Configurable en `docker-compose.yml` mediante `--storage.tsdb.retention.time`

### Configuración de Grafana

Grafana se configura automáticamente mediante provisioning:

- **Datasources**: `monitoring/grafana/provisioning/datasources/prometheus.yml`
- **Dashboards**: `monitoring/grafana/provisioning/dashboards/dashboard.yml`

Los dashboards se cargan automáticamente desde `monitoring/grafana/dashboards/`.

## 📝 Sistema de Logging

El sistema implementa un middleware de logging automático que captura:

1. **Método HTTP** (GET, POST, PUT, DELETE, etc.)
2. **Código de estado HTTP** (200, 404, 500, etc.)
3. **Tiempo de respuesta** en segundos

### Archivos de Log

Los logs se almacenan en el directorio `logs/`:

- `logs/metrics.log` - Logs de requests HTTP (formato: `METHOD STATUS`)
- `logs/response_time.log` - Tiempos de respuesta (una línea por request)

### Rotación de Logs

El sistema implementa rotación automática de logs:
- Se ejecuta aleatoriamente en 1 de cada 100 requests
- Mantiene las últimas 10,000 líneas por archivo
- Se activa cuando un archivo supera los 5MB

### Funcionamiento

El logging se realiza automáticamente mediante:

1. `includes/header.php` - Inicia la medición del tiempo al inicio del request
2. `includes/footer.php` - Finaliza la medición y escribe los logs al final del request
3. `includes/metrics_logger.php` - Funciones de logging y rotación

## 🚨 Alertas

El sistema incluye reglas de alertas configuradas en `monitoring/prometheus/alerts.yml`:

### Alertas de Aplicación
- **HighErrorRate**: Tasa de errores HTTP > 5% durante 5 minutos
- **CriticalErrorRate**: Tasa de errores HTTP > 10% durante 2 minutos
- **DatabaseConnectionDown**: Conexión a base de datos caída
- **HighResponseTime**: Tiempo de respuesta p95 > 2s durante 5 minutos
- **CriticalResponseTime**: Tiempo de respuesta p95 > 5s durante 2 minutos
- **NoHTTPRequests**: Sin requests HTTP durante 10 minutos

### Alertas de Base de Datos
- **MySQLConnectionsExhausted**: > 80% de conexiones en uso
- **MySQLConnectionsCritical**: > 95% de conexiones en uso
- **MySQLSlowQueries**: Alto número de consultas lentas
- **MySQLDown**: MySQL exporter no disponible

### Alertas de Sistema
- **HighCPUUsage**: Uso de CPU > 80% durante 5 minutos
- **CriticalCPUUsage**: Uso de CPU > 95% durante 2 minutos
- **HighMemoryUsage**: Uso de memoria > 85% durante 5 minutos
- **CriticalMemoryUsage**: Uso de memoria > 95% durante 2 minutos
- **LowDiskSpace**: Espacio en disco < 15%
- **CriticalDiskSpace**: Espacio en disco < 5%

**Nota**: Las alertas están configuradas pero requieren Alertmanager para enviar notificaciones. Actualmente solo se registran en Prometheus.

## 🔍 Consultas Prometheus Útiles

### Requests por segundo
```promql
sum(rate(app_http_requests_total[5m])) by (method)
```

### Tasa de errores
```promql
sum(rate(app_http_requests_total{status=~"5.."}[5m])) 
/ 
sum(rate(app_http_requests_total[5m]))
```

### Tiempo de respuesta promedio
```promql
rate(app_http_response_time_seconds_sum[5m]) 
/ 
rate(app_http_response_time_seconds_count[5m])
```

### Tiempo de respuesta p95
```promql
app_http_response_time_seconds{quantile="0.95"}
```

### Usuarios por rol
```promql
app_users_by_role
```

### Citas futuras vs pasadas
```promql
app_citas_by_status
```

## 🐛 Solución de Problemas

### El endpoint `/metrics.php` no funciona

1. Verificar que el archivo existe en `/var/www/html/metrics.php` (Docker) o en la raíz del proyecto (local)
2. Verificar permisos del directorio `logs/`:
   ```bash
   chmod 755 logs/
   ```
3. Verificar que el directorio `logs/` existe y es escribible

### Prometheus no puede scrapear la aplicación

1. Verificar que el servicio `web` está corriendo:
   ```bash
   docker-compose ps
   ```
2. Verificar conectividad desde Prometheus:
   ```bash
   docker-compose exec prometheus wget -O- http://web:80/metrics.php
   ```
3. Verificar la configuración en `monitoring/prometheus/prometheus.yml`

### Grafana no muestra datos

1. Verificar que Prometheus está funcionando: http://localhost:9090
2. Verificar que el datasource está configurado correctamente en Grafana
3. Verificar que los dashboards están cargados: Dashboards → Browse
4. Verificar el rango de tiempo seleccionado en Grafana

### Los logs no se están generando

1. Verificar permisos del directorio `logs/`:
   ```bash
   ls -la logs/
   chmod 755 logs/
   ```
2. Verificar que `includes/metrics_logger.php` está siendo incluido
3. Verificar logs de PHP para errores:
   ```bash
   docker-compose logs web | grep -i error
   ```

## 📚 Recursos Adicionales

- [Documentación de Prometheus](https://prometheus.io/docs/)
- [Documentación de Grafana](https://grafana.com/docs/)
- [PromQL Query Language](https://prometheus.io/docs/prometheus/latest/querying/basics/)
- [Guía de Dashboards de Grafana](https://grafana.com/docs/grafana/latest/dashboards/)

## 🔐 Seguridad

**Importante**: En producción, cambia las credenciales por defecto de Grafana y considera:

- Configurar autenticación para Prometheus
- Restringir acceso a los puertos de monitoreo
- Usar HTTPS para Grafana
- Configurar firewall para los servicios de monitoreo

## 📝 Notas

- El sistema de monitoreo solo está disponible cuando se ejecuta con Docker Compose
- Los logs de métricas se almacenan localmente y pueden crecer con el tiempo
- Considera implementar rotación de logs más agresiva en producción
- Las métricas de sesiones activas son aproximadas (basadas en archivos de sesión PHP)
