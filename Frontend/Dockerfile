FROM php:8.2-apache

# Installa le librerie per cURL
RUN apt-get update && apt-get install -y libcurl4-openssl-dev

#Installal'estensione cURL di PHP
RUN docker-php-ext-install curl

#Copia i file del frontend nell'immagine 
COPY ./src /var/www/html/

#Imposta i permessi corretti per Apache
RUN chown -R www-data:www-data /var/www/html