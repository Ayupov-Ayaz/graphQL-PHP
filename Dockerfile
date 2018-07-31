FROM php:5.6-apache-jessie
MAINTAINER ayaz.ayupov@gmail.com
RUN docker-php-ext-install pdo_mysql mysql mysqli