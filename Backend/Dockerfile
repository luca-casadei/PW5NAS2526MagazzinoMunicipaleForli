FROM php:8.2-apache

# Installa driver per il DB
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Abilita il modulo rewrite
RUN a2enmod rewrite

# Configura Apache per accettare .htaccess in /var/www/html
RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' >> /etc/apache2/apache2.conf

# Copia codice del backend nell'immagine
COPY ./src /var/www/html/

# Imposta i permessi corretti per Apache
RUN chown -R www-data:www-data /var/www/html