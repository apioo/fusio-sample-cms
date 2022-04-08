FROM fusio/fusio:3.0
COPY ./resources /var/www/html/fusio/resources
COPY ./src /var/www/html/fusio/src
COPY ./.fusio.yml /var/www/html/fusio/.fusio.yml
COPY ./composer.json /var/www/html/fusio/composer.json
COPY ./container.php /var/www/html/fusio/container.php
RUN chown -R www-data: /var/www/html/fusio
