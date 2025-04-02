FROM php:8.3-fpm

# Define o nome do usuário
ARG user=venancio-docker
ARG uid=1000

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    cron

# Limpa cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala extensões do PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets

# Obtém a versão mais recente do Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria usuário para rodar o Composer e comandos Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Instala e habilita Redis
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Configuração do cron
RUN mkdir -p /var/run/cron /var/spool/cron/crontabs && \
    chmod 0777 /var/run/cron /var/spool/cron/crontabs

# Copia arquivo do cron e script de fila
COPY cronfile /etc/cron.d/start-queue
COPY start-queue.sh /usr/local/bin/start-queue.sh

# Define permissões para o cron e o script
RUN chmod +x /usr/local/bin/start-queue.sh && \
    chmod 0644 /etc/cron.d/start-queue && \
    chown root:root /etc/cron.d/start-queue && \
    crontab /etc/cron.d/start-queue

# Define o diretório de trabalho
WORKDIR /var/www

# Copia configurações personalizadas do PHP
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Retorna para usuário root para evitar problemas com cron
USER root

# Comando para iniciar cron e php-fpm
CMD service cron start && php-fpm
