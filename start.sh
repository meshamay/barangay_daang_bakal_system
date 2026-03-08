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

# Ensure public storage symlink is valid for serving uploaded images/files
if [ -L public/storage ] || [ -e public/storage ]; then
  rm -rf public/storage
fi
php artisan storage:link --no-interaction || true

# Runtime DB bootstrap (only for sqlite)
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
  mkdir -p database
  SQLITE_PATH="${DB_DATABASE:-database/database.sqlite}"

  case "$SQLITE_PATH" in
    /*) ;;
    *) SQLITE_PATH="$(pwd)/$SQLITE_PATH" ;;
  esac

  SQLITE_DIR=$(dirname "$SQLITE_PATH")
  mkdir -p "$SQLITE_DIR"
  touch "$SQLITE_PATH"
fi

php artisan migrate --force --no-interaction

exec php \
  -d upload_max_filesize=12M \
  -d post_max_size=40M \
  -S 0.0.0.0:${PORT:-8080} \
  -t public
