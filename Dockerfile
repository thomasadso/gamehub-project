FROM php:8.2-apache

# Instalamos las librerías necesarias para PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# ¡OJO AQUÍ!: Cambiamos el punto por la ruta de la carpeta
COPY gamehub-web/ /var/www/html/

EXPOSE 80