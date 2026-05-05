#!/bin/sh
set -e

if [ ! -f .env ]; then
  cp .env.example .env
fi

sed -i "s/^DB_HOST=.*/DB_HOST=db/" .env
sed -i "s/^DB_PORT=.*/DB_PORT=3306/" .env
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=ekahal_products/" .env
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=ekahal/" .env
sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=ekahal/" .env

composer install --no-interaction --prefer-dist
php artisan key:generate --force
php artisan migrate --seed --force

php artisan serve --host=0.0.0.0 --port=8000
