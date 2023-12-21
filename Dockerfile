# build frontend
FROM node@sha256:7ce8b205d15e30fd395e5fa4000bcdf595fcff3f434fe75822e54e82a5f5cf82 as build-frontend

WORKDIR /app

COPY ./frontend/package.json ./
COPY ./frontend/package-lock.json ./
RUN npm install

COPY ./frontend/ ./
RUN npm run build

# Deploy everything
FROM trafex/php-nginx@sha256:6d56d20a4752470beff6841f96293e2923ae2cb82617d1c449f6d6ef32f1c234

USER root
RUN apk update
RUN apk add --no-cache php82-pdo php82-pdo_mysql git

# Install PHPMailer
RUN wget https://github.com/PHPMailer/PHPMailer/archive/master.zip -O /usr/share/php82/PHPMailer.zip
RUN unzip /usr/share/php82/PHPMailer.zip -d /usr/share/php82/
RUN rm /usr/share/php82/PHPMailer.zip

RUN git clone https://github.com/thephpleague/oauth2-google.git /usr/share/php82/oauth2-google

COPY nginx/default.conf /etc/nginx/conf.d/default.conf

USER nobody
RUN rm -rf /var/www/html/*

COPY ./ebooks /var/www/ebooks

COPY ./backend /var/www/html

# move frontend in /usr/share/nginx/html
COPY --from=build-frontend /app/build /var/www/html
