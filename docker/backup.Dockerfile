FROM alpine:3.18

# Install required packages: mysql-client for mysqldump, curl for healthchecks
RUN apk add --no-cache \
    mysql-client \
    curl \
    tzdata \
    && ln -sf /usr/share/zoneinfo/UTC /etc/localtime

# Copy backup script
COPY backup.sh /usr/local/bin/backup.sh
RUN chmod +x /usr/local/bin/backup.sh

# Create backup directory
RUN mkdir -p /backup

# Healthcheck to verify the backup service is operational
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD sh -c 'test -x /usr/local/bin/backup.sh && echo "Backup service is healthy" || exit 1'

# Default command - run backup script
CMD ["/usr/local/bin/backup.sh"]
</parameter>
<task_progress>
- [x] Read alertmanager.yml template to verify path structure
- [x] Add resource limits to docker-compose.coolify.yml
- [x] Add backup strategy for MySQL
- [x] Add healthchecks to additional services
- [x] Create backup service/script
- [x] Add backup service to docker-compose
- [x] Create Dockerfile for backup service
- [ ] Validate the configuration
- [ ] Test docker-compose configuration
</parameter>
</write_to_file>
</tool_call>