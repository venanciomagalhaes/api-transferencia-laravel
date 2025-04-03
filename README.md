1 -  instalar o docker
https://www.nerdlivre.com.br/instalando-docker-e-docker-compose-no-ubuntu-24-04/

cp .env.example .env

APP_NAME="Especializa Ti"
APP_URL=http://localhost:8989

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379


Suba os containers do projeto

docker-compose up -d
Acessar o container

docker-compose exec app bash
Instalar as dependências do projeto

composer install
Gerar a key do projeto Laravel

php artisan key:generate

chmod 777 -R .

php artisan optimize:clear


php artisan migrate



confirmar tudo

php artisan l5-swagger:generate
