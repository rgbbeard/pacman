FROM php:8.0-apache

LABEL authors="davide"

RUN a2enmod headers rewrite

COPY . /var/www/html
