#!/bin/sh
set -eu

TEMPLATE_PATH="/etc/alertmanager/alertmanager.yml.tpl"
OUT_PATH="/etc/alertmanager/alertmanager.yml"

escape_sed_repl() {
  # Escape for sed replacement (delimiter: |)
  # shellcheck disable=SC2001
  printf '%s' "$1" | sed -e 's/[\\\/&]/\\&/g'
}

require_tls="${SMTP_REQUIRE_TLS:-true}"
case "$require_tls" in
  true|false) ;;
  *)
    echo "ERROR: SMTP_REQUIRE_TLS must be 'true' or 'false' (got: $require_tls)" >&2
    exit 1
    ;;
esac

smtp_smarthost="${SMTP_SMARTHOST:-}"
smtp_from="${SMTP_FROM:-}"
smtp_auth_username="${SMTP_AUTH_USERNAME:-}"
smtp_auth_password="${SMTP_AUTH_PASSWORD:-}"
alert_email_to="${ALERT_EMAIL_TO:-}"

if [ -z "$smtp_smarthost" ] || [ -z "$smtp_from" ] || [ -z "$smtp_auth_username" ] || [ -z "$smtp_auth_password" ] || [ -z "$alert_email_to" ]; then
  echo "ERROR: Missing required env vars for Alertmanager email." >&2
  echo "Required: SMTP_SMARTHOST, SMTP_FROM, SMTP_AUTH_USERNAME, SMTP_AUTH_PASSWORD, ALERT_EMAIL_TO" >&2
  exit 1
fi

sed \
  -e "s|__SMTP_SMARTHOST__|$(escape_sed_repl "$smtp_smarthost")|g" \
  -e "s|__SMTP_FROM__|$(escape_sed_repl "$smtp_from")|g" \
  -e "s|__SMTP_AUTH_USERNAME__|$(escape_sed_repl "$smtp_auth_username")|g" \
  -e "s|__SMTP_AUTH_PASSWORD__|$(escape_sed_repl "$smtp_auth_password")|g" \
  -e "s|__ALERT_EMAIL_TO__|$(escape_sed_repl "$alert_email_to")|g" \
  -e "s|__SMTP_REQUIRE_TLS__|$require_tls|g" \
  "$TEMPLATE_PATH" > "$OUT_PATH"

exec /bin/alertmanager \
  --config.file="$OUT_PATH" \
  --storage.path=/alertmanager \
  --web.listen-address=:9093

