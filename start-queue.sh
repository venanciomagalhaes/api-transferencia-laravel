#!/bin/bash
# A partir do diretório da aplicação, executa o comando de filas do Laravel
cd /var/www
php artisan queue:work --daemon --sleep=3 --tries=3
