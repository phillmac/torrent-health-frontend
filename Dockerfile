FROM peelvalley/php


WORKDIR /var/www/html

COPY . ./


USER www-data
RUN composer --no-ansi --no-progress --quiet install


USER root

EXPOSE 80