#!/bin/sh

# Generate alertmanager.yml from environment variables
cat <<EOF > /alertmanager/alertmanager.yml
global:
  smtp_smarthost: '${SMTP_SMARTHOST}'
  smtp_from: '${SMTP_FROM}'
  smtp_auth_username: '${SMTP_AUTH_USERNAME}'
  smtp_auth_password: '${SMTP_AUTH_PASSWORD}'
  smtp_require_tls: ${SMTP_REQUIRE_TLS}

route:
  group_by: ['alertname']
  group_wait: 30s
  group_interval: 5m
  repeat_interval: 1h
  receiver: 'email-notifications'

receivers:
  - name: 'email-notifications'
    email_configs:
      - to: '${ALERT_EMAIL_TO}'
        send_resolved: true
EOF

# Start Alertmanager
exec /bin/alertmanager --config.file=/alertmanager/alertmanager.yml --storage.path=/alertmanager