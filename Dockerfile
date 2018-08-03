FROM php:7-apache
MAINTAINER ayaz.ayupov@gmail.com
RUN docker-php-ext-install pdo_mysql mysqli pdo