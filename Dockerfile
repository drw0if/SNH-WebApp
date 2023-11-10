# build frontend

FROM trafex/php-nginx

USER root
RUN apk update
RUN apk add --no-cache php82-pdo php82-pdo_mysql

USER nobody
RUN rm -rf /var/www/html/*
COPY ./backend /usr/share/nginx/html/api
# move frontend in /usr/share/nginx/html
