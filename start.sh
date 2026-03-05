#!/bin/sh
set -e

# Ensure .env exists
[ -f .env ] || cp .env.example .env

# If APP_KEY env var is missing/empty, ensure .env has one and export it for this process
if [ -z "${APP_KEY:-}" ]; then
  CURRENT_KEY=$(grep '^APP_KEY=' .env | head -n 1 | cut -d '=' -f2-)

  if [ -z "$CURRENT_KEY" ]; then
    php artisan key:generate --force --no-interaction
    CURRENT_KEY=$(grep '^APP_KEY=' .env | head -n 1 | cut -d '=' -f2-)
  fi

  export APP_KEY="$CURRENT_KEY"
fi

# Prevent stale cached config from keeping an empty APP_KEY
php artisan config:clear --no-interaction || true

# Runtime DB bootstrap
mkdir -p database
touch database/database.sqlite

php artisan migrate --force --no-interaction

exec php -S 0.0.0.0:${PORT:-8080} -t public
