FROM php:8.2-apache

# Installa estensioni per il database
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Abilita il rewrite url di Apache
RUN a2enmod rewrite