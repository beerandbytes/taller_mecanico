FROM alpine:3.18

# Install required packages: mysql-client for mysqldump, curl for healthchecks
RUN apk add --no-cache \
    mysql-client \
    curl \
    tzdata \
    && ln -sf /usr/share/zoneinfo/UTC /etc/localtime

# Copy backup script
COPY docker/backup.sh /usr/local/bin/backup.sh
RUN chmod +x /usr/local/bin/backup.sh

# Create backup directory
RUN mkdir -p /backup

# Healthcheck to verify the backup service is operational
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD sh -c 'test -x /usr/local/bin/backup.sh && echo "Backup service is healthy" || exit 1'

# Default command - run backup script
CMD ["/usr/local/bin/backup.sh"]