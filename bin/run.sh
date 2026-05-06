#!/usr/bin/env bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
APP_DIR="${PROJECT_ROOT}/app"
COMPOSE_CMD="docker-compose"

if [ ! -f "${APP_DIR}/composer.json" ]; then
  echo "Brak katalogu app/ z projektem Symfony."
  exit 1
fi

if [ ! -f "${APP_DIR}/.env" ]; then
  if [ -f "${APP_DIR}/.env.example" ]; then
    cp "${APP_DIR}/.env.example" "${APP_DIR}/.env"
    echo "Utworzono app/.env na podstawie app/.env.example"
  else
    echo "Brak app/.env i app/.env.example"
    exit 1
  fi
fi

cd "${PROJECT_ROOT}"

if docker compose version >/dev/null 2>&1; then
  COMPOSE_CMD="docker compose"
fi

echo "Instalacja zaleznosci Composer..."
docker run --rm \
  --user "$(id -u):$(id -g)" \
  -v "${APP_DIR}:/app" \
  -w /app \
  composer:2 install

echo "Budowanie i start kontenera..."
${COMPOSE_CMD} up --build -d

echo "Czekam na gotowosc bazy..."
for i in {1..30}; do
  if ${COMPOSE_CMD} exec -T db mysqladmin ping -h "127.0.0.1" -uroot -p"${DB_ROOT_PASSWORD:-root}" --silent >/dev/null 2>&1; then
    break
  fi
  sleep 1
done

echo "Tworzenie bazy i tabel Doctrine..."
${COMPOSE_CMD} exec -T app php bin/console doctrine:database:create --if-not-exists --no-interaction
${COMPOSE_CMD} exec -T app php bin/console doctrine:schema:update --force --no-interaction

echo "Aplikacja dziala pod adresem: http://localhost:${APP_PORT:-15014}"
echo "phpMyAdmin dziala pod adresem: http://localhost:${PHPMYADMIN_PORT:-8086}"
