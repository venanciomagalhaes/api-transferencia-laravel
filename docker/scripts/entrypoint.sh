#!/bin/sh
set -e

echo "Configurando Git seguro..."
git config --global --add safe.directory /var/www/html

echo "Instalando dependências..."
if [ ! -d vendor ]; then
  if command -v composer >/dev/null; then
    composer install --no-interaction
  else
    echo "❌ Composer não encontrado"
    exit 1
  fi
fi

if [ ! -d node_modules ]; then
  if command -v npm >/dev/null; then
    npm install
  else
    echo "❌ npm não encontrado"
    exit 1
  fi
fi

echo "Compilando assets..."
if ! npm run build; then
  echo "❌ Falha ao compilar assets"
  exit 1
fi

echo "Rodando migrações..."
if ! php artisan migrate --seed; then
  echo "❌ Migrações falharam"
  exit 1
fi

echo "Limpando cache e otimizando..."
php artisan cache:clear
php artisan optimize:clear


chown -R www-data:www-data storage bootstrap/cache
chmod -R 777 storage bootstrap/cache

php artisan test

echo "✅ Pronto. Iniciando o PHP-FPM..."
exec php-fpm
