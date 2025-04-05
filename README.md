
# 💸 API de Transferência com Laravel

API desenvolvida com Laravel, Docker, Redis, MySQL e testada com PestPHP. Simula um sistema de transferência entre usuários com regras de autorização e notificação externas.

---

## 🐳 Requisitos

- Docker e Docker Compose  
  👉 Instale seguindo este tutorial:  
  [https://www.nerdlivre.com.br/instalando-docker-e-docker compose-no-ubuntu-24-04/](https://www.nerdlivre.com.br/instalando-docker-e-docker compose-no-ubuntu-24-04/)

---

## 🚀 Subindo o projeto

Clone o projeto e entre na pasta:

```bash
git clone https://github.com/venanciomagalhaes/api-transferencia-laravel.git
cd api-transferencia-laravel
```

Copie o arquivo `.env` de exemplo:

```bash
cp .env.example .env
```

Edite as seguintes variáveis no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=user
DB_PASSWORD=userpass

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Crie as imagens e suba os containers com Docker Compose:

```bash
docker compose build
docker compose up -d
```

A aplicação rodará no endereço http://localhost:8989

---

## 🛠️ Configuração dentro do container

Entre no container da aplicação:

```bash
docker compose exec app bash
```

Instale as dependências:

```bash
composer install
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Ajuste permissões para evitar erros de cache/log:

```bash
chmod 777 -R .
```


Rode as migrações:

```bash
php artisan migrate
```

Rode os seeders:

```bash
php artisan db:seed
```


Gere a documentação com Swagger:

```bash
php artisan l5-swagger:generate
```
A documentação estará disponível em http://localhost:8989/api/documentation

---

## 🧪 Ambiente de Testes


Rode, dentro do container, os testes com PestPHP:

```bash
php artisan test
```

